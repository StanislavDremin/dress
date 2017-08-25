<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
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
<el-menu class="el-menu-vertical-demo">
	<el-menu-item index="2"><a href="/">Главная</a></el-menu-item>
	<?
	$i = 2;
	foreach ($arResult['CATALOG_SECTIONS'] as $arSection):?>
		<? if (count($arSection['SUB_SECTION']) > 0){
			$j = 1;?>
			<el-submenu index="<?=$i?>">
				<template slot="title"><?=$arSection['NAME']?></template>
				<?foreach ($arSection['SUB_SECTION'] as $subsection){?>
					<el-menu-item index="<?=$i?>-<?=$j?>">
						<a href="<?=$subsection['SECTION_PAGE_URL']?>"><?=$subsection['NAME']?></a>
					</el-menu-item>
					<? $j++;
				}
				?>
			</el-submenu>
		<?} else { ?>
			<el-menu-item index="<?=$i?>">
				<a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a>
			</el-menu-item>
		<? } ?>
	<? $i++; endforeach; ?>
	<?foreach ($arResult['ITEMS'] as $k => $item):?>
		<el-menu-item index="2" <?=($item['PARAMS']['sale'] ? 'class="color"' : false)?>>
			<a href="<?=$item['LINK']?>">
				<?=$item['TEXT']?>
				<?if($item['PARAMS']['sale']){
					$icon = '/local/dist/img/icons/'.$item['PARAMS']['sale'];?>
					&nbsp;<span style="background-image: url(<?=$icon?>);"></span>
				<?}?>
			</a>
		</el-menu-item>
		<?if($k == 2){
			?><span class="hr"></span><?
		}?>
	<?endforeach;?>
</el-menu>