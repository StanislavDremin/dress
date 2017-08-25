<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * Created by OOO 1C-SOFT.
 * User: GrandMaster
 * Date: 25.07.17
 */
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
$this->addExternalCss($templateFolder.'/script.css');
//dump($arResult);
?>
<div class="container">
	<div class="breadcrumbs">
		<?// Навигационная цепочка - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/navigation/breadcrumb.php
		$APPLICATION->IncludeComponent(
			"bitrix:breadcrumb",
			".default",
			Array(
				"START_FROM"    => "0",     // Номер пункта, начиная с которого будет построена навигационная цепочка
				"PATH"          => "",      // Путь, для которого будет построена навигационная цепочка (по умолчанию, текущий путь)
				"SITE_ID"       => "-",     // Cайт (устанавливается в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
			)
		);?>
	</div>
</div>
<?$APPLICATION->IncludeComponent(
	"ab:product.sections",
	"",
	Array(
		"ADD_SECTIONS_CHAIN" => "Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COUNT_ELEMENTS" => "N",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"SECTION_CODE" => "",
		"SECTION_FIELDS" => array("",""),
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_URL" => $arResult["SECTION_PATH"],
		"SECTION_USER_FIELDS" => array("",""),
		"TOP_DEPTH" => "3",
//		"SECTION_DATA" => $arResult["SECTION"]
	),
	$component
);?>
<?$APPLICATION->IncludeComponent(
	'ab:smart.filter',
	'',
	array(
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		'SECTION_DATA' => $arResult['SECTION'],
		'SECTION_URL' => $arResult["SECTION_PATH"],
		"DETAIL_URL" => $arResult['ELEMENT_PATH'],
		"SECTION_ID" => $arResult['SECTION']['ID'],
		"FILTER_NAME" => 'SmartFilter',
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
		"SAVE_IN_SESSION" => "N",
		"FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
		"XML_EXPORT" => "Y",
		"SECTION_TITLE" => "NAME",
		"SECTION_DESCRIPTION" => "DESCRIPTION",
		'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
		"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
		'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
		'CURRENCY_ID' => $arParams['CURRENCY_ID'],
		"SEF_MODE" => 'N',
		"SEF_RULE" => '',
		"SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
		"PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
		"INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
	),
	$component
)?>
<!--begin container-->
<div class="container container-inner" id="app_catalog">
	<!--begin sidebar-->
	<div class="sidebar">
		<sidebar-sections></sidebar-sections>
		<div class="tags">
			<div class="tags__title">Популярные теги</div>
			<a href="#">Модные новинки</a>
			<a href="#">Зимняя одежда</a>
			<a href="#">Классические туфли</a>
			<a href="#">Сумки</a>
			<a href="#">Кожаные юбки</a>
			<a href="#">Вечерние платья</a>
			<a href="#">Туфли на шпильке</a>
			<a href="#">Яркие футболки</a>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->

	<!--begin content-->
	<div class="content">
		<div class="catalog-list-head">
			<div class="catalog-list-head__title">
				<?=$arResult['SECTION']['NAME']?>
				<?$APPLICATION->ShowViewContent('count_products');?>
			</div>
			<div class="catalog-list-head__sort"> Сортировать:
				<div id="selectSorting" class="select1" v-cloak>
					<el-select v-model="sortingValue" placeholder="по популярности">
						<el-option v-for="item in sorting" :key="item.value" :label="item.label" :value="item.value"></el-option>
					</el-select>
				</div>
			</div>
		</div>
		<div class="catalog-list-filter">
			<span class="catalog-list-filter__title">Фильтр:</span>
			<div id="main_filter" v-cloak=""></div>
		</div>
		<?$APPLICATION->IncludeComponent(
			"ab:product.list",
			"",
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
				"CACHE_TIME" => $arParams['CACHE_TIME'],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
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
				"PROPERTY_CODE" => $arParams['LIST_PROPERTY_CODE'],
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
				"COMPONENT_TEMPLATE" => ".default",
				"PAGE_NAVIGATION_ID" => $arParams['PAGE_NAVIGATION_ID'],
				"PREVIEW_IMG_WIDTH" => "330",
				"PREVIEW_IMG_HEIGHT" => "450",
				"DETAIL_IMG_WIDTH" => "",
				"DETAIL_IMG_HEIGHT" => "",
				"SECTION_DATA" => $arResult["SECTION"]
			),
			$component
		); ?>

	</div>
	<!--¯\_(ツ)_/¯ end content-->
</div>
<!--¯\_(ツ)_/¯ container-->
<div class="container">
	<div class="description_section">
		<?=$arResult['SECTION']['DESCRIPTION']?>
	</div>
</div>