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
if(count($arResult['ITEMS']) == 0)
	return false;
?>
<!--begin news-shops-->
<div class="news-shops-wr">
	<div class="container">
		<div class="news-shops-title"><?=$arParams['NAME_BLOCK']?></div>
		<div class="news-shops responsive">
			<?foreach ($arResult['ITEMS'] as $k => $item):?>
				<div class="news-shops__items news-shops__hover">
					<a href="<?=$item['DETAIL_PAGE_URL']?>">
						<div class="news-shops__items__img" style="background-image: url(<?=$item['SMALL_PICTURE']['src']?>)"></div>
					</a>
					<div class="news-shops__items__prise">
						<?=$item['PRICE_FORMAT']?> <i class="fa fa-rouble"></i>
					</div>
					<div class="news-shops__items__desc">
						<?=$item['NAME']?>
					</div>
					<div class="news-shops__items__size">
						<?if(count($item['SIZE']['VALUE']) > 0):?>
							<span class="items__size_txt">Размеры:</span>
							<strong style="color: #000;"><?=implode(" ", $item['SIZE']['VALUE'])?></strong>
						<?endif;?>
					</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
