<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property-read string $name
 */
class City extends \Nette\Object implements ICity
{
	/** @var int */
	private $id = NULL;
	/** @var string */
	private $name;

	public function  __construct($data)
	{
		if (isset($data['id']))
			$this->id = $data['id'];
		$this->name = $data['name'];
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
	 * @return NotORM_Row|NULL
	 */
	public static function find($id)
	{
		global $notORM;
		$data = $notORM->cities("id", $id)->fetch();
		if ($data === FALSE)
			return NULL;
		return new static($data);
	}

	/**
	 * Find city by name
	 *
	 * @param string $name
	 * @return NotORM_Row|NULL
	 */
	public static function findByName($name)
	{
		global $notORM;
		$data = $notORM->cities("name", $name)->fetch();
		if ($data === FALSE)
			return NULL;
		return new static($data);
	}

	/**
	 * Create new city instance
	 *
	 * @param string $name
	 * @return App\Models\ICity
	 */
	public static function create($name)
	{
		return new static(array('name' => $name));
	}

	/**
	 * Save city changes
	 *
	 * @return App\Models\ICity
	 */
	public function save()
	{
		global $notORM;
		if (!isset($this->id)) {
			$this->id = $notORM->cities(array('name' => $this->name));
		}
		return $this;
	}

	/**
	 * Delete city
	 */
	public function delete()
	{
		global $notORM;
		if (isset($this->id)) {
			$notORM->cities("id", $this->id)->delete();
			$this->id = NULL;
		}
	}
}