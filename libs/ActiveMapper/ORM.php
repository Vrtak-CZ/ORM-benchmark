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
 * The Active Mapper ORM
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper
 */
final class ORM
{
	/**#@+ ActiveMapper version identification */
	const NAME = 'Active Mapper ORM';
	const VERSION = '0.9-dev';
	const REVISION = '4943df3 released on 2010-07-11';
	const DEVELOPMENT = TRUE;
	/**#@-*/

	/**
	 * Static class - cannot be instantiated.
	 * 
	 * @throws LogicException
	 */
	final public function __construct()
	{
		throw new \LogicException("Cannot instantiate static class " . get_class($this));
	}

	/**
	 * Compares current Active Mapper ORM version with given version.
	 * 
	 * @param string $version
	 * @return int
	 */
	public static function compareVersion($version)
	{
		return version_compare($version, self::VERSION);
	}

	/**
	 * Get cache
	 *
	 * @param string $namespace nella namespace suffix
	 * @return Nette\Caching\Cache
	 */
	public static function getCache($namespace = NULL)
	{
		return \Nette\Environment::getCache($namespace ? "ActiveMapper.".$namespace : "ActiveMapper");
	}
}