<?php

namespace App\Models;

interface ICity
{
	/**
	 * Get city id
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Get city name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Find city by id
	 *
	 * @param int $id
	 * @return App\Models\ICity|NULL
	 */
	public static function find($id);

	/**
	 * Find city by name
	 *
	 * @param string $name
	 * @return App\Models\ICity|NULL
	 */
	public static function findByName($name);

	/**
	 * Create new city instance
	 *
	 * @param string $name
	 * @return App\Models\ICity
	 */
	public static function create($name);

	/**
	 * Save city changes
	 *
	 * @return App\Models\ICity
	 */
	public function save();

	/**
	 * Delete city
	 */
	public function delete();
}