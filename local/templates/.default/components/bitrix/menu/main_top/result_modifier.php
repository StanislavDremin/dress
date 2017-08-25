<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
\Bitrix\Main\Loader::includeModule('iblock');
use AB\Tools\Helpers\DataCache;
use Dresscode\Main\Config;

$config = Config::instance();

$resultItems = $arResult;
$sections = [];
/*$cache = new DataCache(86400, '/dress/menu', Config::TOP_MENU_CACHE);
if($cache->isValid()){
	$sections = $cache->getData();
} else {
	$oItems = CIBlockSection::GetList(
		array("MARGIN_LEFT" => "ASC"),
		array(
			'IBLOCK_ID' => $config->getIblock('CATALOG'),
			'ACTIVE' => 'Y',
			'<=DEPTH_LEVEL' => 1,
		),
		false,
		array(
			'ID', 'NAME', 'IBLOCK_ID', 'SECTION_PAGE_URL'
		)
	);
	while ($sect = $oItems->GetNext(true, false)){
		$sections[] = $sect;
	}

	$cache->addCache($sections);
}

foreach ($sections as &$section) {
	if($config->currentDir() !== '' && preg_match('#'.$config->currentDir().'#i', $section['SECTION_PAGE_URL'])){
		$section['IS_ACTIVE'] = true;
	}
}*/

$arResult = [
	'CATALOG_SECTIONS' => $sections,
	'ITEMS' => $resultItems
];
unset($sections, $resultItems);