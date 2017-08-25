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
$this->addExternalCss($templateFolder.'/script.css');
//PR($arResult);
?>
<!--begin catalog-element-->
<!--begin breadcrumbs-->
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
<!--¯\_(ツ)_/¯ end breadcrumbs-->
<!--begin container container-inner-->
<div id="catalog-element">
	<div class="container container-inner l-r-wr">
		<!--begin l-element-->
		<div class="l-element">
			<!--begin el-el-tags-mob-->
			<? if ($arResult['COUNT_TAGS'] > 0): ?>
				<div class="el-tags el-tags-mob">
					<div>
						<? foreach ($arResult['TAG_ITEMS'] as $tag) { ?>
							<span class="el-tag el-tag-pink"><?=$tag?></span>
						<? } ?>
					</div>
				</div>
			<? endif; ?>
			<!--¯\_(ツ)_/¯ end el-el-tags-mob-->
			<!--begin title-mobile-->
			<div class="el-titles el-titles-mob">
				<?if(intval($arResult['NEWPRODUCT']) > 0):?>
					<span class="el-titles__lable el-titles-mob__lable" style="background-image: url(/local/dist/img/icons/icons-lable-new.png)"></span>
				<?endif;?>
				<span class="el-titles__title el-titles-mob__title"><?=$arResult['NAME']?></span>
			</div>
			<!--¯\_(ツ)_/¯ end title-mobile-->
			<!--begin -->
			<div class="el-art-brand el-art-brand-mob">
				<div class="el-art-brand__articul el-art-brand-mob__articul">Артикул: <?=$arResult['ARTNUMBER']?></div>
				<div class="el-art-brand__brands el-art-brand-mob__brands">
					Все товары бренда:&nbsp;&nbsp;
					<a href="/brands/<?=$arResult['BRAND']['CODE']?>/"><?=$arResult['BRAND']['ITEM']['UF_NAME']?></a>
				</div>
			</div>
			<!--¯\_(ツ)_/¯ end -->
			<!--begin slider-element-->
			<div class="slider-element">
				<div id="sync1" class="owl-carousel owl-theme">
					<? foreach ($arResult['SLIDER_PHOTO'] as $photo) { ?>
						<div data-src="<?=$photo['src']?>" class="item" style="background-image: url(<?=$photo['src']?>)"></div>
					<? } ?>
				</div>
				<div id="sync2" class="owl-carousel owl-theme">
					<? foreach ($arResult['SLIDER_PHOTO'] as $photo) { ?>
						<img class="item" src="<?=$photo['SMALL']['src']?>" data-src="<?=$photo['SMALL']['src']?>" />
					<? } ?>
				</div>
			</div>
			<!--¯\_(ツ)_/¯ end slider-element-->
			<? if ($arResult['COUNT_TAGS'] > 0): ?>
				<div class="app-tads">
					<span class="title">Теги</span>
					<div>
						<? foreach ($arResult['TAG_ITEMS'] as $tag) { ?>
							<span class="el-tag el-tag--gray"><?=$tag?></span>
						<? } ?>
					</div>
				</div>
			<? endif; ?>
		</div>
		<!--¯\_(ツ)_/¯ end l-element-->
		<!--begin r-element-->
		<div class="r-element">
			<!--begin sidebar-element-->
			<div class="sidebar-element">
				<div class="el-tags">
				</div>
				<div class="el-titles">
					<?if(intval($arResult['NEWPRODUCT']) > 0):?>
						<span class="el-titles__lable" style="background-image: url(/local/dist/img/icons/icons-lable-new.png)"></span>
					<?endif;?>
					<span class="el-titles__title"><?=$arResult['NAME']?></span>
				</div>
				<div class="el-art-brand">
					<div class="el-art-brand__articul">Артикул: <?=$arResult['ARTNUMBER']?></div>
					<div class="el-art-brand__brands">
						Все товары бренда:&nbsp;&nbsp;
						<a href="/brands/<?=$arResult['BRAND']['CODE']?>/"><?=$arResult['BRAND']['ITEM']['UF_NAME']?></a>
					</div>
				</div>
				<div class="el-prises">
					<div class="el-prises__prises">цена:&nbsp;<span class="prise"><?=$arResult['PRICE_FORMAT']?></span>
						<!--<span class="sale-prise">27 999 Р</span> <span class="sale">-45%</span> -->
					</div>
					<div class="shop">
						<a href="javascript:">
							<?=($arResult['QUANTITY'] > 0 ? 'Товар в наличии' : 'Нет в наличии')?>
						</a>
					</div>
				</div>
				<!--begin выбор размера-->
				<div>
					<div class="sidebar-element__el-sizes-desc">
						<div class="sizes-hero">
							<span>Российский размер</span>
							<a href="javascript:">Как узнать свой размер?</a>
						</div>
						<div class="sizes-list">
							<? foreach ($arResult['SIZE']['VALUE'] as $value) { ?>
								<span class="size" data-size="<?=$value?>" @click="checkSize"><?=$value?></span>
							<? } ?>
						</div>
					</div>
				</div>
				<!--¯\_(ツ)_/¯ end выбор размера-->
				<?if(count($arResult['COLOR']['ITEMS']) > 0):?>
					<div class="sidebar-element__el-colors">
						<span class="title">Цвет</span>
						<? foreach ($arResult['COLOR']['ITEMS'] as $item) {
							if ($item['IMG']){?>
								<span class="color" data-color="<?=$item['UF_XML_ID']?>" @click="checkColor"
										style="background-image: url(<?=$item['IMG']['SRC']?>)">
								</span>
							<?}
						} ?>
					</div>
				<?endif;?>
				<div class="sidebar-element__el-btn">
					<a class="btn_pink" href="javascript:" @click.prevent="addToCart(<?=$arResult['ID']?>)">Добавить в корзину</a>
					<a class="btn_heart" href="javascript:" @click.prevent="addToFavorite(<?=$arResult['ID']?>)">
						<i class="fa fa-heart" aria-hidden="true"></i>
						<span>В избранное</span>
					</a>
				</div>
				<?if(strlen($arResult['PREVIEW_TEXT']) > 0):?>
					<!--begin Описание-->
					<div class="sidebar-element__el-descs">
						<?=$arResult['PREVIEW_TEXT']?>
					</div>
					<!--¯\_(ツ)_/¯ end Описание-->
				<?endif?>
				<?if(strlen($arResult['DETAIL_TEXT']) > 0):?>
					<!--begin Характеристики-->
					<div class="sidebar-element__el-attributes">
						<?=$arResult['DETAIL_TEXT']?>
					</div>
					<!--¯\_(ツ)_/¯ end Характеристики-->
				<?endif?>
			</div>
			<!--¯\_(ツ)_/¯ end sidebar-element-->
		</div>
		<!--¯\_(ツ)_/¯ end r-element-->
	</div>
</div>
<!--¯\_(ツ)_/¯ end container container-inner-->
<!--begin el-caruselka-->
<div class="el-caruselka">
	<? $APPLICATION->IncludeComponent(
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
			"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
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
			"IBLOCK_ID" => "2",
			"IBLOCK_TYPE" => "catalog",
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
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PROPERTY_CODE" => array(
				0 => "SIZE",
				1 => "",
			),
			"SECTION_CODE" => "",
			"SECTION_ID" => 19,
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "/catalog/#SECTION_CODE#/",
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
			"CUSTOM_FILTER" => "",
			"NAME_BLOCK" => "С этим товаром так же покупают",
			"PAGE_NAVIGATION_ID" => "page",
			"PREVIEW_IMG_WIDTH" => "330",
			"PREVIEW_IMG_HEIGHT" => "450",
			"DETAIL_IMG_WIDTH" => "",
			"DETAIL_IMG_HEIGHT" => "",
		),
		false
	); ?>
	<? $APPLICATION->IncludeComponent(
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
			"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
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
			"IBLOCK_ID" => "2",
			"IBLOCK_TYPE" => "catalog",
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
			"PRODUCT_PROPERTIES" => array(),
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PROPERTY_CODE" => array(
				0 => "SIZE",
				1 => "",
			),
			"SECTION_CODE" => "",
			"SECTION_ID" => $arParams['SECTION_DATA']['ID'],
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"SECTION_URL" => "/catalog/#SECTION_CODE#/",
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
			"CUSTOM_FILTER" => "",
			"NAME_BLOCK" => "Похожие товары",
			"PAGE_NAVIGATION_ID" => "page",
			"PREVIEW_IMG_WIDTH" => "330",
			"PREVIEW_IMG_HEIGHT" => "450",
			"DETAIL_IMG_WIDTH" => "",
			"DETAIL_IMG_HEIGHT" => "",
		),
		false
	); ?>
</div>
<!--¯\_(ツ)_/¯ end el-caruselka-->
<!--begin el-tabs-->
<div id="element_tabs">
	<elements-tabs :product="<?=$arResult['ID']?>"></elements-tabs>
</div>
<!--¯\_(ツ)_/¯ end el-tabs-->
<!--begin el-caruselka-->
<div class="el-caruselka">
	<?
	if(count($arResult['VIEWED_PRODUCTS']) > 0){
		\Dresscode\Main\ModifierComponentsFilter::getInstance()->add('filterViewProducts', [
			'@ID' => $arResult['VIEWED_PRODUCTS']
		]);

		$APPLICATION->IncludeComponent(
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
				"CACHE_TIME" => "3600",
				"CACHE_TYPE" => "N",
				"COMPATIBLE_MODE" => "N",
				"CONVERT_CURRENCY" => "N",
				"DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
				"DISABLE_INIT_JS_IN_COMPONENT" => "N",
				"DISPLAY_BOTTOM_PAGER" => "N",
				"DISPLAY_COMPARE" => "N",
				"DISPLAY_TOP_PAGER" => "N",
				"ELEMENT_SORT_FIELD" => "sort",
				"ELEMENT_SORT_FIELD2" => "id",
				"ELEMENT_SORT_ORDER" => "asc",
				"ELEMENT_SORT_ORDER2" => "desc",
				"FILTER_NAME" => "filterViewProducts",
				"HIDE_NOT_AVAILABLE" => "N",
				"HIDE_NOT_AVAILABLE_OFFERS" => "N",
				"IBLOCK_ID" => "2",
				"IBLOCK_TYPE" => "catalog",
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
				"PRODUCT_PROPERTIES" => array(),
				"PRODUCT_PROPS_VARIABLE" => "prop",
				"PRODUCT_QUANTITY_VARIABLE" => "quantity",
				"PROPERTY_CODE" => array(
					0 => "SIZE",
					1 => "",
				),
				"SECTION_CODE" => "",
				"SECTION_ID" => "",
				"SECTION_ID_VARIABLE" => "SECTION_ID",
				"SECTION_URL" => "/catalog/#SECTION_CODE#/",
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
				"CUSTOM_FILTER" => "",
				"NAME_BLOCK" => "Вы недавно смотрели",
				"PAGE_NAVIGATION_ID" => "page",
				"PREVIEW_IMG_WIDTH" => "330",
				"PREVIEW_IMG_HEIGHT" => "450",
				"DETAIL_IMG_WIDTH" => "",
				"DETAIL_IMG_HEIGHT" => "",
			),
			$component,
			['HIDE_ICONS' => 'Y']
		);
	} ?>
</div>
<!--¯\_(ツ)_/¯ end el-caruselka-->