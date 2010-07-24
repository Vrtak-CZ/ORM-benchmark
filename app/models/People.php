<?php

namespace App\Models;

use Nette\Environment;

/**
 * @property-read int $id
 * @property string $name
 * @property string $street
 * @property App\Models\ICity $city
 * @property string $mail
 * @tableName peoples
 * @ManyToOne(App\Models\City)
 */
class People extends \ActiveMapper\Proxy implements IPeople
{
	/**
	 * @var int
	 * @column(Int)
	 * @autoincrement
	 * @primary
	 */
	protected $id;
	/**
	 * @var string
	 * @column(String, 128)
	 */
	protected $name;
	/**
	 * @var string
	 * @column(String, 128)
	 */
	protected $street;
	/**
	 * @var string
	 * @column(String, 128)
	 */
	protected $mail;

	/**
	 * Get people id
	 *
	 * @return int
	 */
	public function getId()
	{
		return parent::getId();
	}

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getName()
	{
		return parent::getName();
	}

	/**
	 * Set people name
	 *
	 * @param string $name
	 * @return App\Models\IPeople
	 */
	public function setName($name)
	{
		parent::__set('name', $name);
		return $this;
	}

	/**
	 * Get people street
	 *
	 * @return string
	 */
	public function getStreet()
	{
		return parent::__get('street');
	}

	/**
	 * Set people street
	 *
	 * @param string $street
	 * @return App\Models\IPeople
	 */
	public function setStreet($street)
	{
		parent::__set('street', $street);
		return $this;
	}

	/**
	 * Get people city
	 *
	 * @return App\Models\ICity
	 */
	public function getCity()
	{
		return parent::__get('city');
	}

	/**
	 * Set people city
	 *
	 * @param App\Models\ICity $city
	 * @return App\Models\IPeople
	 */
	public function setCity(ICity $city)
	{
        /*if ($city->id == NULL)
            $city->save();*/
		parent::__set('city', $city);
		return $this;
	}

	/**
	 * Get people name
	 *
	 * @return string
	 */
	public function getMail()
	{
		return parent::__get('mail');;
	}

	/**
	 * Set people mail
	 *
	 * @param string $mail
	 * @return App\Models\IPeople
	 */
	public function setMail($mail)
	{
		parent::__set('mail', $mail);
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
		return Environment::getService('ActiveMapper\Manager')->find(get_called_class(), $id);
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
		$data = new static(array('name' => $name, 'street' => $street, 'mail' => $mail));
		$data->city = $city;
		return $data;
	}

	/**
	 * Save people changes
	 *
	 * @return App\Models\IPeople
	 */
	public function save()
	{
		$em = Environment::getService('ActiveMapper\Manager');
		$em->persist($this)->flush();
		return $this;
	}

	/**
	 * Delete people
	 */
	public function delete()
	{
		$em = Environment::getService('ActiveMapper\Manager');
		$em->delete($this)->flush();
	}
}