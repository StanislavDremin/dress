<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 24.07.2017
 */

namespace Dresscode\Main;

use Bitrix\Main\Type\Dictionary;

class ModifierComponentsFilter
{
	/** @var  Dictionary */
	private $components;

	/** @var  ModifierComponentsFilter */
	private static $instance = null;

	/**
	 * ModifierComponentsFilter constructor.
	 */
	public function __construct()
	{
		$this->components = new Dictionary();
	}

	/**
	 * @method add
	 * @param $name
	 * @param $value
	 */
	public function add($name, $value)
	{
		$this->components->offsetSet($name, $value);
	}

	/**
	 * @method get
	 * @param $name
	 *
	 * @return null|string|array
	 */
	public function get($name)
	{
		return $this->components->get($name);
	}

	/**
	 * @method getInstance
	 * @return ModifierComponentsFilter|static
	 */
	public static function getInstance()
	{
		if(is_null(self::$instance)){
			self::$instance = new static();
		}
		return self::$instance;
	}
}