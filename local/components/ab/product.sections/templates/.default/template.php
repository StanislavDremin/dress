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
$this->setFrameMode(true); ?>
<template id="sidebar-nav">
	<div class="sidebar-catalog" v-cloak>
		<el-menu default-active="0-1" class="el-menu-vertical-demo">
			<?
			$i = 0;
			foreach ($arResult['ITEMS'] as $first): ?>
				<el-submenu index="<?=$i?>">
					<? if (count($first['SUB_SECTION']) > 0): ?>
						<template class="sidebar-catalog__title" slot="title"><?=$first['NAME']?></template>
						<? $j = 0;
						foreach ($first['SUB_SECTION'] as $second): ?>
							<? if (count($second['SUB_SECTION']) > 0): ?>
								<el-submenu index="<?=($i.'-'.$j)?>">
									<template slot="title"><?=$second['NAME']?></template>
									<? foreach ($second['SUB_SECTION'] as $third): ?>
										<el-menu-item index="<?=($i.'-'.$third['ID'])?>">
											<a href="<?=$third['DETAIL_PAGE_URL']?>"><?=$third['NAME']?></a>
										</el-menu-item>
									<?endforeach; ?>
								</el-submenu>
								<?
							else: ?>
								<el-menu-item index="<?=($i.'-'.$j)?>">
									<a href="<?=$second['DETAIL_PAGE_URL']?>"><?=$second['NAME']?></a>
								</el-menu-item>
							<?endif; ?>
							<? $j++;
						endforeach; ?>
						<?
					else: ?>
						<el-menu-item class="sidebar-catalog__title" index="<?=$i?>">
							<a href="<?=$first['DETAIL_PAGE_URL']?>"><?=$first['NAME']?></a>
						</el-menu-item>
					<?endif; ?>

				</el-submenu>

				<? $i++;
			endforeach; ?>
		</el-menu>
	</div>
</template>
