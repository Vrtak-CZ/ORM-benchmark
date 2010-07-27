<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\City $city
 * @property string $mail
 * @table peoples
 * @hasOne(name = city, referencedEntity = App\Models\City, column = city_id)
 */
class People extends \Ormion\Record
{
	/**
	 * Create new people instance
	 *
	 * @param string $name
	 * @param string $street
	 * @param App\Models\City
	 * @param string $mail
	 * @return App\Models\People
	 */
	public static function create($name, $street, City $city, $mail)
	{
		return parent::createX(array('name' => $name, 'street' => $street, 'city' => $city, 'mail' => $mail));
	}
}