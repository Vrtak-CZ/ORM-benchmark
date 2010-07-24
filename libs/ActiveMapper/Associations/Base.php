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
	Nette\Reflection\ClassReflection;

/**
 * Base entity association
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 * @property-read string $sourceEntity source entity class
 * @property-read string $targetEntity target entity class
 * @property-read string $name
 */
abstract class Base extends \Nette\Object
{
	/** @var string */
	private $sourceEntity;
	/** @var string */
	private $targetEntity;

	/**
	 * Costructor
	 *
	 * @param string $sourceEntity valid source entity class
	 * @param string $targetEntity valid target entity class
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity)
	{
		// TODO: verify entity class

		$this->sourceEntity = $sourceEntity;
		$this->targetEntity = $targetEntity;
	}

	/**
	 * Get source entity class
	 *
	 * @return string
	 */
	final public function getSourceEntity()
	{
		return $this->sourceEntity;
	}

	/**
	 * Get target entity class
	 *
	 * @return string
	 */
	final public function getTargetEntity()
	{
		return $this->targetEntity;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	final public function getName()
	{
		return $this->name;
	}
}