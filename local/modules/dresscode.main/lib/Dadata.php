<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 23.08.2017
 */

namespace Dresscode\Main;

use Bitrix\Main;

class Dadata
{
	private $token= '2404ba672f38b1f1429b4eae6245bdb12b8fcbbd';
	private $apiKey = '526dbbd7f554f28f80951db6401cd7631064b75b';
	private $Client;
	private $url = 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address';
	/**
	 * Dadata constructor.
	 */
	public function __construct()
	{
		$this->Client = new Main\Web\HttpClient();
		$this->Client->setHeader('Authorization', 'Token '. $this->token);
		$this->Client->setHeader('X-Secret', $this->apiKey);
		$this->Client->setHeader('Content-Type', 'application/json');

	}

	/**
	 * {
			"query": "Пе",
			"from_bound": { "value": "region" },
			"to_bound": { "value": "region" }
		}
	 *
	 *
	 * @method getRegion
	 * @param $q
	 *
	 * @return string json
	 */
	public function getRegion($q)
	{
		$post = [
			'query' => $q,
			'from_bound' => ['value' => 'region'],
			'to_bound' => ['value' => 'region'],
		];

		return $this->doRequest($post);
	}

	public function getStreetByCity($q, $city)
	{
		$post = [
			'query' => $q,
			'locations' => [
				array('city' => $city)
			],
			'restrict_value' => true
		];

		return $this->doRequest($post);
	}

	protected function doRequest($post)
	{
		return $this->Client->post($this->url, Main\Web\Json::encode($post));
	}
}