<?php
/**
 * ActiveMapper
 *
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://addons.nette.org/cs/active-mapper
 * @category   ActiveMapper
 * @package    ActiveMapper\Associations
 */

namespace ActiveMapper\Associations;

use ActiveMapper\Tools,
	ActiveMapper\Metadata;

/**
 * One to many entity association
 *
 * @author     Patrik Votoček
 * @copyright  Copyright (c) 2010 Patrik Votoček
 * @package    ActiveMapper\Associations
 * @property-read string $sourceEntity source entity class
 * @property-read string $targetEntity target entity class
 * @property-read string $name association name
 * @property-read string $sourceColumn source column name
 * @property-read string $targetColumn target column name
 */
final class OneToMany extends Base implements IAssociation
{
	/** @var string */
	protected $name;
	/** @var string */
	protected $column;

	/**
	 * Costructor
	 *
	 * @param string $sourceEntity valid source entity class
	 * @param string $targetEntity valid target entity class
	 * @param string $name
	 * @param string $column targer/source column name
	 * @throws InvalidArgumentException
	 */
	public function __construct($sourceEntity, $targetEntity, $name = NULL, $column = NULL)
	{
		parent::__construct($sourceEntity, $targetEntity);

		if (empty($name))
			$this->name = lcfirst(Tools::pluralize(Metadata::getMetadata($targetEntity)->name));
		else
			$this->name = $name;

		if (empty($column)) {
			$metadata = Metadata::getMetadata($sourceEntity);
			$this->column = Tools::underscore($metadata->name.ucfirst($metadata->primaryKey));
		} else
			$this->column = $column;
	}

	/**
	 * Get source column
	 *
	 * @return string
	 */
	public function getSourceColumn()
	{
		return Metadata::getMetadata($this->sourceEntity)->primaryKey;
	}

	/**
	 * Get target column
	 *
	 * @return string
	 */
	public function getTargetColumn()
	{
		return $this->column;
	}
}