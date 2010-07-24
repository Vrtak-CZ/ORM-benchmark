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

/**
 * Identity map
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
class IdentityMap extends \Nette\Object
{
	/** @var ActiveMapper\Manager */
	protected $em;
	/** @var string */
	protected $entity;
	/** @var array */
	protected $data = array();
	/** @var array */
	protected $idReference = array();
	/** @var array */
	protected $originalData = array();

	/**
	 * Construct
	 *
	 * @param ActiveMapper\Manager $em
	 * @param string $entity full entity class name (with namespace)
	 */
	public function __construct(Manager $em, $entity)
	{
		// TODO: verify entity class

		$this->em = $em;
		$this->entity = $entity;
	}

	/**
	 * Is entity mapped
	 *
	 * @param mixed $entity
	 * @return bool
	 */
	public function isMapped(&$entity)
	{
		if ($this->entity != get_class($entity))
			throw new \InvalidArgumentException("Entity [".get_class($entity)."] is not valid '{$this->entity}' for this identity map.");

		return isset($this->data[spl_object_hash($entity)]);
	}

	/**
	 * Find entity by primary key
	 * 
	 * @param mixed $primaryKey
	 * @return NULL|mixed
	 */
	public function find($primaryKey)
	{
		return isset($this->idReference[$primaryKey]) ? $this->idReference[$primaryKey] : NULL;
	}

	/**
	 * Get entity primary key value
	 *
	 * @param mixed $entity
	 * @return mixed
	 */
	private function getEntityPrimaryKey(&$entity)
	{
		$ref = new \Nette\Reflection\PropertyReflection($this->entity, Metadata::getMetadata($this->entity)->primaryKey);
		$ref->setAccessible(TRUE);
		$pk = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		return $pk;
	}

	/**
	 * Store entity
	 *
	 * @param mixed $entity
	 */
	public function store(&$entity)
	{
		if (!$this->isMapped($entity)) {
			$this->data[spl_object_hash($entity)] = &$entity;
			$this->originalData[spl_object_hash($entity)] = Metadata::getMetadata(get_class($entity))->getValues($entity, FALSE);
			if (($id = $this->getEntityPrimaryKey($entity)) !== NULL)
				$this->idReference[$id] = &$entity;
		}

		return $entity;
	}

	/**
	 * Detach entity
	 *
	 * @param mixed $entity
	 */
	public function detach(&$entity)
	{
		if ($this->isMapped($entity)) {
			unset($this->idReference[$this->getEntityPrimaryKey($entity)]);
			unset($this->data[spl_object_hash($entity)]);
		}
	}

	/**
	 * Remap entity
	 *
	 * @param mixed $entity
	 */
	public function remap(&$entity)
	{
		if ($this->isMapped($entity)) {
			$metadata = Metadata::getMetadata(get_class($entity));
			$this->originalData[spl_object_hash($entity)] = $metadata->getValues($entity, FALSE);
			if (isset($this->originalData[spl_object_hash($entity)][$metadata->primaryKey]))
				unset($this->originalData[spl_object_hash($entity)][$metadata->primaryKey]);
			foreach ($metadata->getAssociationsValues($entity) as $name => $association) {
				$this->em->associationsMap->map(get_class($entity), $name, $metadata->getPrimaryKeyValue($entity), $association);
			}
		}
	}

	/**
	 * Map entity or entities
	 *
	 * @param array $input
	 * @return mixed|array
	 * @throws InvalidArgumentException
	 */
	public function map($input)
	{
		if (is_array($input) && count(array_filter($input, function ($a) {return is_array($a) || $a instanceof \ArrayAccess;}))) {
			$output = array();
			foreach ($input as $key => $row) {
				$output[$key] = $this->_map($row);
			}
			return $output;
		} elseif (is_array($input) || $input instanceof \ArrayAccess) {
			return $this->_map($input);
		} elseif ($input == FALSE) {
			return NULL;
		} else
			throw new \InvalidArgumentException("Map accept only loaded data or loaded data array");
	}

	/**
	 * Map entity
	 *
	 * @param array|ArrayAccess $data
	 * @return mixed
	 */
	protected function &_map($data)
	{
		$metadata = Metadata::getMetadata($this->entity);
		if (!isset($data[$metadata->primaryKey]))
			throw new \InvalidArgumentException("Data for entity '{$this->entity}' must load primary key");
		if (isset($this->idReference[$data[$metadata->primaryKey]]))
			return $this->idReference[$data[$metadata->primaryKey]];
		else {
			$entity = $metadata->getInstance($this->em, $data);
			$this->idReference[$data[$metadata->primaryKey]] = &$entity;
			$this->data[spl_object_hash($entity)] = &$entity;
			$this->originalData[spl_object_hash($entity)] = (array) $data;
			if (isset($this->originalData[spl_object_hash($entity)][$metadata->primaryKey]))
				unset($this->originalData[spl_object_hash($entity)][$metadata->primaryKey]);
			return $entity;
		}
	}

	/**
	 * Get saved values
	 *
	 * @param mixed $entity
	 * @return array
	 */
	public function getSavedValues($entity)
	{
		$metadata = Metadata::getMetadata($this->entity);
		if ($this->isMapped($entity)) {
			$data = $metadata->getValues($entity, FALSE);
			$data = array_diff($data, $this->originalData[spl_object_hash($entity)]);
		} else
			$data = $metadata->getValues($entity);

		$ref = new \Nette\Reflection\PropertyReflection($this->entity, '_associations');
		$ref->setAccessible(TRUE);
		$associationsData = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		foreach (array_merge($metadata->oneToOne, $metadata->manyToOne) as $association) {
			if ($association instanceof Associations\OneToOne && !empty($association->mapped))
				continue;

			if (array_key_exists($association->name, $associationsData) &&
					!($associationsData[$association->name] instanceof Associations\LazyLoad)) {
				$original = $this->em->associationsMap
					->find($this->entity, $association->name, $metadata->getPrimaryKeyValue($entity));
				if (empty($associationsData[$association->name]) && !empty($original))
					$data[$association->sourceColumn] = NULL;
				else {
					$targetMetadata = Metadata::getMetadata(get_class($associationsData[$association->name]));
					$now = $targetMetadata->getPrimaryKeyValue($associationsData[$association->name]);
					if ($original != $now)
						$data[$association->sourceColumn] = $now;
				}
			}
		}

		return $data;
	}
}