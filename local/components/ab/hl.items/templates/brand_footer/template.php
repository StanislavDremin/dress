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
//dump($arResult['ITEMS']);
?>
<!--begin brands-->
<div class="brands-wr">
	<div class="container">
		<div class="news-shops-title" style="margin: 10px 0 40px 0;">Бренды</div>
		<div class="brands responsive">
			<?foreach ($arResult['ITEMS'] as $l => $item):
				$itemImg = \CFile::ResizeImageGet($item['PICTURE_ID'], ['width' => 200, 'height' => 50, BX_RESIZE_IMAGE_PROPORTIONAL_ALT]);
				?>
				<div class="brands__items">
					<a href="/brands/<?=$item['XML_ID']?>/">
						<div class="brands__items__img">
							<img src="<?=$itemImg['src']?>" alt="<?=$item['NAME']?>">
						</div>
						<div class="brands__items__name"><?=$item['NAME']?></div>
					</a>
				</div>
			<?endforeach;?>
		</div>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
