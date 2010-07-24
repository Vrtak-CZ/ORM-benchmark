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
 * Text column class
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 *
 * @property-read string $name
 * @property-read bool $allowNull
 */
class Text extends Base implements IDataType
{
	/**
	 * Is valid value
	 *
	 * @param int|float|string $value
	 * @return bool
	 */
	public function isValid($value)
	{
		if ($value === NULL && !$this->allowNull)
			return FALSE;
		elseif ($value === NULL)
			return TRUE;

		if (is_bool($value))
			return FALSE;
		else
			return TRUE;
	}

	/**
	 * Convert data to native PHP value
	 *
	 * @param string $value
	 * @return bool
	 */
	public function convertToPHPValue($value)
	{
		if ($value === NULL && !$this->allowNull)
			throw new \InvalidArgumentException("Null is not allowed value for '{$this->name}'");
		elseif ($value !== NULL && !$this->isValid($value))
			throw new \InvalidArgumentException("Only string or int or float accepted for '{$this->name}' [$value]");

		if ($value === NULL)
			return NULL;
		else
			return (string) $value;
	}
}