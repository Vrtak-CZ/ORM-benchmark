<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\ICity $city
 * @property string $mail
 * @table peoples
 * @hasOne(name = city, referencedEntity = App\Models\City, column = city_id)
 */
class People extends \Ormion\Record implements IPeople
{
	/**
	 * Get people id
	 *
	 * @return int
	 */
	public function getId()
	{
		return parent::getId();
	}

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getName()
	{
		return parent::getName();
	}
	
	/**
	 * Set people name
	 *
	 * @param string $name
	 * @return App\Models\IPeople
	 */
	public function setName($name)
	{
		parent::__set('name', $name);
		return $this;
	}

	/**
	 * Get people street
	 *
	 * @return string
	 */
	public function getStreet()
	{
		return parent::getStreet();
	}

	/**
	 * Set people street
	 *
	 * @param string $street
	 * @return App\Models\IPeople
	 */
	public function setStreet($street)
	{
		parent::__set('street', $street);
		return $this;
	}

	/**
	 * Get people city
	 *
	 * @return App\Models\ICity
	 */
	public function getCity()
	{
		return parent::getCity();
	}

	/**
	 * Set people city
	 *
	 * @param App\Models\ICity $city
	 * @return App\Models\IPeople
	 */
	public function setCity(ICity $city)
	{
		parent::__set('city', $city);
		return $this;
	}

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getMail()
	{
		return parent::getMail();
	}

	/**
	 * Set people mail
	 *
	 * @param string $mail
	 * @return App\Models\IPeople
	 */
	public function setMail($mail)
	{
		parent::__set('mail', $mail);
		return $this;
	}

	/**
	 * Create new people instance
	 *
	 * @param string $name
	 * @param string $street
	 * @param App\Models\ICity
	 * @param string $mail
	 * @return App\Models\IPeople
	 */
	public static function create($name, $street, ICity $city, $mail)
	{
		return parent::createX(array('name' => $name, 'street' => $street, 'city' => $city, 'mail' => $mail));
	}
}