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
$firstItem = array_shift($arResult['ITEMS']);
?>
<!--begin box-list-->
<div class="container">
	<div class="box-list">
		<div class="box-list__lg" style="background-image: url(<?=$firstItem['DETAIL_PICTURE_SRC']?>);">
			<span class="box-list__lg__title"><?=$firstItem['TITLE']?></span>
			<div class="btn_lg">
				<a class="btn_black" href="<?=$firstItem['LINK']?>">Подробнее о коллекции</a>
			</div>
		</div>
		<div class="row box-list-sl-md-wr">
			<?foreach ($arResult['ITEMS'] as $k => $arItem):?>
				<?if($k + 1 != count($arResult['ITEMS'])):?>
					<a href="<?=$arItem['LINK']?>" class="box-list__sl" style="background-image: url(<?=$arItem['DETAIL_PICTURE_SRC']?>);">
						<span class="box-list__sl__title"><?=$arItem['TITLE']?></span>
					</a>
				<?else:?>
					<div class="box-list__md">
						<div class="box-list__md__img_desc" style="background-image: url(<?=$arItem['DETAIL_PICTURE_SRC']?>)"></div>
						<div class="box-list__md__img_mobile" style="background-image: url(<?=$arItem['PREVIEW_PICTURE_SRC']?>)"></div>
						<span class="box-list__md__title"><?=$arItem['TITLE']?></span>
						<div class="btn_md">
							<a class="btn_black" href="<?=$arItem['LINK']?>">Выбрать и купить</a>
						</div>
					</div>
				<?endif;?>
			<?endforeach;?>
		</div>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
