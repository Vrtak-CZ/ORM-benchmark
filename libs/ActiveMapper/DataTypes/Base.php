<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\DataTypes
 */

namespace ActiveMapper\DataTypes;

/**
 * Base column class
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 *
 * @property-read string $name
 * @property-read bool $allowNull
 */
abstract class Base extends \Nette\Object
{
	/** @var string */
	private $name;
	/** @var bool */
	private $allowNull;

	/**
	 * Constructor
	 *
	 * @param string $name
	 * @param bool $allowNull
	 */
	public function __construct($name, $allowNull = FALSE)
	{
		$this->name = $name;
		$this->allowNull = $allowNull;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get allow null
	 *
	 * @return bool
	 */
	public function getAllowNull()
	{
		return $this->allowNull;
	}

	/**
	 * Is null allowed
	 *
	 * @return bool
	 */
	public function isNullAllowed()
	{
		return $this->getAllowNull();
	}
}