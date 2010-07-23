<?php

namespace App\Models;

/**
 * @property-read int $id
 * @property-read string $name
 */
class City extends \Nette\Object implements ICity
{
	/** @var int */
	private $id;
	/** @var string */
	private $name;

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
}