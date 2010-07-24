<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\Associations
 */

namespace ActiveMapper\Associations;

use ActiveMapper\Metadata,
	ActiveMapper\Tools;

/**
 * Lazy load associations data
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 */
class LazyLoad extends \Nette\Object
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
	/** @var string */
	private $name;
	/** @var mixed */
	private $associationKey;

	/**
	 * Costructor
	 *
	 * @param ActiveMapper\Manager $em entity manager
	 * @param string $entity entity class name
	 * @param string $name association name
	 * @param array $entityData
	 */
	public function __construct(\ActiveMapper\Manager $em, $entity, $name, $entityData)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
		$this->name = $name;
		$metadata = Metadata::getMetadata($entity);
		if (!isset($metadata->associations[$name]))
			throw new \InvalidArgumentException("Entity '$entity' not have association '$name'");
		$this->associationKey = $entityData[$metadata->associations[$name]->sourceColumn];
	}

	/**
	 * Get data
	 *
	 * @return mixed
	 */
	public function getData()
	{
		$association = Metadata::getMetadata($this->entity)->associations[$this->name];
		if ($association instanceof OneToOne)
			return $this->getOneToOneData();
		elseif ($association instanceof OneToMany)
			return $this->getOneToManyData();
		elseif ($association instanceof ManyToOne)
			return $this->getManyToOneData();
		return $this->getManyToManyData();
	}

	/**
	 * Get entity by one to one association
	 *
	 * @return mixed
	 */
	protected function getOneToOneData()
	{
		$association = Metadata::getMetadata($this->entity)->associations[$this->name];
		if ($this->em->associationsMap->isMapped($this->entity, $this->name, $this->associationKey))
			return $this->em->find($association->targetEntity, 
					$this->em->associationsMap->find($this->entity, $this->name, $this->associationKey));

		$targetMetadata = Metadata::getMetadata($association->targetEntity);
		if ($association->mapped)
			$modificator = $this->getModificator($this->entity, $association->sourceColumn);
		else
			$modificator = $this->getModificator($association->targetEntity, $association->targetColumn);
		$data = $this->em->connection->select("*")->from($targetMetadata->tableName)
			->where("[{$association->targetColumn}] = $modificator", $this->associationKey)
			->execute();
		$entity = $this->em->getIdentityMap($association->targetEntity)->map($data->fetch());
		$this->em->associationsMap->map($this->entity, $this->name, $this->associationKey,
				$targetMetadata->getPrimaryKeyValue($entity));

		return $entity;
	}

	/**
	 * Get entites by one to many association
	 * 
	 * @return array|NULL
	 */
	protected function getOneToManyData()
	{
		$association = Metadata::getMetadata($this->entity)->associations[$this->name];
		$targetMetadata = Metadata::getMetadata($association->targetEntity);
		$data = $this->em->connection->select("*")->from($targetMetadata->tableName)
			->where("[{$association->targetColumn}] = ".$this->getModificator($this->entity, $association->sourceColumn),
					$this->associationKey)
			->execute();
		$entities = $this->em->getIdentityMap($association->targetEntity)->map($data->fetchAssoc($targetMetadata->primaryKey));
		if (!empty($entities))
			$this->em->associationsMap->map($this->entity, $this->name, $this->associationKey, array_keys($entities));

		return $entities;
	}

	/**
	 * Get entites by one to many association
	 *
	 * @return array|NULL
	 */
	protected function getManyToOneData()
	{
		$association = Metadata::getMetadata($this->entity)->associations[$this->name];
		if ($this->em->associationsMap->isMapped($this->entity, $this->name, $this->associationKey))
			return $this->em->find($association->targetEntity,
					$this->em->associationsMap->find($this->entity, $this->name, $this->associationKey));

		$targetMetadata = Metadata::getMetadata($association->targetEntity);
		$modificator = $this->getModificator($association->targetEntity, $association->targetColumn);
		$data = $this->em->connection->select("*")->from($targetMetadata->tableName)
			->where("[{$association->targetColumn}] = $modificator", $this->associationKey)
			->execute();
        $entity = $this->em->getIdentityMap($association->targetEntity)->map($data->fetch());
		$this->em->associationsMap->map($this->entity, $this->name, $this->associationKey,
				$targetMetadata->getPrimaryKeyValue($entity));

		return $entity;
	}

	/**
	 * Get entities by many to many association
	 *
	 * @return array|NULL
	 */
	protected function getManyToManyData()
	{
		$association = Metadata::getMetadata($this->entity)->associations[$this->name];
		$targetMetadata = Metadata::getMetadata($association->targetEntity);
		$data = $this->em->connection->select("[{$targetMetadata->tableName}].*")->from($targetMetadata->tableName)
			->innerJoin($association->joinTable)->on("[{$association->joinTable}].[{$association->joinSourceColumn}] = "
					.$this->getModificator($this->entity, $association->sourceColumn), $this->associationKey)
			->and("[{$association->joinTable}].[{$association->joinTargetColumn}] = ["
					.$targetMetadata->tableName."].[{$association->targetColumn}]")
			->execute();

		$entities = $this->em->getIdentityMap($association->targetEntity)->map($data->fetchAssoc($targetMetadata->primaryKey));
		if (!empty($entities))
			$this->em->associationsMap->map($this->entity, $this->name, $this->associationKey, array_keys($entities));

		return $entities;
	}

	/**
	 * Get modificator
	 *
	 * @param string $entity entity class name
	 * @param string $column entity column name
	 * @return string
	 * @throws InvalidArgumentException
	 */
	protected function getModificator($entity, $column)
	{
		$metadata = Metadata::getMetadata($entity);
		if (!$metadata->hasColumn($column))
			throw new \InvalidArgumentException("Entity '$entity' has not '$column' column");

		$class = $metadata->getColumn($column)->reflection->name;
		if (!in_array($class, array_keys(self::$modificators)))
			throw new \NotImplementedException("Support for '$class' datatype not implemented in '".get_called_class()."'");

		return self::$modificators[$class];
	}
}