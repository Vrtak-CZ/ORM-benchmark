<?php

namespace App\Models;

use dibi;

/**
 * @property-read int $id
 * @property-read string $name
 */
class City extends \Nette\Object
{
	const TABLE_NAME = "Cities";

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
	 * @return App\Models\City|NULL
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
	 * Find city by name
	 *
	 * @param string $name
	 * @return App\Models\City|NULL
	 */
	public static function findByName($name)
	{
		$data = dibi::select('*')->from(static::TABLE_NAME)->where("[name] = %s", $name)
			->execute()->setRowClass(get_called_class())->fetch();
		if ($data === FALSE)
			return NULL;
		return $data;
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
		if (!isset($this->id)) {
			dibi::insert(static::TABLE_NAME, array('name' => $this->name))->execute();
			$this->id = dibi::insertId();
		}
		return $this;
	}

	/**
	 * Delete city
	 */
	public function delete()
	{
		if (isset($this->id)) {
			dibi::delete(static::TABLE_NAME)->where("[id] = %i", $this->id)->execute();
			$this->id = NULL;
		}
	}
}