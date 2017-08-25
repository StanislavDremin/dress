<?php namespace Derss\Catalog;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Tools\Debug;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Sale;

Main\Loader::includeModule('sale');

Loc::loadLanguageFile(__FILE__);

class BasketComponent extends \CBitrixComponent
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
		$siteId = Main\Context::getCurrent()->getSite();

		unset($_SESSION['DRESS_BASKET_QUANTITY']);
		if(!isset($_SESSION['DRESS_BASKET_QUANTITY'])){
			$this->arResult['CNT'] = Sale\Internals\BasketTable::getCount([
				'LID' => $siteId,
				'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
				'ORDER_ID' => false
			]);
		} else {
			$this->arResult['CNT'] = count($_SESSION['DRESS_BASKET_QUANTITY'][$siteId]);
		}

		$this->includeComponentTemplate();
	}
}