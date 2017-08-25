<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
//dump($arParams);
?>
<? $APPLICATION->IncludeComponent(
	'ab:catalog.detail',
	'',
	array(
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'ELEMENT_CODE' => $arResult['ELEMENT_CODE'],
		'SECTION_DATA' => $arResult['SECTION'],
		'UF_SECTION_ID' => $arResult['UF_SECTION_ID'],
		'SECTION_PATH' => $arResult['SECTION_PATH'],
		'SECTION_CODE' => $arResult['SECTION_CODE'],
		'DETAIL_PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
		'SAVE_VIEWED_PRODUCTS' => 'Y'
	),
	$component,
	['HIDE_ICONS' => 'Y']
); ?>