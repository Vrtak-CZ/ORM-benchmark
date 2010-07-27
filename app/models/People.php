<?php

namespace App\Models;

use Nette\Environment;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\City $city
 * @property string $mail
 * @tableName peoples
 * @ManyToOne(App\Models\City)
 */
class People extends \ActiveMapper\Proxy
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
	 * @var string
	 * @column(String, 128)
	 */
	protected $street;
	/**
	 * @var string
	 * @column(String, 128)
	 */
	protected $mail;

	/**
	 * Find people by id
	 *
	 * @param int $id
	 * @return App\Models\People|NULL
	 */
	public static function find($id)
	{
		return Environment::getService('ActiveMapper\Manager')->find(get_called_class(), $id);
	}

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
		$data = new static(array('name' => $name, 'street' => $street, 'mail' => $mail));
		$data->city = $city;
		return $data;
	}

	/**
	 * Save people changes
	 *
	 * @return App\Models\People
	 */
	public function save()
	{
		$em = Environment::getService('ActiveMapper\Manager');
		$em->persist($this);
		return $this;
	}

	/**
	 * Delete people
	 */
	public function delete()
	{
		$em = Environment::getService('ActiveMapper\Manager');
		$em->delete($this);
	}
}