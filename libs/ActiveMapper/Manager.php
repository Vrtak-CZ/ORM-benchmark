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
 * Entity manager
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 *
 * @property-read DibiConnection $connection
 * @property-read ActiveMapper\UnitOfWork $unitOfWork
 * @property-read ActiveMapper\Associations\Map $associationsMap
 */
class Manager extends \Nette\Object
{
	/** @var DibiConnection */
	private $connection;
	/** @var array<ActiveMapper\IRepository> */
	private $repositories = array();
	/** @var array<ActiveMapper\IPersister> */
	private $persisters = array();
	/** @var array<ActiveMapper\IdentityMap> */
	private $identityMap = array();
	/** @var ActiveMapper\UnitOfWork */
	private $unitOfWork;
	/** @var ActiveMapper\Associations\Map */
	private $associationsMap;

	public function __construct(DibiConnection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * Get connection
	 *
	 * @return DibiConnection
	 */
	final public function getConnection()
	{
		return $this->connection;
	}

	/**
	 * Get repository
	 * 
	 * @param string $entity
	 * @return ActiveMapper\IRepository
	 */
	public function getRepository($entity)
	{
		if (!isset($this->repositories[$entity]))
			$this->repositories[$entity] = new DibiRepository($this, $entity);

		return $this->repositories[$entity];
	}

	/**
	 * Set repository
	 * 
	 * @param string $entity
	 * @param ActiveMapper\IRepository $repository
	 * @return ActiveMapper\Manager
	 */
	public function setRepository($entity, IRepository &$repository)
	{
		$this->repositories[$entity] = &$repository;

		return $this;
	}

	/**
	 * Get persister
	 *
	 * @param string $entity
	 * @return ActiveMapper\IPersister
	 */
	public function getPersister($entity)
	{
		if (!isset($this->persisters[$entity]))
			$this->persisters[$entity] = new DibiPersister($this, $entity);

		return $this->persisters[$entity];
	}

	/**
	 * Set persister
	 *
	 * @param string $entity
	 * @param ActiveMapper\IPersister $persister
	 * @return ActiveMapper\Manager
	 */
	public function setPersister($entity, IPersister &$persister)
	{
		$this->persisters[$entity] = &$persister;

		return $this;
	}

	/**
	 * Get identity map
	 *
	 * @param string $entity
	 * @return ActiveMapper\IdentityMap
	 */
	public function getIdentityMap($entity)
	{
		if (!isset($this->identityMap[$entity]))
			$this->identityMap[$entity] = new IdentityMap($this, $entity);

		return $this->identityMap[$entity];
	}

	/**
	 * Get unit of work
	 *
	 * @return ActiveMapper\UnitOfWork
	 */
	public function getUnitOfWork()
	{
		if (!isset($this->unitOfWork))
			$this->unitOfWork = UnitOfWork::getUnitOfWork($this);

		return $this->unitOfWork;
	}

	/**
	 * Get associations map
	 *
	 * @return ActiveMapper\Associations\Map
	 */
	public function getAssociationsMap()
	{
		if (!isset($this->associationsMap))
			$this->associationsMap = new Associations\Map($this);

		return $this->associationsMap;
	}

	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param string $entity
	 * @param mixed $primaryKey
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function find($entity, $primaryKey)
	{
		return $this->getRepository($entity)->find($primaryKey);
	}

	/**
	 * Find all entity
	 *
	 * @param string $entity
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidArgumentException
	 */
	public function findAll($entity)
	{
		// TODO: use FluentCollection

		return $this->getRepository($entity)->findAll();
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
			$entity = $args[0];
			unset($args[0]);
			return callback($this->getRepository($entity), $name)->invokeArgs($args);
		} else
			return parent::__call($name, $args);
	}

	/**
	 * Persist entity
	 *
	 * @param mixed $entity
	 * @return ActiveMapper\Manager
	 */
	public function persist(&$entity)
	{
		$this->getUnitOfWork()->registerSave($entity);

		return $this;
	}

	/**
	 * Delete entity
	 *
	 * @param mixed $entity
	 * @return ActiveMapper\Manager
	 */
	public function delete(&$entity)
	{
		$this->getUnitOfWork()->registerDelete($entity);

		return $this;
	}

	/**
	 * Flush all changes
	 *
	 * @return ActiveMapper\Manager
	 */
	public function flush()
	{
		if ($this->getUnitOfWork()->count >= 1)
			$this->getUnitOfWork()->commit();

		return $this;
	}

	/**
	 * Entity manager factory
	 *
	 * @param DibiConnection $connection
	 * @return ActiveMapper\Manager
	 */
	public static function getManager(DibiConnection $connection = NULL)
	{
		if ($connection == NULL)
			$connection = \dibi::getConnection();

		return new static($connection);
	}
}