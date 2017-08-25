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
//PR($arResult['SECTION']['ID']);
?>
<? if (is_array($arResult['SECTION']['BANNER'])): ?>
	<!--begin banner-girl-->
	<div class="banner-girl" style="background-image: url(<?=$arResult['SECTION']['BANNER']['DESKTOP']['SRC']?>)">
		<div class="row">
			<div class="banner-girl__txt"><?=$arResult['SECTION']['UF_BANNER_TEXT']?></div>
			<div class="banner-girl__btn"><a class="btn_black" href="<?=$arResult['SECTION']['DETAIL_PAGE_URL']?>">Выбрать
					и купить</a></div>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
<? endif; ?>

<? if (count($arResult['SUB_SECTIONS']['MAIN']) > 0): ?>
	<!--begin big-box-girl-->
	<div class="container">
		<div class="big-box-girl">
			<? foreach ($arResult['SUB_SECTIONS']['MAIN'] as $k => $sectionMain): ?>
				<div class="big-box-girl__items-wr">
					<a href="<?=$sectionMain['DETAIL_PAGE_URL']?>">
						<div class="big-box-girl__items" style="background-image: url(<?=$sectionMain['BANNER']['DESKTOP']['SRC']?>)"></div>
						<div class="big-box-girl__desc">
							<?=$sectionMain['UF_BANNER_TEXT']?>
							<span><?=$sectionMain['UF_BANNER_DESC']?></span>
						</div>
					</a>
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
<? endif; ?>

<? if (count($arResult['SUB_SECTIONS']['OTHER']) > 0): ?>
	<!--begin small-box-man-->
	<div class="container">
		<div class="small-box-girl">
			<? foreach ($arResult['SUB_SECTIONS']['OTHER'] as $subSection): ?>
				<div class="small-box-girl__items">
					<a href="<?=$subSection['DETAIL_PAGE_URL']?>">
						<div class="small-box-girl__items-img" style="background-image: url(<?=$subSection['BANNER']['DESKTOP']['SRC']?>)"></div>
						<div class="small-box-girl__items-txt"><?=$subSection['UF_BANNER_TEXT']?><span><?=$subSection['NAME']?></span></div>
					</a>
				</div>
			<? endforeach; ?>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
<? endif; ?>

<?$APPLICATION->IncludeComponent(
	"ab:product.list",
	"caroucel",
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "N",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => $arResult['ELEMENT_PATH'],
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"INCLUDE_SUBSECTIONS" => "A",
		"LINE_ELEMENT_COUNT" => "3",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "15",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PROPERTY_CODE" => array(
			0 => "SIZE",
			1 => "",
		),
		"SECTION_CODE" => "",
		"SECTION_ID" => $arResult['SECTION']['ID'],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => $arResult['SECTION_PATH'],
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "caroucel",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:2:6\",\"DATA\":{\"logic\":\"Equal\",\"value\":1}}]}",
		"NAME_BLOCK" => "Новинки",
		"PAGE_NAVIGATION_ID" => "page",
		"PREVIEW_IMG_WIDTH" => "330",
		"PREVIEW_IMG_HEIGHT" => "450",
		"DETAIL_IMG_WIDTH" => "",
		"DETAIL_IMG_HEIGHT" => ""
	),
	$component
); ?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"one_banner",
	Array(
		"AREA_FILE_SHOW"      => "file",     // Показывать включаемую область
		"PATH"    => "/catalog/sect_".$arResult['RAW_SECTION_CODE'].'/banner.php',      // Суффикс имени файла включаемой области
		"EDIT_TEMPLATE"       => "",         // Шаблон области по умолчанию
	)
);?>

<?$APPLICATION->IncludeComponent(
	"ab:product.list",
	"caroucel",
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "N",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => $arResult['ELEMENT_PATH'],
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
		"INCLUDE_SUBSECTIONS" => "A",
		"LINE_ELEMENT_COUNT" => "3",
		"MESSAGE_404" => "",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "5",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "15",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PROPERTY_CODE" => array(
			0 => "SIZE",
			1 => "",
		),
		"SECTION_CODE" => "",
		"SECTION_ID" => $arResult['SECTION']['ID'],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => $arResult['SECTION_PATH'],
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "caroucel",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:2:7\",\"DATA\":{\"logic\":\"Equal\",\"value\":1}}]}",
		"NAME_BLOCK" => "Популярные товары",
		"PAGE_NAVIGATION_ID" => "page",
		"PREVIEW_IMG_WIDTH" => "330",
		"PREVIEW_IMG_HEIGHT" => "450",
		"DETAIL_IMG_WIDTH" => "",
		"DETAIL_IMG_HEIGHT" => ""
	),
	$component
);?>