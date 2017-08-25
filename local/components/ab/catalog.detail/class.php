<?php namespace Dress\Catalog;
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
use Bitrix\Sale\Basket;
use Bitrix\Sale\Internals\BasketPropertyTable;
use Dresscode\Main\Config;
use Online1c\Iblock;
use Bitrix\Highloadblock as HL;
use Bitrix\Catalog\CatalogViewedProductTable;
use Dresscode\Main\Favorite;
use Bitrix\Sale\Internals\BasketTable;

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');
Main\Loader::includeModule('highloadblock');

Loc::loadLanguageFile(__FILE__);

class DetailComponent extends \CBitrixComponent
{
	private $defaultSelect = [
		'ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'PREVIEW_TEXT', 'DETAIL_TEXT',
		'DETAIL_PICTURE', 'CODE', 'TAGS',
	];

	/** @var Main\Type\Dictionary */
	private $select;

	private $metaProperty;

	protected $nameCmp = 'ad:product.detail';

	private $fUser;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->select = new Main\Type\Dictionary($this->defaultSelect);
		$this->fUser = \Bitrix\Sale\Fuser::getId(true);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		TrimArr($arParams['DETAIL_PROPERTY_CODE']);

		if($this->request->get('temple')){
			$param = unserialize(base64_decode($_SESSION['CMP'][$this->nameCmp.':'.$this->request->get('temple')]));
			if(count($param) > 0){
				$this->arParams = $param;
			}
		}

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

	public function buildSelect()
	{
		$this->metaProperty = Iblock\BaseProperty::getMetaProperty($this->arParams['IBLOCK_ID']);
		$propSelect = $this->arParams['DETAIL_PROPERTY_CODE'];
		foreach ($propSelect as $code) {
			if (array_key_exists($code, $this->metaProperty)){
				$this->select->offsetSet($code, 'PROPERTY.'.$code);
			}
		}

		return $this->select;
	}

	/**
	 * @method getElementData
	 * @return array|null
	 */
	public function getElementData()
	{
		$this->select->offsetSet('PRICE_VALUE', 'PRICE.PRICE');
		$this->select->offsetSet('QUANTITY', 'CATALOG.QUANTITY');

		try {
			$arElement = Iblock\Element::getRow([
				'select' => $this->select->toArray(),
				'filter' => [
					'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
					'=CODE' => $this->arParams['ELEMENT_CODE'],
					'=ACTIVE' => 'Y',
				],
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
					),
				],
			]);

			return $arElement;

		} catch (\Exception $err) {
//			dump($err);
		}

	}

	public function prepareDataElement($element)
	{
		if ((int)$element['DETAIL_PICTURE'] > 0){
			$element['MAIN_IMG'] = \CFile::ResizeImageGet(
				$element['DETAIL_PICTURE'],
				['width' => 500, 'height' => 745],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT
			);
			$element['MAIN_IMG']['SMALL'] = \CFile::ResizeImageGet(
				$element['DETAIL_PICTURE'],
				['width' => 80, 'height' => 100],
				BX_RESIZE_IMAGE_EXACT
			);
		}

		if (count($element['COLOR']['VALUE']) > 0){
			$settings = unserialize($this->metaProperty['COLOR']['USER_TYPE_SETTINGS']);
			$rowBlock = HL\HighloadBlockTable::getRow([
				'filter' => ['TABLE_NAME' => $settings['TABLE_NAME']],
			]);
			$dataClass = HL\HighloadBlockTable::compileEntity($rowBlock)->getDataClass();
			$oColors = $dataClass::getList([
				'filter' => ['=UF_XML_ID' => $element['COLOR']['VALUE']],
			]);
			while ($color = $oColors->fetch()) {
				if ((int)$color['UF_FILE'] > 0){
					$color['IMG'] = \CFile::GetFileArray($color['UF_FILE']);
				}
				$element['COLOR']['ITEMS'][] = $color;
			}
		}

		if (count($element['MORE_PHOTO']['VALUE']) > 0){
			foreach ($element['MORE_PHOTO']['VALUE'] as $i => $value) {
				$photo = \CFile::ResizeImageGet($value, ['width' => 500, 'height' => 745], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
				$photo['SMALL'] = \CFile::ResizeImageGet($value, ['width' => 80, 'height' => 100], BX_RESIZE_IMAGE_EXACT);
				$element['MORE_PHOTO']['ITEMS'][$i] = $photo;
			}
		}

		$element['SLIDER_PHOTO'] = [];
		if (strlen($element['MAIN_IMG']['src']) > 0){
			$element['SLIDER_PHOTO'][] = $element['MAIN_IMG'];
		}
		if (count($element['MORE_PHOTO']['ITEMS']) > 0){
			$element['SLIDER_PHOTO'] += $element['MORE_PHOTO']['ITEMS'];
		}

		if (strlen($element['BRAND']) > 0){
			$settingsBrand = unserialize($this->metaProperty['BRAND']['USER_TYPE_SETTINGS']);
			$rowBlockBrand = HL\HighloadBlockTable::getRow([
				'filter' => ['TABLE_NAME' => $settingsBrand['TABLE_NAME']],
			]);
			$dataClass = HL\HighloadBlockTable::compileEntity($rowBlockBrand)->getDataClass();
			$brandXmlId = $element['BRAND'];
			$element['BRAND'] = [
				'CODE' => $brandXmlId,
				'ITEM' => $dataClass::getRow([
					'filter' => ['=UF_XML_ID' => $brandXmlId],
				]),
			];
		}

		$element['PRICE_FORMAT'] = \SaleFormatCurrency($element['PRICE_VALUE'], 'RUB');
		$element['QUANTITY'] = (int)$element['QUANTITY'];
		$element['TAG_ITEMS'] = explode(',', $element['TAGS']);
		$element['COUNT_TAGS'] = count($element['TAG_ITEMS']);

		return $element;
	}

	/**
	 * @method addToFavoriteAction
	 * @param array $data
	 *
	 * @return int
	 * @throws \Exception
	 */
	public function addToFavoriteAction($data = [])
	{
		Favorite\FavoriteTable::createTable();

		$row = Favorite\FavoriteTable::getByElement($data['productId']);
		if (!is_null($row)){
			throw new \Exception('Вы уже добавили этот товар в избранное', 403);
		}

		$colorClass = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getRowById(1))->getDataClass();
		$sizeClass = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getRowById(5))->getDataClass();
		$colorValue = $sizeValue = [];
		$obColorValue = $colorClass::getRow([
			'filter' => ['=UF_XML_ID' => $data['color']],
			'select' => ['UF_NAME']
		]);
		$obSizeValue = $sizeClass::getRow([
			'filter' => ['=UF_XML_ID' => $data['size']],
			'select' => ['UF_NAME']
		]);
		/*while ($rs = $obColorValue->fetch()){
			$colorValue[] = $rs['UF_NAME'];
		}
		while ($rs = $obSizeValue->fetch()){
			$sizeValue[] = $rs['UF_NAME'];
		}*/

		$save = Favorite\FavoriteTable::add([
			'ELEMENT_ID' => $data['productId'],
			'PROPS' => ['SIZE' => $obSizeValue['UF_NAME'], 'COLOR' => $obColorValue['UF_NAME']],
		]);
		if (!$save->isSuccess()){
			throw new \Exception(implode(', ', $save->getErrorMessages()));
		}

		return $save->getId();
	}

	public function addToCartAction($data = [])
	{
		$productId = (int)$data['productId'];
		$quantity = (int)$data['quantity'];
		if ($quantity == 0){
			$quantity = 1;
		}
		if ($productId == 0){
			throw new \Exception('Нет товара для добавления в корзину', 404);
		}

		$arProduct = Iblock\Element::getRow([
			'select' => [
				'ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE',
				'PRICE_VAL' => 'PRICE.PRICE',
				'QUANTITY' => 'CATALOG.QUANTITY',
			],
			'filter' => ['=ID' => $productId, '=ACTIVE' => 'Y'],
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
				),
			],
		]);

		if (is_null($arProduct)){
			throw new \Exception('Товар не доступен для продажи', 403);
		}

		if ((int)$arProduct['QUANTITY'] == 0){
			throw new \Exception('Товар отсутствует на складе', 403);
		}

		$siteId = Main\Context::getCurrent()->getSite();
		$basket = Basket::loadItemsForFUser($this->fUser, $siteId);
		$productAdd = [
			'QUANTITY' => $quantity,
			'NAME' => $arProduct['NAME'],
			'PRICE' => $arProduct['PRICE_VAL'],
			'CURRENCY' => 'RUB',
		];

		$colorClass = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getRowById(1))->getDataClass();
		$sizeClass = HL\HighloadBlockTable::compileEntity(HL\HighloadBlockTable::getRowById(5))->getDataClass();
		$colorValue = $sizeValue = [];
		$obColorValue = $colorClass::getRow([
			'filter' => ['=UF_XML_ID' => $data['color']],
			'select' => ['UF_NAME']
		]);
		$obSizeValue = $sizeClass::getRow([
			'filter' => ['=UF_XML_ID' => $data['size']],
			'select' => ['UF_NAME']
		]);
//		while ($rs = $obColorValue->fetch()){
//			$colorValue[] = $rs['UF_NAME'];
//		}
//		while ($rs = $obSizeValue->fetch()){
//			$sizeValue[] = $rs['UF_NAME'];
//		}

		$arProps = [
			[
				'NAME' => 'SIZE',
				'VALUE' => $obSizeValue['UF_NAME'],
				'CODE' => 'SIZE',
				'SORT' => 100,
			],
			[
				'NAME' => 'COLOR',
				'VALUE' => $obColorValue['UF_NAME'],
				'CODE' => 'COLOR',
				'SORT' => 100,
			],
		];

		/*$existId = BasketTable::getRow([
			'filter' => [
				'FUSER_ID' => $this->fUser,
				'ORDER_ID' => false,
				'=PRODUCT_ID' => $productId
			],
			'select' => ['ID','PRODUCT_ID','QUANTITY']
		]);*/
		$item = $basket->getExistsItem('catalog', $productId, $arProps);

		if (is_null($item)){
			$item = $basket->createItem('catalog', $productId, $arProps);
			$item->setFields($productAdd);
		} else {
//			/** @var \Bitrix\Sale\BasketItem $item */
//			$item = $basket->getItemById($existId['ID']);
			if ($quantity == 1)
				$quantity = $item->getQuantity() + 1;

			$item->setField('QUANTITY', $quantity);
		}

		$idSave = $item->save()->getId();
		$save = $basket->save();
		foreach ($arProps as $val) {
			$rowProp = BasketPropertyTable::getRow([
				'select' => ['ID'],
				'filter' => ['CODE' => $val['CODE'], 'BASKET_ID' => $item->getId()],
			]);
			if (!is_null($rowProp)){
				BasketPropertyTable::delete($rowProp['ID']);
			}
			$val['BASKET_ID'] = $item->getId();
			BasketPropertyTable::add($val);
		}

		if (!$save->isSuccess()){
			throw new \Exception(implode(', ', $save->getErrorMessages()), 500);
		}

		$totalQuantity = BasketTable::getCount([
			'LID' => $siteId,
			'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
			'ORDER_ID' => false
		]);

		$_SESSION['DRESS_BASKET_QUANTITY'][$siteId][$idSave] = $totalQuantity;

		return ['id' => $idSave, 'quantity' => $totalQuantity];
	}

	public function getSmallDetailAction($data = [])
	{
		$id = (int)$data['id'];
		if($id == 0){
			throw new \Exception('Товар не установлен', 500);
		}
		$iblock = (int)$this->arParams['IBLOCK_ID'];
		if($iblock == 0){
			$iblock = Config::getIblock('CATALOG');
		}
		$row = \Bitrix\Iblock\ElementTable::getRow([
			'filter' => ['=ID' => $id, 'IBLOCK_ID' => $iblock],
			'select' => ['CODE']
		]);
		if(is_null($row)){
			throw new \Exception('Товар не найден', 404);
		}
		$this->arParams['ELEMENT_CODE'] = $row['CODE'];
		$this->arParams['DETAIL_PROPERTY_CODE'] = [
			"BRAND",
			"SIZE",
			"COLOR",
			"NEWPRODUCT",
			"SALELEADER",
			"SPECIALOFFER",
			"ARTNUMBER",
			"MATERIAL",
			"BLOG_COMMENTS_CNT",
			"MORE_PHOTO",
		];
		$this->arParams['IBLOCK_ID'] = $iblock;

		$this->buildSelect();
		$arElement = $this->getElementData();
		return $this->prepareDataElement($arElement);
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->buildSelect();

		$arElement = $this->getElementData();

		$this->arResult = $this->prepareDataElement($arElement);

		if ($this->arParams['SAVE_VIEWED_PRODUCTS'] == 'Y' && (int)$this->arResult['ID'] > 0){
			$row = CatalogViewedProductTable::getRow([
				'filter' => [
					'=FUSER_ID' => \CSaleBasket::GetBasketUserID(),
					'ELEMENT_ID' => $this->arResult['ID'],
					'SITE_ID' => Main\Context::getCurrent()->getSite(),
				],
				'select' => ['ID', 'VIEW_COUNT'],
			]);
			if (is_null($row)){
				CatalogViewedProductTable::add([
					'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
					'ELEMENT_ID' => $this->arResult['ID'],
					'PRODUCT_ID' => $this->arResult['ID'],
					'SITE_ID' => Main\Context::getCurrent()->getSite(),
				]);
			} else {
				CatalogViewedProductTable::update($row['ID'], [
					'VIEW_COUNT' => $row['VIEW_COUNT'] + 1,
				]);
			}

			$oView = CatalogViewedProductTable::getList([
				'filter' => [
					'=FUSER_ID' => \CSaleBasket::GetBasketUserID(),
					'!=ELEMENT_ID' => $this->arResult['ID'],
					'SITE_ID' => Main\Context::getCurrent()->getSite(),
				],
				'select' => ['ID', 'ELEMENT_ID'],
				'limit' => 15,
				'order' => ['ID' => 'DESC'],
			]);
			while ($rs = $oView->fetch()) {
				$this->arResult['VIEWED_PRODUCTS'][] = $rs['ELEMENT_ID'];
			}
		}

		$this->includeComponentTemplate();

		$params = [];
		foreach ($this->arParams as $code => $param) {
			if(substr($code, 0, 1) !== '~'){
				$params[$code] = $param;
			}
		}
		$_SESSION['CMP'][$this->nameCmp.':'.$this->getTemplate()->GetName()] = base64_encode(serialize($params));
	}
}