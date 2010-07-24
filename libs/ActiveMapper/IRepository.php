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

/**
 * Repository interface
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
interface IRepository
{
	/**
	 * Find entity witch id (primary key) is ...
	 *
	 * @param mixed $primaryKey
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function find($primaryKey);

	/**
	 * Find all entity
	 *
	 * @return ActiveMapper\RepositoryCollection
	 * @throws InvalidArgumentException
	 */
	public function findAll();

	/**
	 * Method overload for findBy...
	 *
	 * @param string $name
	 * @param array $args
	 * @return mixed
	 * @throws InvalidArgumentException
	 */
	public function __call($name, $args);
}