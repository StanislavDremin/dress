<?php namespace Esd\HL\Type;
	/** @var \CBitrixComponent $this */
	/** @var array $arParams */
	/** @var array $arResult */
	/** @var string $componentPath */
	/** @var string $componentName */
	/** @var string $componentTemplate */
	/** @var \CBitrixComponent $component */
	/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Entity;
use \Bitrix\Main\Loader;

Loc::loadLanguageFile(__FILE__);

class AdminFieldEdit extends \CBitrixComponent
{
	/** @var array|bool|\CDBResult|\CUser|mixed */
	protected $USER;
	/** @var \Bitrix\Main\Application|\Bitrix\Main\HttpApplication|\CMain */
	protected $APP;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		global $USER, $APPLICATION;
		$this->APP = $APPLICATION;
		$this->USER = $USER;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		if($this->arParams['FIELD']['EDIT_IN_LIST'] == 'N'){
			$this->includeComponentTemplate('', '/local/components/hl/admin.field.edit/templates/no_edit');
		} else {
			$this->includeComponentTemplate();
		}
	}
}