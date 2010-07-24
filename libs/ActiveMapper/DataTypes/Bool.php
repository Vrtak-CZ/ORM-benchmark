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
 * Boolean column class
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 *
 * @property-read string $name
 * @property-read bool $allowNull
 */
class Bool extends Base implements IDataType
{
	/**
	 * Is valid value
	 *
	 * @param int|string|bool $value
	 * @return bool
	 */
	public function isValid($value)
	{
		if ($value === NULL && !$this->allowNull)
			return FALSE;
		elseif ($value === NULL)
			return TRUE;

		if (is_bool($value) || $value === 1 || $value === 0)
			return TRUE;
		elseif (is_string($value) && ($value == "1" || $value == "0"))
			return TRUE;
		elseif (is_string($value) && (strtolower($value) == "y" || strtolower($value) == "n" || strtolower($value) == "false"
				|| strtolower($value) == "true"))
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Convert to native PHP value
	 *
	 * @param int|string|bool $value
	 * @return bool
	 * @throws InvalidArgumentException
	 */
	public function convertToPHPValue($value)
	{
		if ($value === NULL && !$this->allowNull)
			throw new \InvalidArgumentException("Null is not allowed value for '{$this->name}'");
		elseif ($value !== NULL && !$this->isValid($value)) {
			throw new \InvalidArgumentException(
				"Only boolean or 0/1 or 'y'/'n' or 'true'/'false' value accepted for '{$this->name}' [$value]"
			);
		}

		if ($value === TRUE || $value == 1 || strtolower($value) == 'y' || strtolower($value) == 'true')
			return TRUE;
		elseif ($value === NULL)
			return NULL;
		else
			return FALSE;
	}
}