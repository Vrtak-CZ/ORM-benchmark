<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\ICity $city
 * @property string $mail
 */
class People extends \Nette\Object
{
	/** @var int */
	private $id;
	/** @var string */
	private $name;
	/** @var string */
	private $street;
	/** @var App\Models\ICity */
	private $city;
	/** @var string */
	private $mail;

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
}