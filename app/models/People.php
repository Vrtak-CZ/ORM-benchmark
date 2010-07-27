<?php

namespace App\Models;

use dibi;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\City $city
 * @property string $mail
 */
class People extends \Nette\Object
{
	const TABLE_NAME = "Peoples";

	/** @var int */
	private $id = NULL;
	/** @var string */
	private $name;
	/** @var string */
	private $street;
	/** @var App\Models\City */
	private $city = NULL;
	/** @var string */
	private $mail;
	/** @var int */
	private $cityId;

	public function  __construct($data)
	{
		if (isset($data['id']))
			$this->id = $data['id'];
		$this->name = $data['name'];
		$this->street = $data['street'];
		if (isset($data['city']))
			$this->city = $data['city'];
		if (isset($data['city_id']))
			$this->cityId = $data['city_id'];
		$this->mail = $data['mail'];
	}

	/**
	 * Get people id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set people name
	 *
	 * @param string $name
	 * @return App\Models\People
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Get people street
	 *
	 * @return string
	 */
	public function getStreet()
	{
		return $this->street;
	}

	/**
	 * Set people street
	 *
	 * @param string $street
	 * @return App\Models\People
	 */
	public function setStreet($street)
	{
		$this->street = $street;
		return $this;
	}

	/**
	 * Get people city
	 *
	 * @return App\Models\City
	 */
	public function getCity()
	{
		if (!isset($this->city))
			$this->city = City::find($this->cityId);
		return $this->city;
	}

	/**
	 * Set people city
	 *
	 * @param App\Models\City $city
	 * @return App\Models\People
	 */
	public function setCity(City $city)
	{
		if ($city->id == NULL)
			$city->save();
		$this->city = $city;
		return $this;
	}

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getMail()
	{
		return $this->mail;
	}

	/**
	 * Set people mail
	 *
	 * @param string $mail
	 * @return App\Models\People
	 */
	public function setMail($mail)
	{
		$this->mail = $mail;
		return $this;
	}
	
	/**
	 * Find people by id
	 *
	 * @param int $id
	 * @return App\Models\People|NULL
	 */
	public static function find($id)
	{
		$data = dibi::select('*')->from(static::TABLE_NAME)->where("[id] = %i", $id)
			->execute()->setRowClass(get_called_class())->fetch();
		if ($data === FALSE)
			return NULL;
		return $data;
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
		return new static(array('name' => $name, 'street' => $street, 'city' => $city, 'mail' => $mail));
	}

	/**
	 * Save people changes
	 *
	 * @return App\Models\People
	 */
	public function save()
	{
		if (!isset($this->id)) {
			dibi::insert(static::TABLE_NAME, array('name' => $this->name, 'street' => $this->street,
				'city_id' => $this->city->id, 'mail' => $this->mail))->execute();
			$this->id = dibi::insertId();
		} else {
			dibi::update(static::TABLE_NAME, array('name' => $this->name, 'street' => $this->street,
				'city_id' => $this->city->id, 'mail' => $this->mail))->where("[id] = %i", $this->id)->execute();
		}

		return $this;
	}

	/**
	 * Delete people
	 */
	public function delete()
	{
		if (isset($this->id)) {
			dibi::delete(static::TABLE_NAME)->where("[id] = %i", $this->id)->execute();
			$this->id = NULL;
		}
	}
}