<?php

namespace App\Models;

use dibi;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\ICity $city
 * @property string $mail
 */
class People extends \Nette\Object implements IPeople
{
	/** @var int */
	private $id = NULL;
	/** @var string */
	private $name;
	/** @var string */
	private $street;
	/** @var App\Models\ICity */
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
	 * @return App\Models\IPeople
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
	 * @return App\Models\IPeople
	 */
	public function setStreet($street)
	{
		$this->street = $street;
		return $this;
	}

	/**
	 * Get people city
	 *
	 * @return App\Models\ICity
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
	 * @param App\Models\ICity $city
	 * @return App\Models\IPeople
	 */
	public function setCity(ICity $city)
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
	 * @return App\Models\IPeople
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
	 * @return App\Models\IPeople|NULL
	 */
	public static function find($id)
	{
		global $notORM;
		$data = $notORM->peoples("id", $id)->fetch();
		if ($data === FALSE)
			return NULL;
		return new static($data);
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
		return new static(array('name' => $name, 'street' => $street, 'city' => $city, 'mail' => $mail));
	}

	/**
	 * Save people changes
	 *
	 * @return App\Models\IPeople
	 */
	public function save()
	{
		global $notORM;
		if (!isset($this->id)) {
			$this->id = $notORM->peoples(array('name' => $this->name, 'street' => $this->street, 'city_id' => $this->city->id, 'mail' => $this->mail));
		} else {
			$notORM->peoples("id", $this->id)->update(array('name' => $this->name, 'street' => $this->street, 'city_id' => $this->city->id, 'mail' => $this->mail));
		}

		return $this;
	}

	/**
	 * Delete people
	 */
	public function delete()
	{
		global $notORM;
		if (isset($this->id)) {
			$notORM->peoples("id", $this->id)->delete();
			$this->id = NULL;
		}
	}
}