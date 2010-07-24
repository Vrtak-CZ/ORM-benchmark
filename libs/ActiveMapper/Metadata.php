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

use Nette\Reflection\ClassReflection,
	Nette\Reflection\PropertyReflection;

/**
 * Entity metada object
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 * @property-read bool $proxy is entity implements proxy?
 * @property-read string $tableName entity table name
 * @property-read array<ActiveMapper\DataTypes\IDataType> $columns entity columns array
 * @property-read string $name entity name (class without namespace)
 * @property-read string $primaryKey entity primary key name
 * @property-read bool $primaryKeyAutoincrement is entity primary key autoincrement
 * @property-read array<ActiveMapper\Associations\IAssociation> $associations
 * @property-read array<ActiveMapper\Associations\OneToOne> $oneToOne
 * @property-read array<ActiveMapper\Associations\OneToMany> $oneToMany
 * @property-read array<ActiveMapper\Associations\ManyToOne> $manyToOne
 * @property-read array<ActiveMapper\Associations\ManyToMany> $manyToMany
 */
class Metadata extends \Nette\Object
{
	/** @var array<ActiveMapper\Metadata> */
	private static $metadata = array();
	/** @var string */
	private $tableName = NULL;
	/** @var array<ActiveMapper\DataTypes\IDataType> */
	private $columns = array();
	/** @var string */
	private $primaryKey = NULL;
	/** @var bool */
	private $primaryKeyAutoincrement = FALSE;
	/** @var string */
	private $entity;
	/** @var string */
	private $name;
	/** @var array<ActiveMapper\Associations\IAssociation> */
	private $associations = array();
	/** @var array<ActiveMapper\Associations\OneToOne> */
	private $oneToOne = array();
	/** @var array<ActiveMapper\Associations\OneToMany> */
	private $oneToMany = array();
	/** @var array<ActiveMapper\Associations\ManyToOne> */
	private $manyToOne = array();
	/** @var array<ActiveMapper\Associations\ManyToMany> */
	private $manyToMany = array();
	/** @var bool */
	private $associationsLoaded = FALSE;

	/**
	 * Construct
	 *
	 * @param string $entity entity class name
	 */
	public function __construct($entity)
	{
		$ref = new ClassReflection($entity);
		// TODO: verify entity class

		$this->entity = $entity;
		if ($pos = strrpos($entity, '\\'))
			$pos++;
		$this->name = substr($entity, $pos);
		if ($ref->hasAnnotation('tableName'))
			$this->tableName = $ref->getAnnotation('tableName');
		else
			$this->tableName = Tools::underscore(Tools::pluralize($this->name));

		foreach ($ref->getProperties() as $property) {
			if ($property->hasAnnotation('column')) {
				$annotation = (array) $property->getAnnotation('column');
				$datatype = 'ActiveMapper\DataTypes\\'.$annotation[0];
				unset($annotation[0]);
				$params = array_merge(array($property->name, $property->hasAnnotation('null')), $annotation);

				if (!\class_exists($datatype))
					throw new \ActiveMapper\InvalidDataTypeException("Data type '$datatype' not exist");

				$this->columns[$property->name] = callback(ClassReflection::from($datatype), 'newInstance')->invokeArgs($params);
			}

			if ($property->hasAnnotation('primary')) {
				if ($property->hasAnnotation('column')) {
					if (empty($this->primaryKey))
						$this->primaryKey = $property->name;
					else
						throw new \NotImplementedException("Multiple column primary key not implemented '$entity'");
				} else
					throw new \NotImplementedException("Primary key must be column $entity::\${$property->name}");
			}

			if ($property->hasAnnotation('autoincrement')) {
				if (!($this->columns[$property->name] instanceof \ActiveMapper\DataTypes\Int)) {
					throw new \ActiveMapper\InvalidDataTypeException(
							"Autoincrement avaiable only for Int data type column $entity::\${$property->name}"
					);
				} elseif ($property->name == $this->primaryKey)
					$this->primaryKeyAutoincrement = TRUE;
				else
					throw new \NotImplementedException("Auto increment for non primary key column not implemented");
			}
		}

		if (!$this->primaryKey)
			throw new \LogicException("Entity without primary key not supported '{$this->entity}'");

		if (!$this->hasProxy() && ($ref->hasAnnotation('OneToOne') ||
				$ref->hasAnnotation('OneToOne') || $ref->hasAnnotation('OneToOne') || $ref->hasAnnotation('OneToOne')))
			throw new \LogicException("Entity associations support only on proxy entity");
	}

	/**
	 * Load associations
	 *
	 * @return void
	 */
	protected function loadAssociations()
	{
		$annotations = ClassReflection::from($this->entity)->getAnnotations();
		if (isset($annotations['OneToOne']) && count($annotations['OneToOne']) > 0) {
			foreach ($annotations['OneToOne'] as $data) {
				$data = (array) $data;
				$assoc = new Associations\OneToOne($this->entity, $data[0],
					isset($data['mapped']) ? $data['mapped'] : NULL,
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['column']) ? $data['column'] : NULL
				);
				$this->oneToOne[$assoc->name] = $this->associations[$assoc->name] = $assoc;
			}
		}
		if (isset($annotations['OneToMany']) && count($annotations['OneToMany']) > 0) {
			foreach ($annotations['OneToMany'] as $data) {
				$data = (array) $data;
				$assoc = new Associations\OneToMany($this->entity, $data[0],
						isset($data['name']) ? $data['name'] : NULL,
						isset($data['column']) ? $data['column'] : NULL
				);
				$this->oneToMany[$assoc->name] = $this->associations[$assoc->name] = $assoc;
			}
		}
		if (isset($annotations['ManyToOne']) && count($annotations['ManyToOne']) > 0) {
			foreach ($annotations['ManyToOne'] as $data) {
				$data = (array) $data;
				$assoc = new Associations\ManyToOne($this->entity, $data[0],
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['column']) ? $data['column'] : NULL
				);
				$this->manyToOne[$assoc->name] = $this->associations[$assoc->name] = $assoc;
			}
		}
		if (isset($annotations['ManyToMany']) && count($annotations['ManyToMany']) > 0) {
			foreach ($annotations['ManyToMany'] as $data) {
				$data = (array) $data;
				$assoc = new Associations\ManyToMany($this->entity, $data[0],
					isset($data['mapped']) ? $data['mapped'] : TRUE,
					isset($data['name']) ? $data['name'] : NULL,
					isset($data['joinTable']) ? $data['joinTable'] : NULL,
					isset($data['joinTargetColumn']) ? $data['joinTargetColumn'] : NULL,
					isset($data['joinSourceColumn']) ? $data['joinSourceColumn'] : NULL
				);
				$this->manyToMany[$assoc->name] = $this->associations[$assoc->name] = $assoc;
			}
		}

		$this->associationsLoaded = TRUE;
	}

	/**
	 * Get associations
	 *
	 * @return array<ActiveMapper\Associations\IAssociation>
	 */
	public function getAssociations()
	{
		if (!$this->associationsLoaded)
			$this->loadAssociations();

		return $this->associations;
	}

	/**
	 * Get one to one associations
	 *
	 * @return array<ActiveMapper\Associations\OneToOne>
	 */
	public function getOneToOne()
	{
		if (!$this->associationsLoaded)
			$this->loadAssociations();

		return $this->oneToOne;
	}

	/**
	 * Get one to many associations
	 *
	 * @return array<ActiveMapper\Associations\OneToMany>
	 */
	public function getOneToMany()
	{
		if (!$this->associationsLoaded)
			$this->loadAssociations();

		return $this->oneToMany;
	}

	/**
	 * Get many to one associations
	 *
	 * @return array<ActiveMapper\Associations\ManyToOne>
	 */
	public function getManyToOne()
	{
		if (!$this->associationsLoaded)
			$this->loadAssociations();

		return $this->manyToOne;
	}

	/**
	 * Get many to many associations
	 *
	 * @return array<ActiveMapper\Associations\ManyToMany>
	 */
	public function getManyToMany()
	{
		if (!$this->associationsLoaded)
			$this->loadAssociations();

		return $this->manyToMany;
	}

	/**
	 * Has entity proxy object
	 *
	 * @return bool
	 */
	public function hasProxy()
	{
		return is_subclass_of($this->entity, 'ActiveMapper\Proxy');
	}

	/**
	 * Has entity proxy object
	 *
	 * @return bool
	 */
	public function getProxy()
	{
		return $this->hasProxy();
	}

	/**
	 * Get columns DataType objects
	 *
	 * @return array<ActiveMapper\DataTypes\IDataType>
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * Has column
	 *
	 * @return bool
	 */
	public function hasColumn($name)
	{
        $this;
        $this->columns;
		return isset($this->columns[$name]);
	}

	/**
	 * Get column DataType object by name
	 *
	 * @param string $name colum name
	 * @return ActiveMapper\DataTypes\IDataType
	 * @throws InvalidArgumentException
	 */
	public function getColumn($name)
	{
		if (!$this->hasColumn($name))
			throw new \InvalidArgumentException("Column '$name' not exist in '{$this->entity}' entity");

		return $this->columns[$name];
	}

	/**
	 * Get primary key name
	 * 
	 * @return string|NULL
	 */
	public function getPrimaryKey()
	{
		return $this->primaryKey;
	}

	/**
	 * Is primary key autoincrement
	 *
	 * @return bool
	 */
	public function isPrimaryKeyAutoincrement()
	{
		return $this->primaryKeyAutoincrement;
	}

	/**
	 * Get primary key autoincrement
	 *
	 * @return bool
	 */
	public function getPrimaryKeyAutoincrement()
	{
		return $this->isPrimaryKeyAutoincrement();
	}

	/**
	 * Get entity table name
	 *
	 * @return string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Get entity name
	 *
	 * @return strin
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get metadata instance
	 *
	 * @param string $entity
	 * @return ActiveMapper\Metadata
	 * @throws InvalidArgumentException
	 * @throws NotImplementedException
	 * @throws ActiveMapper\InvalidDataTypeException
	 */
	public static function getMetadata($entity)
	{
		if (!isset(self::$metadata[$entity]))
			self::$metadata[$entity] = new static($entity);

		return self::$metadata[$entity];
	}

	/**
	 * Get entity instance with default data
	 *
	 * @param ActiveMapper\Manager $em
	 * @param array $data
	 * @return mixed
	 */
	public function getInstance(Manager $em, $data)
	{
		if (!is_array($data) && !($data instanceof \ArrayAccess))
			throw new \InvalidArgumentException("Get instance data must be array or implement ArrayAccess.");

		$ref = ClassReflection::from($this->entity);
		if ($this->hasProxy())
			$instance = $ref->newInstance($data);
		else {
			$instance = $ref->newInstance();
			foreach ($this->columns as $column) {
				$tmpName = Tools::underscore($column->name);
				if (isset($data[$tmpName])) {
					$prop = $ref->getProperty($column->name);
					$prop->setAccessible(TRUE);
					$prop->setValue($instance, $column->convertToPHPValue($data[$tmpName]));
					$prop->setAccessible(FALSE);
				}
			}
		}

		if (count($this->getAssociations()) > 0) {
			$propRef = $ref->getProperty('_associations');
			$propRef->setAccessible(TRUE);
			$associations = $propRef->getValue($instance);
			foreach ($this->getAssociations() as $assoc) {
				$associations[$assoc->name] = new Associations\LazyLoad($em, $this->entity, $assoc->name, $data);
			}
			$propRef->setValue($instance, $associations);
			$propRef->setAccessible(FALSE);
		}

		return $instance;
	}

	/**
	 * Get entity primary key value
	 *
	 * @param mixed $entity
	 * @return mixed
	 */
	public function getPrimaryKeyValue(&$entity)
	{
		$ref = new PropertyReflection($this->entity, $this->primaryKey);
		$ref->setAccessible(TRUE);
		$primaryKey = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		return $primaryKey;
	}

	/**
	 * Set entity primary key value
	 *
	 * @param mixed $entity
	 * @param mixed $primaryKey
	 */
	public function setPrimaryKeyValue(&$entity, $primaryKey)
	{
		$ref = new PropertyReflection($this->entity, $this->primaryKey);
		$ref->setAccessible(TRUE);
		$ref->setValue($entity, $this->columns[$this->primaryKey]->convertToPHPValue($primaryKey));
		$ref->setAccessible(FALSE);
	}

	/**
	 * Get entity values
	 *
	 * @param mixed $entity
	 * @param bool $withPrimaryKey
	 * @return array
	 */
	public function getValues(&$entity, $withPrimaryKey = TRUE)
	{
		$data = array();
		foreach ($this->columns as $column) {
			if ($column->name == $this->primaryKey && !$withPrimaryKey)
				continue;

			$ref = new PropertyReflection($this->entity, $column->name);
			$ref->setAccessible(TRUE);
			$data[$column->name] = $ref->getValue($entity);
			$ref->setAccessible(FALSE);
		}

		$ref = new PropertyReflection($this->entity, '_associations');
		$ref->setAccessible(TRUE);
		$associations = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		foreach (array_merge($this->oneToOne, $this->manyToOne) as $association) {
			if (!array_key_exists($association->name, $associations) ||
					$associations[$association->name] instanceof Associations\LazyLoad ||
					($association instanceof Associations\OneToOne && !empty($association->mapped))) {
				continue;
			}

			if (empty($associations[$association->name]))
				$data[$association->sourceColumn] = NULL;
			else {
				$metadata = Metadata::getMetadata($association->targetEntity);
				$data[$association->sourceColumn] = $metadata->getPrimaryKeyValue($associations[$association->name]);
			}
		}

		return $data;
	}

	/**
	 * Get entity associations values
	 *
	 * @param mixed $entity
	 * @return array
	 */
	public function getAssociationsValues(&$entity)
	{
		$ref = new PropertyReflection($this->entity, '_associations');
		$ref->setAccessible(TRUE);
		$associations = $ref->getValue($entity);
		$ref->setAccessible(FALSE);
		$data = array();
		foreach ($this->associations as $association) {
			if (!array_key_exists($association->name, $associations) || 
					$associations[$association->name] instanceof Associations\LazyLoad) {
				continue;
			}

			if (is_array($associations[$association->name]) || $associations[$association->name] instanceof \ArrayAccess)
				$data[$association->name] = array_keys($associations[$association->name]);
			elseif (empty($associations[$association->name]))
				$data[$association->name] = NULL;
			else {
				$data[$association->name] = Metadata::getMetadata(get_class($associations[$association->name]))
					->getPrimaryKeyValue($associations[$association->name]);
			}
		}

		return $data;
	}
}