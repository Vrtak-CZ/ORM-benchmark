<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

use DibiConnection;

/**
 * Dibi persister
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class DibiPersister extends \Nette\Object implements IPersister
{
	/** @var array */
	public static $modificators = array(
		'ActiveMapper\DataTypes\Bool' => "%b",
		'ActiveMapper\DataTypes\Date' => "%d",
		'ActiveMapper\DataTypes\DateTime' => "%t",
		'ActiveMapper\DataTypes\Float' => "%f",
		'ActiveMapper\DataTypes\Int' => "%i",
		'ActiveMapper\DataTypes\String' => "%sN",
		'ActiveMapper\DataTypes\Text' => "%sN",
	);
	/** @var ActiveMapper\Manager */
	private $em;
	/** @var string */
	private $entity;
	/** @var DibiConnection */
	private $connection;

	/**
	 * Constructor
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity
	 * @param DibiConnection $connection
	 * @throws InvalidArgumentException
	 */
	public function __construct(Manager $em, $entity, DibiConnection $connection = NULL)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
		$this->connection = $connection;
		if ($connection == NULL)
			$this->connection = $this->em->connection;
	}

	/**
	 * Get values
	 *
	 * @param mixed $entity
	 * @return array
	 */
	protected function getValues(&$entity)
	{
		return $this->em->getIdentityMap($this->entity)->getSavedValues($entity);
	}

	/**
	 * Insert data
	 *
	 * @param mixed $entity
	 */
	public function insert($entity)
	{
		$values = $this->getValues($entity);
		if (empty($values))
			return NULL;

		$metadata = Metadata::getMetadata($this->entity);
		$res = $this->connection->insert($metadata->tableName, $values)->execute();
		return $res;
	}

	/**
	 * Update data
	 *
	 * @param mixed $entity
	 */
	public function update($entity)
	{
		$values = $this->getValues($entity);
		if (empty($values))
			return NULL;

		$metadata = Metadata::getMetadata($this->entity);
		$res = $this->connection->update($metadata->tableName, $values)
				->where("[".$metadata->primaryKey."] = ".$this->getModificator($metadata->primaryKey),
						$metadata->getPrimaryKeyValue($entity))->execute();
		return $res;
	}

	/**
	 * Delete data
	 *
	 * @param mixed $entity
	 */
	public function delete($entity)
	{
		$metadata = Metadata::getMetadata($this->entity);
		return $this->connection->delete($metadata->tableName)
				->where("[".$metadata->primaryKey."] = ".$this->getModificator($metadata->primaryKey),
						$metadata->getPrimaryKeyValue($entity))->execute();
	}

	/**
	 * Update inversed one to one association keys
	 *
	 * @param ActiveMapper\Associations\OneToOne $association
	 * @param mixed $primaryKey
	 * @param mixed $key
	 */
	public function persistInversedOneToOneAssociation(Associations\OneToOne $association, $primaryKey, $key)
	{
		$metadata = Metadata::getMetadata($this->entity);
		$this->connection->update($metadata->tableName, array($association->targetColumn => $key))
			->where("[{$metadata->primaryKey}] = ".$this->getModificator($metadata->primaryKey), $primaryKey)->execute();
	}

	/**
	 * Update one to many association keys
	 *
	 * @param ActiveMapper\Associations\OneToMany $association
	 * @param mixed $primaryKeys
	 * @param mixed $key
	 */
	public function persistOneToManyAssociation(Associations\OneToMany $association, $primaryKeys, $key)
	{
		$metadata = Metadata::getMetadata($this->entity);
		$this->connection->update($metadata->tableName, array($association->targetColumn => $key))
			->where("[{$metadata->primaryKey}] IN %in", $primaryKeys)->execute();
	}

	/**
	 * Persist many to many associations
	 *
	 * @param Associations\ManyToMany $association
	 * @param mixed $primaryKey
	 * @param array|NULL $keys
	 * @param bool $delete
	 */
	public function persistManyToManyAssociation(Associations\ManyToMany $association, $primaryKey, $keys, $delete = FALSE)
	{
		$metadata = Metadata::getMetadata($this->entity);

		if (empty($keys)) {
			$this->connection->delete($association->joinTable)->where("[{$association->joinSourceColumn}] = "
					.$this->getModificator($metadata->primaryKey), $primaryKey)->execute();
		} elseif ($delete) {
			$this->connection->delete($association->joinTable)->where("[{$association->joinSourceColumn}] = "
					.$this->getModificator($metadata->primaryKey)." AND [{$association->joinTargetColumn}] IN %in",
				$primaryKey, $keys)->execute();
		}else {
			foreach ($keys as $key) {
				$this->connection->insert($association->joinTable, array($association->joinSourceColumn => $primaryKey,
					$association->joinTargetColumn => $key))->execute();
			}
		}
	}

	/**
	 * Get last generated primary key (autoincrement)
	 *
	 * @param string $sequence
	 * @return mixed
	 */
	public function lastPrimaryKey($sequence = NULL)
	{
		return $this->connection->getInsertId($sequence);
	}

	/**
	 * Get modificator
	 *
	 * @param string $column entity column name
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function getModificator($column)
	{
		$metadata = Metadata::getMetadata($this->entity);
		if (!$metadata->hasColumn($column))
			throw new \InvalidArgumentException("Entity '{$this->entity}' has not '$column' column");

		$class = $metadata->getColumn($column)->reflection->name;
		if (!in_array($class, array_keys(self::$modificators)))
			throw new \NotImplementedException("Support for '$class' datatype not implemented in '".get_called_class()."'");

		return self::$modificators[$class];
	}

	/**
	 * Dibi persister factory
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity
	 * @param DibiConnection $connection
	 * @return ActiveMapper\DibiPersister
	 */
	public static function getDibiPersister(Manager $em, $entity, DibiConnection $connection = NULL)
	{
		return new static($em, $entity, $connection);
	}
}