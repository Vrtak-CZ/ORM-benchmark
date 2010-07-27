<?php

namespace App\Models;

use Nette\Environment;

/**
 * @property-read int $id
 * @property-read string $name
 * @tableName cities
 */
class City extends \ActiveMapper\Proxy
{
	/**
	 * @var int
	 * @column(Int)
	 * @autoincrement
	 * @primary 
	 */
	protected $id;
	/**
	 * @var string
	 * @column(String, 128)
	 */
	protected $name;

	/**
	 * Find city by id
	 *
	 * @param int $id
	 * @return App\Models\City|NULL
	 */
	public static function find($id)
	{
		return Environment::getService('ActiveMapper\Manager')->find(get_called_class(), $id);
	}

	/**
	 * Find city by name
	 *
	 * @param string $name
	 * @return App\Models\City|NULL
	 */
	public static function findByName($name)
	{
		return Environment::getService('ActiveMapper\Manager')->findByName(get_called_class(), $name);
	}

	/**
	 * Create new city instance
	 *
	 * @param string $name
	 * @return App\Models\City
	 */
	public static function create($name)
	{
		return new static(array('name' => $name));
	}

	/**
	 * Save city changes
	 *
	 * @return App\Models\City
	 */
	public function save()
	{
		$em = Environment::getService('ActiveMapper\Manager');
		$em->persist($this)->flush();
		return $this;
	}

	/**
	 * Delete city
	 */
	public function delete()
	{
		$em = Environment::getService('ActiveMapper\Manager');
		$em->delete($this)->flush();
	}
}