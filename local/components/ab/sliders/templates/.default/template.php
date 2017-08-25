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
?>
<div class="slider-wr">
	<div class="slider fade">
		<? foreach ($arResult['ITEMS'] as $arItem):
			$link = null;
			if ($arItem['PRODUCT_ITEM']['DETAIL_PAGE_URL']){
				$link = $arItem['PRODUCT_ITEM']['DETAIL_PAGE_URL'];
			} elseif (strlen($arItem['UF_LINK']) > 0) {
				$link = $arItem['UF_LINK'];
			}
			?>
			<div class="slider__items lazy_item" style="background-image: url(<?=$arItem["UF_MAIN_IMG"]["SRC"]?>);">
				<div class="container">
					<div class="row">
						<div class="slider__items__small"><?=$arItem['UF_LEFT_TEXT']?></div>
						<div class="slider__items__big"><?=$arItem['UF_SLOGAN']?></div>
					</div>
					<div class="row">
						<div class="slider__items__desk"><?=$arItem['UF_DESC']?></div>
					</div>
					<? if (!is_null($link)):?>
						<div class="row">
							<div class="slider__items__btn">
								<a href="<?=$link?>">
									<?=(strlen($arItem['UF_LINK_TEXT']) > 0 ? $arItem['UF_LINK_TEXT'] : 'Выбрать и купить')?>
								</a>
							</div>
						</div>
					<? endif; ?>
					<? if (strlen($arItem['UF_PROPMO_CODE']) > 0): ?>
						<div class="row">
							<div class="slider__items__promo">Промокод: <?=$arItem['UF_PROPMO_CODE']?></div>
						</div>
					<? endif; ?>
				</div>
			</div>
		<? endforeach; ?>
	</div>
</div>
