<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
global $USER, $APPLICATION;
$arHtmlControl = $arParams['HTML_CTRL'];
$default_value = intVal($arHtmlControl["VALUE"]);
$res = "";
if ($default_value == $USER->GetID()) {
	$arResult['USER'] = array(
		'ID'=>$USER->GetID(),
		'LOGIN'=>$USER->GetLogin(),
		'NAME'=>$USER->GetFirstName(),
		'LAST_NAME'=>$USER->GetLastName()
	);
	$arResult['SELECT'] = "CU";

}elseif ($default_value > 0) {
	$arResult['SELECT'] = "SU";
	$by = 'ID';
	$order = 'DESC';
	$rsUsers = \CUser::GetList($by, $order, array("ID" => $default_value));
	if ($arUser = $rsUsers->Fetch()){
		$arResult['USER'] = $res;
	} else {
		$arResult['USER'] = GetMessage("MAIN_NOT_FOUND");
	}
}else {
	$arResult['SELECT'] = "none";
	$default_value = "";
}
$arResult['DEFAULT_VALUE'] = $default_value;

$arResult['NAME_X'] = preg_replace("/([^a-z0-9])/is", "x", $arHtmlControl["NAME"]);
preg_match('#.*(\d+)$#i', $arParams['FIELD']['ENTITY_ID'], $matchId);
$arResult['FORM_NAME'] = 'hlrow_edit_'.$matchId[1].'_form';