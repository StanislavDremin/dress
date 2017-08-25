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
\Bitrix\Main\Loader::includeModule('iblock');
use AB\Tools\Helpers\DataCache;
use Dresscode\Main\Config;

$config = Config::instance();

$resultItems = $arResult;

if(!function_exists('__sectionSort')){
	function __sectionSort($a, $b)
	{
		if ($a['SORT'] == $b['SORT']) {
			return 0;
		}
		return ($a['SORT'] < $b['SORT']) ? -1 : 1;
	}
}
$listItems = $levels = [];

$cache = new DataCache(86400, '/dress/menu', Config::LEFT_MENU_CACHE);
if ($cache->isValid()){
	$listItems = $cache->getData();
} else {
	$oItems = CIBlockSection::GetList(
		array("DEPTH_LEVEL" => "DESC"),
		array(
			'IBLOCK_ID' => $config->getIblock('CATALOG'),
			'ACTIVE' => 'Y','GLOBAL_ACTIVE' => 'Y',
			'<=DEPTH_LEVEL' => $arParams['MAX_LEVEL'],
		),
		false,
		array(
			'ID', 'NAME', 'IBLOCK_ID', 'SECTION_PAGE_URL', 'IBLOCK_SECTION_ID', 'SORT',
		)
	);

	while ($sect = $oItems->GetNext(true, false)) {
		$listItems[$sect['ID']] = $sect;
//		$levels[] = $sect['DEPTH_LEVEL'];
	}
//	$levels = array_unique($levels);
//	rsort($levels);
//	$maxLevel = $levels[0];
	$maxLevel = $arParams['MAX_LEVEL'];
	for ($i = $maxLevel; $i > 1; $i--) {
		foreach ($listItems as $sId => $value) {
			if($value['DEPTH_LEVEL'] == $i){
				$listItems[$value['IBLOCK_SECTION_ID']]['SUB_SECTION'][] = $value;
				unset($listItems[$sId]);
			}
		}
	}
	usort($listItems, "__sectionSort");

	$cache->addCache($listItems);
}

foreach ($listItems as &$section) {
	if ($config->currentDir() !== '' && preg_match('#'.$config->currentDir().'#i', $section['SECTION_PAGE_URL'])){
		$section['IS_ACTIVE'] = true;
	}
}
$arResult = [
	'CATALOG_SECTIONS' => $listItems,
	'ITEMS' => $resultItems,
];
unset($sections, $resultItems);