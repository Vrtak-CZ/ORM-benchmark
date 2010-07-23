<?php

namespace App\Models;

interface IPeople
{
	/**
	 * Get people id
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Set people name
	 *
	 * @param string $name
	 * @return App\Models\IPeople
	 */
	public function setName($name);

	/**
	 * Get people street
	 *
	 * @return string
	 */
	public function getStreet();

	/**
	 * Set people street
	 *
	 * @param string $street
	 * @return App\Models\IPeople
	 */
	public function setStreet($street);

	/**
	 * Get people city
	 *
	 * @return App\Models\ICity
	 */
	public function getCity();

	/**
	 * Set people city
	 *
	 * @param App\Models\ICity $city
	 * @return App\Models\IPeople
	 */
	public function setCity(ICity $city);

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getMail();

	/**
	 * Set people mail
	 *
	 * @param string $mail
	 * @return App\Models\IPeople
	 */
	public function setMail($mail);

	/**
	 * Find people by id
	 *
	 * @param int $id
	 * @return App\Models\IPeople|NULL
	 */
	public static function find($id);

	/**
	 * Create new people instance
	 *
	 * @param string $name
	 * @param string $street
	 * @param App\Models\ICity
	 * @param string $mail
	 * @return App\Models\IPeople
	 */
	public static function create($name, $street, ICity $city, $mail);

	/**
	 * Save people changes
	 *
	 * @return App\Models\IPeople
	 */
	public function save();

	/**
	 * Delete people
	 */
	public function delete();
}