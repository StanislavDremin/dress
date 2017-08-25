<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 18.07.2017
 */

namespace Dresscode\Main\InSaleImport;

use Bitrix\Main;

abstract class BaseImport
{
	/** @var \InSales\API\ApiClient  */
	protected $client;

	private $options;

	/**
	 * Sections constructor.
	 *
	 * @param $params
	 */
	public function __construct($params = false)
	{
		$this->client = new \InSales\API\ApiClient(
			'5329f7fc792e1b9bd843726637e6614f',
			'41bf6b43bc004f2c64ca76df9f27fa02',
			'www.dresscod.org'
		);
		if(is_callable($params)){
			$this->options = new Main\Type\Dictionary($params());
		} else {
			$this->options = new Main\Type\Dictionary($params);
		}
	}


	/**
	 * @method getOptions - get param options
	 * @return Main\Type\Dictionary
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param Main\Type\Dictionary $options
	 */
	public function setOptions(Main\Type\Dictionary $options)
	{
		$this->options = $options;
	}
}