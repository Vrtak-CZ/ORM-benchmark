<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property-read string $name
 * @table cities
 */
class City extends \Ormion\Record
{
	/**
	 * Create new city instance
	 *
	 * @param string $name
	 * @return App\Models\City
	 */
	public static function create($name)
	{
		return parent::createX(array('name' => $name));
	}
}