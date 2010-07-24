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
 * IDataType interface for data type column
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 */
interface IDataType
{
	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get allow null
	 *
	 * @return bool
	 */
	public function getAllowNull();

	/**
	 * Is valid value
	 *
	 * @param mixed $value
	 * @return bool
	 */
	public function isValid($value);

	/**
	 * Convert to native PHP value
	 *
	 * @param mixed $value
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function convertToPHPValue($value);
}