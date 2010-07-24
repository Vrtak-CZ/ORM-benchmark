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

use ActiveMapper\Metadata;

/**
 * Identity map
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 */
class Map extends \Nette\Object
{
	/** @var ActiveMapper\Manager */
	protected $em;
	/** @var string */
	protected $data = array();

	/**
	 * Construct
	 *
	 * @param ActiveMapper\Manager $em
	 */
	public function __construct(\ActiveMapper\Manager $em)
	{
		$this->em = $em;
	}

	/**
	 * Is entity mapped
	 *
	 * @param string $entity
	 * @param string $name
	 * @param mixed $key
	 * @return bool
	 */
	public function isMapped($entity, $name, $key)
	{
		$association = Metadata::getMetadata($entity)->associations[$name];
		if ($association instanceof OneToOne && empty($association->mapped)) {
			if (!isset($this->data[$association->targetEntity]))
				return FALSE;
			if (!isset($this->data[$association->targetEntity][$entity]))
				return FALSE;

			return isset($this->data[$association->targetEntity][$entity][$key]);
		}

		if (!isset($this->data[$entity]))
			return FALSE;
		if (!isset($this->data[$entity][$association->targetEntity]))
			return FALSE;

		return isset($this->data[$entity][$association->targetEntity][$key]);
	}

	/**
	 * Find entity by primary key
	 * 
	 * @param string $entity
	 * @param string $name
	 * @param mixed $key
	 * @return NULL|mixed
	 */
	public function find($entity, $name, $key)
	{
		if (!$this->isMapped($entity, $name, $key))
			return NULL;

		$association = Metadata::getMetadata($entity)->associations[$name];
		if ($association instanceof OneToOne && empty($association->mapped)) {
			if (!isset($this->data[$association->targetEntity]))
				return NULL;
			if (!isset($this->data[$association->targetEntity][$entity]))
				return NULL;

			return $this->data[$association->targetEntity][$entity][$key];
		}

		if (!isset($this->data[$entity]))
			return NULL;
		if (!isset($this->data[$entity][$association->targetEntity]))
			return NULL;

		return $this->data[$entity][$association->targetEntity][$key];
	}


	/**
	 * Map entity or entities
	 *
	 * @param string $entity
	 * @param string $name
	 * @param mixed $key
	 * @param mixed $targetPK
	 * @throws InvalidArgumentException
	 */
	public function map($entity, $name, $key, $targetPK)
	{
		$association = Metadata::getMetadata($entity)->associations[$name];
		
		if ($association instanceof OneToMany && !is_array($targetPK) && !($targetPK instanceof \ArrayAccess) && $targetPK != NULL)
			throw new \InvalidArgumentException("For association '$name' of '$entity' entity targetKey must be array because is OneToMany");
		if ($association instanceof ManyToMany && !is_array($targetPK) && !($targetPK instanceof \ArrayAccess) && $targetPK != NULL)
			throw new \InvalidArgumentException("For association '$name' of '$entity' entity targetKey must be array because is ManyToMany");

		if ($association instanceof OneToOne && empty($association->mapped)) {
			if (!isset($this->data[$association->targetEntity]))
				$this->data[$association->targetEntity] = array();
			if (!isset($this->data[$association->targetEntity][$entity]))
				$this->data[$association->targetEntity][$entity] = array();

			if (empty($targetPK))
				unset($this->data[$association->targetEntity][$entity][$key]);
			else
				$this->data[$association->targetEntity][$entity][$key] = $targetPK;
		} elseif ($association instanceof OneToMany) {
			if (!isset($this->data[$entity]))
				$this->data[$entity] = array();
			if (!isset($this->data[$entity][$association->targetEntity]))
				$this->data[$entity][$association->targetEntity] = array();

			if (empty($targetPK)) {
				$keys = $this->data[$entity][$association->targetEntity][$key];
				unset($this->data[$entity][$association->targetEntity][$key]);
			} else
				$keys = $this->data[$entity][$association->targetEntity][$key] = $targetPK;
			
			foreach ($keys as $value) {
				if (!isset($this->data[$association->targetEntity]))
					$this->data[$association->targetEntity] = array();
				if (!isset($this->data[$association->targetEntity][$entity]))
					$this->data[$association->targetEntity][$entity] = array();

				if (empty($targetPK))
					unset($this->data[$association->targetEntity][$entity][$value]);
				else
					$this->data[$association->targetEntity][$entity][$value] = $key;
			}
		} elseif ($association instanceof ManyToOne) {
			if (!isset($this->data[$entity]))
				$this->data[$entity] = array();
			if (!isset($this->data[$entity][$association->targetEntity]))
				$this->data[$entity][$association->targetEntity] = array();

			if (empty($targetPK)) {
				if (isset($this->data[$association->targetEntity]) && isset($this->data[$association->targetEntity][$entity])) {
					$value = $this->data[$entity][$association->targetEntity][$key];
					if (isset($this->data[$association->targetEntity][$entity][$value])) {
						$values = &$this->data[$association->targetEntity][$entity][$value];
						$pos = array_search($value, $values);
						if ($pos)
							unset($values[$pos]);
					}
				}
				
				unset($this->data[$entity][$association->targetEntity][$key]);
			}
			else {
				$this->data[$entity][$association->targetEntity][$key] = $targetPK;
				if (isset($this->data[$association->targetEntity]) && isset($this->data[$association->targetEntity][$entity])) {
					$value = $this->data[$entity][$association->targetEntity][$key];
					if (isset($this->data[$association->targetEntity][$entity][$value])) {
						$values = &$this->data[$association->targetEntity][$entity][$value];
						if (!in_array($value, $values))
							$values[] = $value;
					}
				}
			}
		} else {
			if (!isset($this->data[$entity]))
				$this->data[$entity] = array();
			if (!isset($this->data[$entity][$association->targetEntity]))
				$this->data[$entity][$association->targetEntity] = array();

			if (empty($targetPK))
				unset($this->data[$entity][$association->targetEntity][$key]);
			else
				$this->data[$entity][$association->targetEntity][$key] = $targetPK;
		}
	}
}