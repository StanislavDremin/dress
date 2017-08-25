<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 17.07.2017
 */

namespace Dresscode\Main;

use AB\Tools\Helpers\DataCache;
use Bitrix\Main;
use Bitrix\Sale;

Main\Loader::includeModule('sale');

class CityServices
{

	const CITY_SESS = 'MY_CITY';

	public function __construct()
	{
	}

	/**
	 * @method getCityListAction
	 * @return array
	 */
	public function getCityListAction()
	{
		$result = [];

		$cache = new DataCache(86400, '/dress/location', self::CITY_SESS);
		if($cache->isValid()){
			$result = $cache->getData();
		} else {
			$obMos = Sale\Location\LocationTable::getList([
				'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, '=ID' => [3, 129]),
				'select' => array('ID', 'CODE', 'CITY_ID', 'TITLE' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE'),
				'order' => ['ID' => 'DESC']
			]);
			while ($mo = $obMos->fetch()){
				$mo['SEARCH'] = strtoupper($mo['TITLE']);
				$result[] = $mo;
			}
			$res = Sale\Location\LocationTable::getList(array(
				'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, '=TYPE.CODE' => 'CITY', '!=REGION_ID' => 3),
				'select' => array('ID', 'CODE', 'CITY_ID', 'TITLE' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE'),
			));
			while ($item = $res->fetch()) {
				$item['SEARCH'] = strtoupper($item['TITLE']);
				$result[] = $item;
			}

			$cache->addCache($result);
		}

		return $result;
	}

	/**
	 * @method setMyCityAction
	 * @param array $data
	 */
	public function setMyCityAction($data = [])
	{
		$_SESSION[self::CITY_SESS] = self::getCityById($data['id']);
		global $APPLICATION;
		$APPLICATION->set_cookie('CITY', $data['id'], false, '/');
	}

	/**
	 * @method getCityById
	 * @param $id
	 *
	 * @return array|null
	 * @throws \Exception
	 */
	private static function getCityById($id)
	{
		$id = (int)$id;
		if($id == 0){
			throw new \Exception('Город не найден', 500);
		}

		return Sale\Location\LocationTable::getRow([
			'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID, '=ID' => $id),
			'select' => array('ID', 'CODE', 'CITY_ID', 'TITLE' => 'NAME.NAME', 'TYPE_CODE' => 'TYPE.CODE'),
		]);

	}

	/**
	 * @method getMyCity
	 * @return mixed
	 */
	public static function getMyCity()
	{
		$request = Main\Context::getCurrent()->getRequest();
		$id = (int)$request->getCookie('CITY');

		if(empty($_SESSION[self::CITY_SESS]) && $id > 0){
			$_SESSION[self::CITY_SESS] = self::getCityById($id);
		}

		return $_SESSION[self::CITY_SESS];
	}

	public function getMyCityAction()
	{
		return self::getMyCity();
	}
}