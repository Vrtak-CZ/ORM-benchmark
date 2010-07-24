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
 * Float column class
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 *
 * @property-read string $name
 * @property-read bool $allowNull
 */
class Float extends Base implements IDataType
{
	/**
	 * Is valid value
	 *
	 * @param int|string|float $value
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
		elseif ((bool) preg_match('/^-?[0-9]*[.,]?[0-9]+$/', $value))
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Convert to native PHP value
	 *
	 * @param int|string|float $value
	 * @return float
	 * @throws InvalidArgumentException
	 */
	public function convertToPHPValue($value)
	{
		if ($value === NULL && !$this->allowNull)
			throw new \InvalidArgumentException("Null is not allowed value for '{$this->name}'");
		elseif ($value !== NULL && !$this->isValid($value))
			throw new \InvalidArgumentException("Only float value accepted for '{$this->name}' [$value]");

		if ($value === NULL)
			return NULL;
		else
			return (float) $value;
	}
}