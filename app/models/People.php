<?php

namespace App\Models;

use Nette\Environment;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\ICity $city
 * @property string $mail
 * @Entity
 * @Table(name="peoples")
 */
class People extends \Nette\Object implements IPeople
{
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 * @var int
	 */
	private $id;
	/**
	 * @Column(type="string", length=128, nullable=false)
	 * @var string
	 */
	private $name;
	/**
	 * @Column(type="string", length=128, nullable=false)
	 * @var string
	 */
	private $street;
	/**
     * @ManyToOne(targetEntity="City")
     * @JoinColumn(name="city_id", referencedColumnName="id")
     */
	private $city;
	/**
	 * @Column(type="string", length=128, nullable=false)
	 * @var string
	 */
	private $mail;

	public function __construct($name = NULL, $street = NULL, ICity $city = NULL, $mail = NULL)
	{
		if (!empty($name))
			$this->name = $name;
		if (!empty($street))
			$this->street = $street;
		if (!empty($city))
			$this->city = $city;
		if (!empty($mail))
			$this->mail = $mail;
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

	/**
	 * Find people by id
	 *
	 * @param int $id
	 * @return App\Models\IPeople|NULL
	 */
	public static function find($id)
	{
		return Environment::getService('Doctrine\ORM\EntityManager')->find(get_called_class(), $id);
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
		return new static($name, $street, $city, $mail);
	}

	/**
	 * Save people changes
	 *
	 * @return App\Models\IPeople
	 */
	public function save()
	{
		$em = Environment::getService('Doctrine\ORM\EntityManager');
		$em->persist($this);
		$em->flush();
		return $this;
	}

	/**
	 * Delete people
	 */
	public function delete()
	{
		$em = Environment::getService('Doctrine\ORM\EntityManager');
		$em->remove($this);
		$em->flush();
	}
}