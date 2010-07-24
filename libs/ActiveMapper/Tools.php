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
 * Tools
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček (http://patrik.votocek.cz)
 * @copyright  Copyright (c) 2009 Roman Sklenář (http://romansklenar.cz)
 * @copyright  Copyright (c) 2008 Luke Baker (http://lukebaker.org)
 * @copyright  Copyright (c) 2005 Flinn Mueller (http://actsasflinn.com)
 * @license    New BSD License
 * @package    ActiveMapper
 */
class Tools
{
	/** @var array  of singular nouns as rule => replacement */
	public static $singulars = array(
		'/(quiz)$/i' => '\1zes',
		'/^(ox)$/i' => '\1en',
		'/([m|l])ouse$/i' => '\1ice',
		'/(matr|vert|ind)(?:ix|ex)$/i' => '\1ices',
		'/(x|ch|ss|sh)$/i' => '\1es',
		'/([^aeiouy]|qu)y$/i' => '\1ies',
		'/(hive)$/i' => '\1s',
		'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
		'/sis$/i' => 'ses',
		'/([ti])um$/i' => '\1a',
		'/(buffal|tomat)o$/i' => '\1oes',
		'/(bu)s$/i' => '\1ses',
		'/(alias|status)$/i' => '\1es',
		'/(octop|vir)us$/i' => '\1i',
		'/(ax|test)is$/i' => '\1es',
		'/s$/i' => 's',
		'/$/' => 's',
	);
	/** @var array  of plural nouns as rule => replacement */
	public static $plurals = array(
		'/(database)s$/i' => '\1',
		'/(quiz)zes$/i' => '\1',
		'/(matr)ices$/i' => '\1ix',
		'/(vert|ind)ices$/i' => '\1ex',
		'/^(ox)en/i' => '\1',
		'/(alias|status)es$/i' => '\1',
		'/(octop|vir)i$/i' => '\1us',
		'/(cris|ax|test)es$/i' => '\1is',
		'/(shoe)s$/i' => '\1',
		'/(o)es$/i' => '\1',
		'/(bus)es$/i' => '\1',
		'/([m|l])ice$/i' => '\1ouse',
		'/(x|ch|ss|sh)es$/i' => '\1',
		'/(m)ovies$/i' => '\1ovie',
		'/(s)eries$/i' => '\1eries',
		'/([^aeiouy]|qu)ies$/i' => '\1y',
		'/([lr])ves$/i' => '\1f',
		'/(tive)s$/i' => '\1',
		'/(hive)s$/i' => '\1',
		'/([^f])ves$/i' => '\1fe',
		'/(^analy)ses$/i' => '\1sis',
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
		'/([ti])a$/i' => '\1um',
		'/(n)ews$/i' => '\1ews',
		'/s$/i' => '',
	);
	/** @var array  of irregular nouns */
	public static $irregular = array(
		'person' => 'people',
		'man' => 'men',
		'child' => 'children',
		'sex' => 'sexes',
		'move' => 'moves',
		'cow' => 'kine',
	);
	/** @var array  of uncountable nouns */
	public static $uncountable = array('equipment', 'information', 'rice', 'money', 'species', 'series', 'fish', 'sheep', 'jeans');

	/**
	 * Static class - cannot be instantiated.
	 *
	 * @throws LogicException
	 */
	final public function __construct()
	{
		throw new \LogicException("Cannot instantiate static class '".get_called_class()."'");
	}

	/**
	 * The reverse of pluralize, returns the singular form of a word.
	 *
	 * @param string $word
	 * @return string
	 */
	public static function singularize($word)
	{
		$lower = String::lower($word);

		if (self::isSingular($word))
			return $word;

		if (!self::isCountable($word))
			return $word;

		if (self::isIrregular($word)) {
			foreach (self::$irregular as $single => $plural) {
				if ($lower == $plural)
					return $single;
			}
		}

		foreach (self::$plurals as $rule => $replacement) {
			if (preg_match($rule, $word))
				return preg_replace($rule, $replacement, $word);
		}

		return FALSE;
	}

	/**
	 * Returns the plural form of the word.
	 *
	 * @param string $word
	 * @return string
	 */
	public static function pluralize($word)
	{
		$lower = String::lower($word);

		if (self::isPlural($word))
			return $word;

		if (!self::isCountable($word))
			return $word;

		if (self::isIrregular($word))
			return self::$irregular[$lower];

		foreach (self::$singulars as $rule => $replacement) {
			if (preg_match($rule, $word))
				return preg_replace($rule, $replacement, $word);
		}

		return FALSE;
	}

	/**
	 * Is given string singular noun?
	 *
	 * @param string $word
	 * @return bool
	 */
	public static function isSingular($word)
	{
		if (!self::isCountable($word))
			return TRUE;

		return !self::isPlural($word);
	}

	/**
	 * Is given string plural noun?
	 *
	 * @param string $word
	 * @return bool
	 */
	public static function isPlural($word)
	{
		$lower = String::lower($word);

		if (!self::isCountable($word))
			return TRUE;

		if (self::isIrregular($word))
			return in_array($lower, array_values(self::$irregular));

		foreach (self::$plurals as $rule => $replacement) {
			if (preg_match($rule, $word))
				return TRUE;
		}

		return FALSE;
	}

	/**
	 * Is given string countable noun?
	 *
	 * @param string $word
	 * @return bool
	 */
	public static function isCountable($word)
	{
		$lower = String::lower($word);
		return (bool) !in_array($lower, self::$uncountable);
	}

	/**
	 * Is given string irregular noun?
	 *
	 * @param string $word
	 * @return bool
	 */
	public static function isIrregular($word)
	{
		$lower = String::lower($word);
		return (bool) in_array($lower, self::$irregular) || array_key_exists($lower, self::$irregular);
	}

	/**
	 * Ordinalize turns a number into an ordinal string used to denote
	 * the position in an ordered sequence such as 1st, 2nd, 3rd, 4th.
	 *
	 * @param int $number
	 * @return string
	 */
	public static function ordinalize($number)
	{
		$number = (int) $number;

		if ($number % 100 >= 11 && $number % 100 <= 13)
			return "{$number}th";
		else {
			switch ($number % 10) {
				case 1: return "{$number}st";
				case 2: return "{$number}nd";
				case 3: return "{$number}rd";
				default: return "{$number}th";
			}
		}
	}

	/**
	 * Convert string to UpperCamelCase.
	 * If the second argument is set to FALSE then camelize() produces lowerCamelCase.
	 * 
	 * - this_functio -> thisFunction
	 *
	 * @param string $s lower case and underscored word
	 * @param bool $firstUpper first letter in uppercase?
	 * @return string
	 */
	public static function camelize($s, $firstUpper = FALSE)
	{
		$s = preg_replace_callback('~_([a-z])~', function($m) { return strtoupper($m[1]); }, $s);
		return $firstUpper ? ucfirst($s) : lcfirst($s);
	}

	/**
	 * Convert string to under_score_case.
	 * 
	 * - thistFunction -> this_function
	 *
	 * @param string $s camel cased word
	 * @return string
	 */
	public static function underscore($s)
	{
		return preg_replace_callback('~([A-Z])~', function($m) { return '_'.strtolower($m[1]); }, lcfirst($s));
	}
}