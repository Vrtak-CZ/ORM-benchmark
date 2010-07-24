<?php

namespace App\Models;

use Nette\Environment;

/**
 * @property-read int $id
 * @property-read string $name
 * @Entity
 * @Table(name="cities")
 */
class City extends \Nette\Object implements ICity
{
	/**
	 * @Id @Column(type="integer")
	 * @GeneratedValue
	 * @var int
	 */
	private $id;
	/**
	 * @Column(type="string", length=128, unique=true)
	 * @var string
	 */
	private $name;

	public function __construct($name = NULL)
	{
		if (!empty($name))
			$this->name;
	}

	/**
	 * Get city id
	 *
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Get name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Find city by id
	 *
	 * @param int $id
	 * @return App\Models\ICity|NULL
	 */
	public static function find($id)
	{
		return Environment::getService('Doctrine\ORM\EntityManager')->find(get_called_class(), $id);
	}

	/**
	 * Find city by name
	 *
	 * @param string $name
	 * @return App\Models\ICity|NULL
	 */
	public static function findByName($name)
	{
		return Environment::getService('Doctrine\ORM\EntityManager')
			->getRepository(get_called_class())->findOneBy(array('name' => $name));
	}

	/**
	 * Create new city instance
	 *
	 * @param string $name
	 * @return App\Models\ICity
	 */
	public static function create($name)
	{
		return new static($name);
	}

	/**
	 * Save city changes
	 *
	 * @return App\Models\ICity
	 */
	public function save()
	{
		$em = Environment::getService('Doctrine\ORM\EntityManager');
		$em->persist($this);
		$em->flush();
		return $this;
	}

	/**
	 * Delete city
	 */
	public function delete()
	{
		$em = Environment::getService('Doctrine\ORM\EntityManager');
		$em->remove($this);
		$em->flush();
	}
}