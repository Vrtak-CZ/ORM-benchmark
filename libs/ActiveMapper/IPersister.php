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
 * Persister interface
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
interface IPersister
{
	/**
	 * Get last generated primary key (autoincrement)
	 *
	 * @param string $sequence
	 * @return mixed
	 */
	public function lastPrimaryKey($sequence = NULL);

	/**
	 * Insert data
	 *
	 * @param mixed $entity
	 * @return mixed $id
	 */
	public function insert($entity);

	/**
	 * Update data
	 * 
	 * @param mixed $entity
	 * @return mixed $id
	 */
	public function update($entity);

	/**
	 * Delete data
	 *
	 * @param mixed $entity
	 * @return mixed $id
	 */
	public function delete($entity);
}