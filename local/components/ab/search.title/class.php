<?php namespace Dresscode\Search;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use \CSearchTitle;
use Dresscode\Main\Config;
use Online1c\Iblock;

Main\Loader::includeModule('search');
Main\Loader::includeModule('iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');
Main\Loader::includeModule('online1c.iblock');

Loc::loadLanguageFile(__FILE__);

class SearchTitle extends \CBitrixComponent
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
	public function getUser()
	{
		global $USER;

		if (!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	public function searchingAction($data = [])
	{
		$query = $data['q'];
		$result = [];
//		sleep(20);
		\CUtil::decodeURIComponent($query);

		$altQuery = '';
		$arLang = \CSearchLanguage::GuessLanguage($query);


		if (is_array($arLang) && $arLang["from"] != $arLang["to"])
			$altQuery = \CSearchLanguage::ConvertKeyboardLayout($query, $arLang["from"], $arLang["to"]);

		if (strlen($query) > 2){
			$phrase = stemming_split($query, LANGUAGE_ID);
			$obTitle = new CSearchTitle();
			$obTitle->setMinWordLength(3);
			if ($obTitle->Search(
				$altQuery ? $altQuery : $query
				, 5
				, array(
					"SITE_ID" => Main\Context::getCurrent()->getSite(),
					"MODULE_ID" => 'iblock',
					"PARAM1" => "catalog",
					"PARAM2" => \Dresscode\Main\Config::getIblock('CATALOG'),
				)
				, false
			)
			){
				$productsIds = $sectionIds = [];
				while ($searchItem = $obTitle->Fetch()) {
					if (substr($searchItem['ITEM_ID'], 0, 1) === 'S'){
						$section = str_replace('S', '', $searchItem['ITEM_ID']);
						$sectionIds[] = $section;
					} else {
						$productsIds[] = $searchItem['ITEM_ID'];
					}
				}
				$result = $this->getProducts($productsIds, $sectionIds);
			}
		}

		return $result;
	}

	private function getUrlOptions()
	{
		$detailUrl = $this->arParams['DETAIL_URL'];
		if(strlen($detailUrl) == 0){
			$iblock = \Bitrix\Iblock\IblockTable::getRowById(Config::getIblock('CATALOG'));
			$detailUrl = $iblock['DETAIL_PAGE_URL'];
		}
		$detailUrl = str_replace("#SITE_DIR#", '', $detailUrl);

		return $detailUrl;
	}

	protected function getProducts($productsIds, $sectionsIds)
	{

		$products = $arSections = [];
		$obItems = Iblock\Element::getList([
			'select' => [
				'ID', 'NAME', 'IBLOCK_ID', 'CODE', 'DETAIL_PICTURE',
				'ELEMENT_CODE' => "CODE",
				'SECTION_ID' => 'IBLOCK_SECTION.ID', 'SECTION_CODE' => 'IBLOCK_SECTION.CODE',
				'SECTION_NAME' => 'IBLOCK_SECTION.NAME',
				'PRICE_VALUE' => 'PRICE.PRICE',
			],
			'filter' => [
				'IBLOCK_ID' => Config::getIblock('CATALOG'),
				'=ID' => $productsIds,
				'>CATALOG.QUANTITY' => 0
			],
			'limit' => count($productsIds),
			'order' => ['ID' => 'DESC'],
			'runtime' => [
				new Main\Entity\ReferenceField(
					'CATALOG',
					\Bitrix\Catalog\ProductTable::getEntity(),
					['=this.ID' => 'ref.ID']
				),
				new Main\Entity\ReferenceField(
					'PRICE',
					\Bitrix\Catalog\PriceTable::getEntity(),
					['=this.ID' => 'ref.PRODUCT_ID']
				)
			]
		]);

		$urlOption = $this->getUrlOptions();
		$obItems->addFetchDataModifier(Iblock\Helpers\PageUrlHelper::makeDetailUrl($urlOption));

		while ($item = $obItems->fetch()) {
			$arSections[$item['SECTION_ID']] = [
				'ID' => $item['SECTION_ID'],
				'NAME' => $item['SECTION_NAME'],
				'CODE' => $item['SECTION_CODE'],
				'IBLOCK_ID' => $item['IBLOCK_ID'],
				'SECTION_PAGE_URL' => '/catalog/'.$item['SECTION_CODE'].'/'
			];
			if((int)$item['DETAIL_PICTURE'] > 0){
				$item['IMG'] = \CFile::ResizeImageGet($item['DETAIL_PICTURE'], ['width' => 80, 'height' => 100], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
			}
			$item['PRICE_FORMAT'] = \SaleFormatCurrency($item['PRICE_VALUE'], 'RUB', true);
			$products[] = $item;
		}
		if(count($sectionsIds) > 0){
			$oSection = \Bitrix\Iblock\SectionTable::getList([
				'select' => ['ID','NAME','IBLOCK_ID', 'CODE'],
				'filter' => ['IBLOCK_ID' => Config::getIblock('CATALOG'), '=ID' => $sectionsIds]
			]);
			while ($section = $oSection->fetch()){
				$section['SECTION_PAGE_URL'] = '/catalog/'.$section['CODE'].'/';
				$arSections[$section['ID']] = $section;
			}
		}

		$resultSections = [];
		foreach ($arSections as &$value) {
			$obSect = \CIBlockSection::GetNavChain(
				Config::getIblock('CATALOG'),
				$value['ID'],
				['ID', 'CODE', 'NAME', 'SECTION_PAGE_URL']
			);
			while ($rs = $obSect->GetNext(true, false)){
				$value['CHAIN'][] = $rs;
			}
		}

		foreach ($arSections as $arSection) {
			foreach ($arSection['CHAIN'] as $item) {
				$resultSections[$item['ID']] = $item;
			}
		}

		return [
			'products' => $products,
			'sections' => $resultSections
		];
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}
}