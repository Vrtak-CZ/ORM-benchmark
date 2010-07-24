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
 * Dibi repository Repository
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class DibiRepository extends \Nette\Object implements IRepository
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
	/** @var ActiveMapper\Manger */
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
	public function __construct(Manager $em, $entity, DibiConnection $connectio = NULL)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
		$this->connection = $connectio;
		if ($connectio == NULL)
			$this->connection = $this->em->connection;
	}

	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param mixed $primaryKey
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function find($primaryKey)
	{
		$metadata = Metadata::getMetadata($this->entity);
		$identityMap = $this->em->getIdentityMap($this->entity);
		$data = $identityMap->find($primaryKey);
		if ($data === NULL) {
			return $identityMap->map($this->em->connection->select("*")->from($metadata->tableName)
				->where("[".Tools::underscore($metadata->primaryKey)."] = "
						.$this->getModificator($metadata->primaryKey), $primaryKey)->execute()->fetch()
			);
		}

		return $data;
	}

	/**
	 * Find all entity
	 *
	 * @return array
	 * @throws InvalidArgumentException
	 */
	public function findAll()
	{
		$metadata = Metadata::getMetadata($this->entity);
		$identityMap = $this->em->getIdentityMap($this->entity);
		return $identityMap->map($this->em->connection->select("*")->from($metadata->tableName)->execute()->fetchAll());
	}

	/**
	 * Method overload for findBy...
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function __call($name, $args)
	{
		if (strncmp($name, 'findBy', 6) === 0 && strlen($name) > 6) {
			$name = lcfirst(substr($name, 6));
			$metadata = Metadata::getMetadata($this->entity);
			$identityMap = $this->em->getIdentityMap($this->entity);
			return $identityMap->map($this->em->connection->select("*")->from($metadata->tableName)
				->where("[".Tools::underscore($name)."] = ".$this->getModificator($name), $args[0])
				->execute()->fetch());
		} else
			return parent::__call($name, $args);
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
	 * Dibi repository factory
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity
	 * @param DibiConnection $connection
	 * @return ActiveMapper\DibiRepository
	 */
	public static function getRepository(Manager $em, $entity, DibiConnection $connection = NULL)
	{
		return new static($em, $entity, $connection);
	}
}