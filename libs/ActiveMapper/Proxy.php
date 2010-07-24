<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper
 */

namespace ActiveMapper;

use Nette\String;

/**
 * Data proxy entity
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
abstract class Proxy extends \Nette\Object
{
	/** @var array */
	protected $_associations = array();

	/**
	 * Contstuctor
	 *
	 * @param array|ArrayAccess $data
	 */
	public function __construct($data = NULL)
	{
		if ($data instanceof \ArrayAccess)
			$data = (array) $data;
		elseif (!is_array($data) && $data != NULL)
			throw new \InvalidArgumentException("Data for entity must be array accesible.");


		$metadata = Metadata::getMetadata(get_called_class());

		if (count($data) > 0) {
			foreach ($metadata->columns as $column) {
				$name = Tools::underscore($column->name);
				if (array_key_exists($name, $data))
					$this->{$column->name} = $column->convertToPHPValue($data[$name]);
			}
		}
	}

	/**
	 * Getter
	 *
	 * @param string $name
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function &__get($name)
	{
		try {
			return $this->universalGetValue($name);
		} catch (\MemberAccessException $e) {
			return parent::__get($name);
		}
	}

	/**
	 * Universal value getter
	 *
	 * @param string $name
	 * @return mixed
	 * @throws MemberAccessException
	 */
	private function &universalGetValue($name)
	{
		if (Metadata::getMetadata(get_called_class())->hasColumn($name)) {
			return $this->$name;
		} elseif (array_key_exists($name, $this->_associations)) {
			if ($this->_associations[$name] instanceof Associations\LazyLoad)
				$this->_associations[$name] = $this->_associations[$name]->getData();

			return $this->_associations[$name];
		} else
			throw new \MemberAccessException("Cannot read to undeclared column ".get_called_class()."::\$$name.");
	}

	/**
	 * Setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __set($name, $value)
	{
		try {
			return $this->universalSetValue($name, $value);
		} catch (\MemberAccessException $e) {
			return parent::__set($name, $value);
		}
	}

	/**
	 * Universal value setter
	 *
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 * @throws MemberAccessException
	 */
	private function universalSetValue($name, $value)
	{
		$metadata = Metadata::getMetadata(get_called_class());
        $xxx = $metadata->hasColumn($name);
		if ($metadata->hasColumn($name)) {
			if ($metadata->primaryKey == $name)
				throw new \MemberAccessException("Primary key is read-only ".get_called_class()."::\$$name.");
			else
				return $this->$name = Metadata::getMetadata(get_called_class())->getColumn($name)->convertToPHPValue($value);
		} elseif (array_key_exists($name, $this->_associations) || (array_key_exists($name, $metadata->associations) && 
				($metadata->associations[$name] instanceof Associations\ManyToOne || 
				$metadata->associations[$name] instanceof Associations\OneToOne))) {
			if (array_key_exists($name, $this->_associations) && $this->_associations[$name] instanceof Associations\LazyLoad)
				$this->_associations[$name] = $this->_associations[$name]->getData();

			return $this->_associations[$name] = $value;
		} else
			throw new \MemberAccessException("Cannot assign undeclared column ".get_called_class()."::\$$name.");
	}

	/**
	 * Method overload for universal getter/setter
	 * 
	 * @param string $name associtation name
	 * @param array $attr
	 * @return mixed
	 * @throws MemberAccessException
	 */
	public function __call($name, $attr)
	{
		try {
			$metadata = Metadata::getMetadata(get_called_class());
			if (strncmp($name, 'get', 3) === 0 && $metadata->hasColumn(lcfirst(substr($name, 3))))
				return $this->universalGetValue(lcfirst(substr($name, 3)));
			elseif (strncmp($name, 'set', 3) === 0 && $metadata->hasColumn(lcfirst(substr($name, 3))))
				return $this->universalSetValue(lcfirst(substr($name, 3)), isset($attr[0]) ? $attr[0] : NULL);
			else
				return parent::__call($name, $attr);
		} catch (\MemberAccessException $e) {
			return parent::__call($name, $attr);
		}
	}
}