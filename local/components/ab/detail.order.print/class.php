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
use Online1c\Iblock;

Loc::loadLanguageFile(__FILE__);
Main\Loader::includeModule('sale');
Main\Loader::includeModule('online1c.iblock');

class OrderPrintComponent extends \CBitrixComponent
{
	/** @var  PropertyDictionary */
	protected $props;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->props = new PropertyDictionary();
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		$arParams['ORDER_ID'] = (int)$arParams['ORDER_ID'];

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

	private function preparePropertyCollection(Sale\PropertyValueCollection $collection)
	{
		/** @var Sale\PropertyValue $item */
		foreach ($collection as $item) {
			$this->props->add($item->getField('CODE'), $item);
		}
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		if ($this->arParams['ORDER_ID'] > 0){
			$order = Sale\Order::load($this->arParams['ORDER_ID']);
			$this->preparePropertyCollection($order->getPropertyCollection());

			$profile = [
				'fio' => $this->props->getProperty('fio')->getValue(),
				'email' => $this->props->getProperty('email')->getValue(),
				'phone' => $this->props->getProperty('phone')->getValue(),
				'region' => $this->props->getProperty('region')->getValue(),
				'city' => $this->props->getProperty('city')->getValue(),
				'ADDRESS' => $this->props->getProperty('ADDRESS')->getValue(),
			];

			$this->arResult['PROFILE'] = $profile;

			$deliveryIds = array_shift(array_unique($order->getDeliverySystemId()));
			$paymentIds = array_unique($order->getPaymentSystemId());

			$this->arResult['DELIVERY'] = Sale\Delivery\Services\Table::getRowById($deliveryIds);
			$this->arResult['PAYMENTS'] = Sale\Internals\PaymentTable::getList([
				'filter' => ['=ID' => $paymentIds],
			])->fetchAll();
			$this->arResult['ORDER_FIELDS'] = $order->getFieldValues();

			$this->arResult['ORDER_FIELDS']['STATUS_NAME'] = Sale\Internals\StatusLangTable::getRow([
				'select' => ['NAME'],
				'filter' => ['=STATUS_ID' => $order->getField('STATUS_ID')],
			]);

			/** @var Sale\BasketItem $arBasketItem */
			foreach ($order->getBasket() as $arBasketItem) {
				$basketItem = $arBasketItem->getFieldValues();
				$basketItem['PROPS'] = $arBasketItem->getPropertyCollection()->getPropertyValues();

				$product = Iblock\Element::getRow([
					'select' => [
						'ID', 'NAME', 'IBLOCK_ID', 'DETAIL_PICTURE',
					],
					'filter' => [
						'=ID' => $arBasketItem->getField('PRODUCT_ID'),
						'IBLOCK_ID' => Config::getIblock('CATALOG'),
					],
				]);

				if ((int)$product['DETAIL_PICTURE'] > 0){
					$product['IMG'] = \CFile::ResizeImageGet(
						$product['DETAIL_PICTURE'],
						['width' => 110, 'height' => 180],
						BX_RESIZE_IMAGE_PROPORTIONAL_ALT
					);
				}

				$basketItem['PRODUCT'] = $product;

				$this->arResult['BASKET'][] = $basketItem;
			}
		}

		$this->includeComponentTemplate();
	}
}

class PropertyDictionary extends Main\Type\Dictionary
{
	/**
	 * @method add
	 * @param $code
	 * @param Sale\PropertyValue $propertyValue
	 */
	public function add($code, Sale\PropertyValue $propertyValue)
	{
		parent::offsetSet($code, $propertyValue);
	}

	/**
	 * @method getProperty
	 * @param $name
	 *
	 * @return Sale\PropertyValue
	 * @throws \Exception
	 */
	public function getProperty($name)
	{
		if (isset($this->values[$name]) || array_key_exists($name, $this->values)){
			return $this->values[$name];
		}

		throw new \Exception('Property '.$name.' is not exist');
	}
}