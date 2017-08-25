<?php namespace Dresscode\Catalog;
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
use Bitrix\Main;
use Online1c\Iblock;

Loc::loadLanguageFile(__FILE__);

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('iblock');

class Sections extends \CBitrixComponent
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
		TrimArr($arParams['SECTION_FIELDS']);
		TrimArr($arParams['SECTION_USER_FIELDS']);

		$this->arResult['PARENT'] = null;
		if ((int)$arParams['SECTION_DATA']['ID'] > 0){
			$this->arResult['PARENT'] = $arParams['SECTION_DATA'];
		}

		$arParams['TOP_DEPTH'] = (int)$arParams['TOP_DEPTH'];
		if ($arParams['TOP_DEPTH'] == 0)
			$arParams['TOP_DEPTH'] = 1;

		$this->arResult['UF_ID'] = 'IBLOCK_'.$arParams['IBLOCK_ID'].'_SECTION';

		return $arParams;
	}

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser()
	{
		global $USER;

		if (!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	public function buildFilter($filter = [])
	{
		$result = [
			'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			'ACTIVE' => 'Y',
			'GLOBAL_ACTIVE' => 'Y',
		];

		if (!is_null($this->arResult['PARENT'])){

		}

		if ($this->arParams['TOP_DEPTH'] > 0){
			$result['<=DEPTH_LEVEL'] = $this->arParams['TOP_DEPTH'];
		}

		return array_merge($result, $filter);
	}

	public function buildSort($sort = [])
	{
		$result = ["DEPTH_LEVEL" => "DESC"];

		return array_merge($result, $sort);
	}

	public function buildSelect($select = [])
	{
		$result = [
			'ID', 'NAME', 'IBLOCK_ID', 'SECTION_CODE' => 'CODE', 'SECTION_ID' => 'ID',
			'PICTURE', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'IBLOCK_SECTION_ID', 'SORT', 'DEPTH_LEVEL'
		];

		return array_merge($result, $select);
	}

	/**
	 * @method getIterator
	 * @return Main\DB\Result
	 */
	public function getIterator()
	{
		$filter = $this->buildFilter();
		$select = $this->buildSelect();
		$order = $this->buildSort();

		Iblock\SectionTable::setUserFieldId($this->arResult['UF_SECTION_ID']);
		Main\UserFieldTable::attachFields(Iblock\SectionTable::getEntity(), $this->arResult['UF_SECTION_ID']);

		$oSection = Iblock\SectionTable::getList([
			'select' => $select,
			'filter' => $filter,
			'order' => $order,
			'limit' => 100,
		]);

		if (strlen($this->arParams['SECTION_URL']) == 0){
			$iblockData = \Bitrix\Iblock\IblockTable::getRow([
				'select' => ['DETAIL_PAGE_URL', 'SECTION_PAGE_URL'],
				'filter' => ['=ID' => $this->arParams['IBLOCK_ID']],
			]);
			$this->arParams['SECTION_URL'] = $iblockData['SECTION_PAGE_URL'];
		}

		$oSection->addFetchDataModifier(Iblock\Helpers\PageUrlHelper::makeDetailUrl($this->arParams['SECTION_URL']));

		return $oSection;
	}

	/**
	 * @method makeTreeSections
	 * @param Main\DB\Result $iterator
	 *
	 * @return array
	 */
	protected function makeTreeSections(Main\DB\Result $iterator)
	{
		$listItems = [];
		while ($sect = $iterator->fetch()) {
			$listItems[$sect['ID']] = $sect;
		}
		$maxLevel = $this->arParams['TOP_DEPTH'];

		for ($i = $maxLevel; $i > 1; $i--) {
			foreach ($listItems as $sId => $value) {
				if ($value['DEPTH_LEVEL'] == $i){
					$listItems[$value['IBLOCK_SECTION_ID']]['SUB_SECTION'][] = $value;
					unset($listItems[$sId]);
				}
			}
		}

		usort($listItems, array($this, '__sectionSort'));

		return $listItems;
	}

	/**
	 * @method __sectionSort
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	public function __sectionSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']){
			return 0;
		}

		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$cacheId = md5(serialize($this->arParams));
		$cache = new DataCache($this->arParams['CACHE_TIME'], '/dress/catalog/sections', $cacheId);
		if($this->arParams['CACHE_TYPE'] == 'N')
			$cache->clear();

		if($cache->isValid()){
			$items = $cache->getData();
		} else {
			$iterator = $this->getIterator();
			$items = $this->makeTreeSections($iterator);

			$cache->addCache($items);
		}

		$this->arResult['ITEMS'] = $items;

		unset($items, $iterator);

//		dump($this->arResult['ITEMS']);

		$this->includeComponentTemplate();
	}
}