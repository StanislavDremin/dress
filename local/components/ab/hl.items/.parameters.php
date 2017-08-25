<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arCurrentValues */

if (!CModule::IncludeModule("highloadblock"))
	return;

CBitrixComponent::includeComponentClass('ab:hl.items');

use Bitrix\Highloadblock;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Json;

global $USER_FIELD_MANAGER;

Loc::loadMessages(__FILE__);

$obTypesEx = Highloadblock\HighloadBlockTable::getList([
	'select' => ['*', 'TITLE' => 'LANG.NAME'],
]);
$listTypeParam = ['-' => 'Выберите блок'];
while ($type = $obTypesEx->fetch()) {
	$arTypesEx[$type['ID']] = $type;
	$listTypeParam[$type['ID']] = strlen($type['TITLE']) > 0 ? $type['TITLE'] : $type['NAME'];
}

$scalarTypes = ['boolean', 'string', 'integer', 'int', 'IBlockElementRef'];
$properties = $scalarProperties = [];
$mainListBlock = null;
if ((int)$arCurrentValues['BLOCK_TYPE'] > 0){
	$mainListBlock = $arTypesEx[$arCurrentValues['BLOCK_TYPE']];
	if ($mainListBlock){
		$fields = $USER_FIELD_MANAGER->GetUserFields('HLBLOCK_'.$mainListBlock['ID'], 0, LANGUAGE_ID);
		foreach ($fields as $code => $arField) {
			$title = $arField['LIST_COLUMN_LABEL'] ? $arField['LIST_COLUMN_LABEL'] : $code;
			$properties[$code] = $title;
			if(in_array($arField['USER_TYPE']['BASE_TYPE'], $scalarTypes)){
				$scalarProperties[$code] = $title;
			}
		}
	}
}

$arSorts = array(
	"ASC" => Loc::getMessage('AB_HL_LIST_PARAM.SORT_ASC'),
	"DESC" => Loc::getMessage('AB_HL_LIST_PARAM.SORT_DESC'),
);
/*
$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["BLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

$arSorts = array("ASC"=>GetMessage("T_IBLOCK_DESC_ASC"), "DESC"=>GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
		"ID"=>GetMessage("T_IBLOCK_DESC_FID"),
		"NAME"=>GetMessage("T_IBLOCK_DESC_FNAME"),
		"ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
		"SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
		"TIMESTAMP_X"=>GetMessage("T_IBLOCK_DESC_FTSAMP")
	);

$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>(isset($arCurrentValues["IBLOCK_ID"])?$arCurrentValues["IBLOCK_ID"]:$arCurrentValues["ID"])));
while ($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	if (in_array($arr["PROPERTY_TYPE"], array("L", "N", "S")))
	{
		$arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
	}
}*/

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),
		"BLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.BLOCK_TYPE'),
			"TYPE" => "LIST",
			"VALUES" => $listTypeParam,
			"DEFAULT" => "",
			"ADDITIONAL_VALUES" => "Y",
			"REFRESH" => "Y",
		),
		"ITEMS_COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.ITEMS_COUNT'),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		),
		"SORT_BY1" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.SORT_BY1'),
			"TYPE" => "LIST",
			"VALUES" => $scalarProperties,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER1" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.SORT_ORDER1'),
			"TYPE" => "LIST",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_BY2" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.SORT_BY2'),
			"TYPE" => "LIST",
			"VALUES" => $scalarProperties,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER2" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.SORT_ORDER2'),
			"TYPE" => "LIST",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"PROPERTY_CODE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.PROPERTY_CODE'),
			"TYPE" => "LIST",
			"MULTIPLE" => "Y",
			"VALUES" => $properties,
			"ADDITIONAL_VALUES" => "Y",
		),
		"PREVIEW_TRUNCATE_LEN" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.PREVIEW_TRUNCATE_LEN'),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"PAGE_NAVIGATION_ID" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.PAGE_NAVIGATION_ID'),
			"TYPE" => "STRING",
			"DEFAULT" => "page",
		),
		"GUIDE_ID" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => Loc::getMessage('AB_HL_LIST_PARAM.GUIDE_ID'),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"CACHE_TIME" => array("DEFAULT" => 36000000),
	),
);
$filterDataValues = [];
//if (!empty($filterDataValues))
//{
	/*$arComponentParameters['PARAMETERS']['CUSTOM_FILTER'] = array(
		'NAME' => Loc::getMessage('AB_HL_LIST_PARAM.CUSTOM_FILTER'),
		'TYPE' => 'CUSTOM',
		'JS_FILE' => \AB\Highload\Items::getSettingsScript($componentPath, 'filter_conditions'),
		'JS_EVENT' => 'initFilterConditionsControl',
		'JS_MESSAGES' => Json::encode(array(
			'invalid' => GetMessage('CP_BCS_TPL_SETTINGS_INVALID_CONDITION')
		)),
		'JS_DATA' => Json::encode($filterDataValues),
		'DEFAULT' => '',
		'PROPERTIES' => $properties
	);*/
//}

/*CIBlockParameters::AddPagerSettings(
	$arComponentParameters,
	'Страницы', //$pager_title
	true, //$bDescNumbering
	true, //$bShowAllParam
	true, //$bBaseLink
	$arCurrentValues["PAGER_BASE_LINK_ENABLE"]==="Y" //$bBaseLinkEnabled
);*/