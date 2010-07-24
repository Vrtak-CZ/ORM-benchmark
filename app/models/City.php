<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property-read string $name
 * @table cities
 */
class City extends \Ormion\Record implements ICity
{
	/**
	 * Get city id
	 *
	 * @return int
	 */
	public function getId()
	{
		return parent::getId();
	}

	/**
	 * Get city name
	 *
	 * @return string
	 */
	public function getName()
	{
		return parent::getName();
	}

	/**
	 * Find city by name
	 *
	 * @param string $name
	 * @return App\Models\ICity|NULL
	 */
	public static function findByName($name)
	{
		return parent::findByName($name);
	}

	/**
	 * Create new city instance
	 *
	 * @param string $name
	 * @return App\Models\ICity
	 */
	public static function create($name)
	{
		return parent::createX(array('name' => $name));
	}
}