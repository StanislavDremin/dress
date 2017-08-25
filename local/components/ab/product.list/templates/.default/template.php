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
/** @var \Dress\Catalog\ProductList $component */
$this->setFrameMode(true);
//PR($arParams['SECTION_DATA']['DESCRIPTION']);
?>
<? $this->SetViewTarget('count_products'); ?>
<span><?=$arResult['COUNT_ITEMS']?></span>
<span class="catalog-list-head__title__hidden"><?=$arResult['COUNT_ITEMS_FORMAT']?></span>
<? $this->EndViewTarget(); ?>

<div class="catalog-wrap-data" v-loading="showLoader" @setfilter="makeFilter">

	<products :items="productItems.items" :nav="productItems.nav" :updater="updatePage" :more-page="morePage" :is-not-available="isNotAvailable">
		<div class="catalog-list">
			<? foreach ($arResult['ITEMS'] as $item): ?>
				<div class="catalog-list__items">
					<?if($item['NEWPRODUCT'] > 0):?>
						<span class="lable" style="background: url(/local/dist/img/icons/icons-lable-new.png)"></span>
					<?endif;?>
					<div class="catalog-list__items__img" style="background-image: url(<?=$item['SMALL_PICTURE']['src']?>)"></div>
					<div class="catalog-list__items__desc">
						<div class="catalog-list__items__desc__title"><span><?=$item['NAME']?></span></div>
						<div class="catalog-list__items__desc__prise">
							<span><?=$item['PRICE_FORMAT']?> руб.</span>
							<!--<span class="sale"></span>-->
						</div>
					</div>
					<div class="catalog-list__items__hidden">
						<? if (count($item['SIZE']['VALUE']) > 0): ?>
							<div class="catalog-list__items__hidden__size">
								<span>Размеры (RUS):</span>&nbsp;&nbsp;<?=(implode(" ", $item['SIZE']['VALUE']))?>
							</div>
						<? endif; ?>
						<div class="catalog-list__items__hidden__btn">
							<a class="shop-item-btn" href="<?=$item['DETAIL_PAGE_URL']?>">Подробнее</a>
						</div>
					</div>
				</div>
			<? endforeach; ?>
		</div>
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.pagenavigation",
			"",
			array(
				"NAV_OBJECT" => $arResult['NAV'],
				"SEF_MODE" => "N",
			),
			false
		); ?>
	</products>
</div>