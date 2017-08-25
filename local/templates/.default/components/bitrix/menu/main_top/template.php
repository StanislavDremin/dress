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
<div class="top-nav__nav">
	<ul>
		<? foreach ($arResult['CATALOG_SECTIONS'] as $section): ?>
			<li>
				<a class="<?=($section['IS_ACTIVE'] ? 'active' : '')?> hvr-underline-from-left" href="<?=$section['SECTION_PAGE_URL']?>">
					<?=$section['NAME']?>
				</a>
			</li>
		<? endforeach; ?>
	</ul>
	<ul class="nav-hidden">
		<? foreach ($arResult['ITEMS'] as $sectionItem):
			$class = 'hvr-underline-from-left';
			if($sectionItem['SELECTED'])
				$class.=' active';

			if($sectionItem['PARAMS']["sale"])
				$class.= ' color';
			?>
			<li>
				<a class="<?=$class?>" href="<?=$sectionItem['LINK']?>">
					<?
					echo $sectionItem['TEXT'];
					if ($sectionItem['PARAMS']["sale"]){
						$icon = '/local/dist/img/icons/'.$sectionItem['PARAMS']['sale'];
						?>
						<span style="background-image: url(<?=$icon?>);"></span>
					<? } ?>
				</a>
			</li>
		<? endforeach; ?>
	</ul>
</div>