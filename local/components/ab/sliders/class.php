<?php namespace Dresscode\Main;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Tools\Helpers\DataCache;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Esd\HL\MainTable;

Loc::loadLanguageFile(__FILE__);

Loader::includeModule('esd.hl');

class Sliders extends \CBitrixComponent
{
	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	/**
	* @method getUser
	* @return \CUser
	*/
	public function getUser(){
		global $USER;

		if(!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$items = [];
		$cache = new DataCache(86400, '/dress/slider', 'SliderBig');
		if($cache->isValid()){
			$items = $cache->getData();
		} else {
			$MainTable = new MainTable('SliderBig');
			$oList = $MainTable->init()->getList([
				'select'=> ['*'],
				'filter' => ['=UF_ACTIVE' => 1],
				'order' => ['UF_SORT'=> 'ASC', 'ID' => 'DESC']
			]);
			while ($list = $oList->fetch()){
				if((int)$list['UF_MAIN_IMG'] > 0){
					$list['UF_MAIN_IMG'] = \CFile::GetFileArray($list['UF_MAIN_IMG']);
				}
				if((int)$list['UF_PRODUCT'] > 0){
					$list['PRODUCT_ITEM'] = \CIBlockElement::GetList(
						array(),
						array('=ID' => $list['UF_PRODUCT']),
						false,
						array('nTopCount' => 1),
						array('ID','IBLOCK_ID','DETAIL_PAGE_URL')
					)->GetNext(true, false);
				}

				$items[] = $list;
			}
			$cache->addCache($items);
		}

		$this->arResult['ITEMS'] = $items;

		$this->includeComponentTemplate();
	}
}