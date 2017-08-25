<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();

$this->setFrameMode(true);

$colorSchemes = array(
	"green" => "bx-green",
	"yellow" => "bx-yellow",
	"red" => "bx-red",
	"blue" => "bx-blue",
);
if(isset($colorSchemes[$arParams["TEMPLATE_THEME"]]))
{
	$colorScheme = $colorSchemes[$arParams["TEMPLATE_THEME"]];
}
else
{
	$colorScheme = "";
}
?>
<!--begin catalog-list-next-btn-->
<div class="catalog-list-next" v-if="productItems.nav && productItems.nav.totalPages > 1 && productItems.nav.totalPages > currentPage">
	<a class="catalog-list-next-btn" @click.prevent="morePage" href="javascript:">Показать еще</a>
</div>
<!--¯\_(ツ)_/¯ end catalog-list-next-btn-->
<div class="bx-pagination <?=$colorScheme?>">
	<div class="bx-pagination-container row">
		<ul>
<?if($arResult["REVERSED_PAGES"] === false):?>
	<?if ($arResult["CURRENT_PAGE"] > 1):?>
		<?if ($arResult["CURRENT_PAGE"] > 2):?>
			<li class="bx-pag-prev"><a @click="changePage($event, <?=$arResult["CURRENT_PAGE"]-1?>)" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]-1))?>"><span><?echo GetMessage("round_nav_back")?></span></a></li>
		<?else:?>
			<li class="bx-pag-prev"><a @click="changePage($event, <?=$arResult["CURRENT_PAGE"]-1?>)" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><span><?echo GetMessage("round_nav_back")?></span></a></li>
		<?endif?>
			<li class=""><a @click="changePage($event, 1)" href="<?=htmlspecialcharsbx($arResult["URL"])?>"><span>1</span></a></li>
	<?else:?>
			<li class="bx-pag-prev"><span><?echo GetMessage("round_nav_back")?></span></li>
			<li class="bx-active"><span>1</span></li>
	<?endif?>

	<?
	$page = $arResult["START_PAGE"] + 1;
	while($page <= $arResult["END_PAGE"]-1):
	?>
		<?if ($page == $arResult["CURRENT_PAGE"]):?>
			<li class="bx-active"><span><?=$page?></span></li>
		<?else:?>
			<li class=""><a @click="changePage($event, <?=$page?>)" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($page))?>"><span><?=$page?></span></a></li>
		<?endif?>
		<?$page++?>
	<?endwhile?>

	<?if($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]):?>
		<?if($arResult["PAGE_COUNT"] > 1):?>
			<li class=""><a @click="changePage($event, <?=$arResult["PAGE_COUNT"]?>)" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"]))?>"><span><?=$arResult["PAGE_COUNT"]?></span></a></li>
		<?endif?>
			<li class="bx-pag-next"><a @click="changePage($event, <?=$arResult["CURRENT_PAGE"]+1?>)" href="<?=htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"]+1))?>"><span><?echo GetMessage("round_nav_forward")?></span></a></li>
	<?else:?>
		<?if($arResult["PAGE_COUNT"] > 1):?>
			<li class="bx-active"><span><?=$arResult["PAGE_COUNT"]?></span></li>
		<?endif?>
			<li class="bx-pag-next"><span><?echo GetMessage("round_nav_forward")?></span></li>
	<?endif?>
<?endif?>
		</ul>
		<div style="clear:both"></div>
	</div>
</div>
