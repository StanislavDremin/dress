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
$this->setFrameMode(true);?>
<a href="/personal/cart/">
	<div class="top-nav__cart" id="basket_top_app" v-cloak>
		<i style="font-style: normal" class="txt-cart">Корзина</i>
		<span v-if="count == 0"><?=$arResult['CNT']?></span>
		<span v-else>{{count}}</span>
	</div>
</a>
