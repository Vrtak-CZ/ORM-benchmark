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
 * String column class
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\DataTypes
 *
 * @property-read string $name
 * @property-read bool $allowNull
 * @property-read int $length
 */
class String extends Base implements IDataType
{
	/** @var int */
	protected $length;

	/**
	 * Construct
	 *
	 * @param string $name
	 * @param bool $null
	 * @param int $length
	 */
	public function __construct($name, $null = FALSE, $length = 255)
	{
		$this->length = $length;
		parent::__construct($name, $null);
	}

	/**
	 * Get length
	 *
	 * @return int
	 */
	public function getLength()
	{
		return $this->length;
	}

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
		elseif (iconv_strlen($value, 'UTF-8') <= $this->length)
			return TRUE;
		else
			return FALSE;
	}

	/**
	 * Convert data to native PHP value
	 *
	 * @param string $value
	 * @return string
	 * @throws InvalidArgumentException
	 */
	public function convertToPHPValue($value)
	{
		if ($value === NULL && !$this->allowNull)
			throw new \InvalidArgumentException("Null is not allowed value for '{$this->name}'");
		elseif ($value !== NULL && !$this->isValid($value))
			throw new \InvalidArgumentException("Only {$this->length} chars accepted for '{$this->name}' [$value]");

		if ($value === NULL)
			return NULL;
		else
			return (string) $value;
	}
}