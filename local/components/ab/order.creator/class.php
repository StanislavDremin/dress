<?php namespace Dresscode;
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
use Bitrix\Sale;
use Dresscode\Main\Config;
use Online1c\Iblock\Element;

Main\Loader::includeModule('sale');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('search');

Loc::loadLanguageFile(__FILE__);

class OrderComponent extends \CBitrixComponent
{
	protected
		$FUser,
		$isTestMode = true;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		$this->FUser = \CSaleBasket::GetBasketUserID();
		$this->setSiteId(Main\Context::getCurrent()->getSite());
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

	public function getBasket()
	{
		/** @var Sale\BasketItem $BasketItem */
		$Basket = Sale\Basket::loadItemsForFUser($this->FUser, $this->getSiteId());
		$result = [];
		$totalSum = $totalCount = 0;
		foreach ($Basket as $BasketItem) {
			$item = $BasketItem->getFieldValues();

			$item = [
				'PRODUCT_ID' => $BasketItem->getField('PRODUCT_ID'),
				'ID' => $BasketItem->getField('ID'),
				'QUANTITY' => (int)$BasketItem->getField('QUANTITY'),
				'PRICE' => $BasketItem->getField('PRICE'),
				'PRICE_FORMAT' => \SaleFormatCurrency($BasketItem->getField('PRICE'), $BasketItem->getField('CURRENCY'), true),
				'CURRENCY' => $BasketItem->getField('CURRENCY'),
				'NAME' => $BasketItem->getField('NAME'),
				'DELAY' => $BasketItem->getField('DELAY'),
				'CAN_BUY' => $BasketItem->getField('CAN_BUY'),
				'SUM' => $BasketItem->getFinalPrice(),
				'SUM_FORMAT' => \SaleFormatCurrency($BasketItem->getFinalPrice(), $BasketItem->getField('CURRENCY'), true),
				'PROPS' => $BasketItem->getPropertyCollection()->getPropertyValues(),
			];
			$totalSum += $item['SUM'];

			$arProduct = Element::getRow([
				'select' => ['ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE'],
				'filter' => ['IBLOCK_ID' => Config::getIblock('CATALOG'), '=ID' => $item['PRODUCT_ID']],
			]);
			if ((int)$arProduct['DETAIL_PICTURE'] > 0){
				$arProduct['IMAGE'] = \CFile::ResizeImageGet(
					$arProduct['DETAIL_PICTURE'],
					['width' => 100, 'height' => 100],
					BX_RESIZE_IMAGE_PROPORTIONAL_ALT
				);
			}

			$item['PRODUCT_DATA'] = $arProduct;

			$result[] = $item;
			$totalCount++;
		}

		return [
			'items' => $result,
			'totalSum' => $totalSum,
			'totalSumFormat' => self::priceFormat($totalSum),
			'totalCount' => $totalCount,
		];
	}

	public function getBasketAction()
	{
		return $this->getBasket();
	}

	public function updateQuantityAction($data = [])
	{
		$id = (int)$data['id'];
		if ($id == 0)
			throw new \Exception('Нет ID товара', 500);

		$Basket = Sale\Basket::loadItemsForFUser($this->FUser, $this->getSiteId());
		$BasketItem = $Basket->getItemById($id);
		if (is_null($BasketItem)){
			throw new \Exception('Продукт не найден', 404);
		}
		$BasketItem->setField('QUANTITY', (int)$data['quantity']);
		$basketSave = $Basket->save();
		if (!$basketSave->isSuccess()){
			throw new \Exception(implode(', ', $basketSave->getErrorMessages()), 502);
		}

		return $basketSave->isSuccess();
	}

	private static function priceFormat($val)
	{
		return \SaleFormatCurrency($val, 'RUB', true);
	}

	public function deleteItem($id)
	{
		$id = (int)$id;
		if ($id == 0)
			throw new \Exception('Нет ID товара', 500);

		$Basket = Sale\Basket::loadItemsForFUser($this->FUser, $this->getSiteId());
		$index = $Basket->getIndexById($id);
		$Basket->deleteItem($index);
		$basketSave = $Basket->save();

		if (!$basketSave->isSuccess()){
			throw new \Exception(implode(', ', $basketSave->getErrorMessages()), 502);
		}

		return $basketSave->isSuccess();
	}

	public function deleteItemAction($data = [])
	{
		return $this->deleteItem($data['id']);
	}

	public function getDeliveryAction()
	{

		$iterator = Sale\Delivery\Services\Table::getList([
			'filter' => ['ACTIVE' => 'Y', 'PARENT_ID' => 0],
			'order' => ['SORT' => 'ASC', 'ID' => 'ASC'],
		])->fetchAll();

		array_shift($iterator);

		$result = [];
		foreach ($iterator as $item) {
			$result[] = [
				'ID' => $item['ID'],
				'NAME' => $item['NAME'],
				'DESCRIPTION' => $item['DESCRIPTION'],
				'PRICE' => $item['CONFIG']['MAIN']['PRICE'],
			];
		}

		return $result;
	}

	public function searchLocationAction()
	{
		$q = $this->request->get('query');
		\CUtil::decodeURIComponent($q);

		$altQuery = false;
		$arLang = \CSearchLanguage::GuessLanguage($q);
		if (is_array($arLang) && $arLang["from"] != $arLang["to"])
			$altQuery = \CSearchLanguage::ConvertKeyboardLayout($q, $arLang["from"], $arLang["to"]);

		$q = $altQuery ? $altQuery : $q;

		$type = $this->request->get('type');
		switch ($type) {
			case 'city':
				$depthLevel = 3;
				$typeCode = 'CITY';
				break;
			default:
				$depthLevel = 2;
				$typeCode = 'REGION';
				break;
		}

		$filter = [
			'=DEPTH_LEVEL' => $depthLevel,
			'=TYPE.CODE' => $typeCode,
			'NAME.LANGUAGE_ID' => LANGUAGE_ID,
			'PHRASE' => $q,
		];

		if($type == 'city'){
			$filter['!=REGION_ID'] = null;
		}

		$result = [
			['value' => null, 'label' => 'Не найдено'],
		];
		$items = [];
		if (strlen($q) > 2){
			$oSearch = Sale\Location\Search\Finder::find([
				'select' => ['*', 'ID', 'CODE', 'NAME_TITLE' => 'NAME.NAME', 'REGION_ID'],
				'filter' => $filter,
				'limit' => 20,
			]);
			while ($res = $oSearch->fetch()) {
				$items[] = [
					'value' => $res['ID'],
					'label' => strlen($res['NAME_TITLE']) > 0 ? $res['NAME_TITLE'] : $res['NAME']
				];
			}
		}

		if (count($items) > 0)
			$result = $items;

		return $result;
	}

	public function saveOrderAction($data = [])
	{
		if ($this->isTestMode){
			$fileName = dirname(__FILE__).'/test.json';
			if (count($data) > 0){
				file_put_contents($fileName, Main\Web\Json::encode($data));
			} else {
				$data = Main\Web\Json::decode(file_get_contents($fileName));
			}
		}
		$arLocation = $this->prepareLocationOrder($data);

		$basket = Sale\Basket::loadItemsForFUser($this->FUser, $this->getSiteId());

		$userId = $this->authoriseUser($data);

		$order = Sale\Order::create($this->getSiteId(), $userId);
		$order->setPersonTypeId(Config::getPersonTypes('FIZ'));
		$order->setBasket($basket);

		$shipmentCollection = $order->getShipmentCollection();
		$shipment = $shipmentCollection->createItem(
			Sale\Delivery\Services\Manager::getObjectById($data['delivery'])
		);
		$shipmentItemCollection = $shipment->getShipmentItemCollection();

		foreach ($basket as $basketItem) {
			$item = $shipmentItemCollection->createItem($basketItem);
			$item->setQuantity($basketItem->getQuantity());
		}

		$paymentCollection = $order->getPaymentCollection();
		$payment = $paymentCollection->createItem(
			Sale\PaySystem\Manager::getObjectById($data['paySelected'])
		);
		$payment->setField("SUM", $order->getPrice());
		$payment->setField("CURRENCY", $order->getCurrency());

		$props = $order->getPropertyCollection();


		foreach ($arLocation['items'] as $item) {
			if($item['ID'] == $data['region']){
				$data['region'] = $item['TITLE'];
			}
			if($item['ID'] == $data['city']){
				$data['city'] = $item['TITLE'];
			}
		}

		/** @var Sale\PropertyValue $PropertyValue */
		foreach ($props as $PropertyValue) {
			$code = $PropertyValue->getField('CODE');
			if(array_key_exists($code, $data)){
				$PropertyValue->setValue($data[$code]);
			}

			if($code == 'ADDRESS'){
				$PropertyValue->setValue($arLocation['address']);
			}
			if($code == 'LOCATION'){
				$values = [];
				foreach ($arLocation['items'] as $value){
					$values[] = $value['ID'];
				}
				$PropertyValue->setValue($values);
			}
		}
		$order->doFinalAction();

		$result = $order->save();
		if (!$result->isSuccess()){
			throw new \Exception(implode(', ', $result->getErrorMessages()), 503);
		}

		return $result->getId();
	}

	protected function prepareLocationOrder($data = [])
	{
		$arLocation = $titles = [];
		$address = null;
		if (strlen($data['region']) > 0 && strlen($data['city']) > 0){
			$oLocation = Sale\Location\LocationTable::getList([
				'select' => ['ID', 'TITLE' => 'NAME.NAME'],
				'filter' => [
					'@ID' => [$data['region'], $data['city']],
				],
			]);
			while ($location = $oLocation->fetch()) {
				$arLocation[] = $location;
				$titles[] = $location['TITLE'];
			}

			$titles[] = $data['street'];
			$titles[] = $data['house'];
			$titles[] = $data['apartment'];
			$address = implode(' ', $titles);
		}

		return ['items' => $arLocation, 'address' => $address];
	}

	protected function authoriseUser($data = [])
	{
		$userId = null;
		if ($this->getUser()->IsAuthorized()){
			$userId = $this->getUser()->GetID();
		} else {
			$CUser = new \CUser();

		}

		return $userId;
	}

	public function getPaymentsAction($data = [])
	{
		if ($this->isTestMode){
			$fileName = dirname(__FILE__).'/test.json';
			if (count($data) > 0){
				file_put_contents($fileName, Main\Web\Json::encode($data));
			} else {
				$data = Main\Web\Json::decode(file_get_contents($fileName));
			}
		}

		$paysIds = [];

		$oPayRestrictions = Sale\Internals\DeliveryPaySystemTable::getList([
			'filter' => ['DELIVERY_ID' => 2]
		]);
		while ($pay = $oPayRestrictions->fetch()){
			$paysIds[] = $pay['PAYSYSTEM_ID'];
		}
		$paysIds = array_unique($paysIds);

		$pays = Sale\PaySystem\Manager::getList([
			'select' => ['ID', 'PSA_NAME', 'DESCRIPTION'],
			'filter' => ['ID' => $paysIds]
		])->fetchAll();


		return $pays;
	}

	/**
	 * @method getStreetAction
	 * @param array $data
	 *
	 * @return array|null
	 */
	public function getStreetAction($data = [])
	{
		$dadata = new \Dresscode\Main\Dadata();
		$result = ['value' => null, 'label' => 'Не найдено'];
		if(strlen($data['query']) > 2){
			$resItems = Main\Web\Json::decode($dadata->getStreetByCity($data['query'], $data['city']));
			if(count($resItems) > 0){
				$result = [];
			}

			foreach ($resItems['suggestions'] as $item) {
				$result[] = [
					'label' => $item['value'],
					'value' => $item['data']['kladr_id']
				];
			}
		}

		return $result;
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