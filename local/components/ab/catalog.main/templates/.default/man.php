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
$this->setFrameMode(true);
dump($arResult['SECTION']);
?>
<?if(is_array($arResult['SECTION']['BANNER'])):?>
	<!--begin banner-girl-->
	<div class="banner-girl" style="background-image: url(<?=$arResult['SECTION']['BANNER']['DESKTOP']['SRC']?>)">
		<div class="row">
			<div class="banner-girl__txt"><?=$arResult['SECTION']['UF_BANNER_TEXT']?></div>
			<div class="banner-girl__btn"><a class="btn_black" href="<?=$arResult['SECTION']['DETAIL_PAGE_URL']?>">Выбрать и купить</a> </div>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
<?endif;?>

