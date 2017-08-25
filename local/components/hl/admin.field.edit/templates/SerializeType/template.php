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
$values = $arParams['HTML_CTRL']['VALUE'][0];
?>
<tr>
	<td></td>
	<td>
		<table class="table">
			<thead>
			<tr>
				<th>Название</th>
				<th>Код</th>
				<th>Значение</th>
				<th>Описание</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><input name="<?=$arParams['HTML_CTRL']['NAME']?>[0][TITLE]" value="<?=$values['TITLE']?>" type="text" /></td>
				<td><input name="<?=$arParams['HTML_CTRL']['NAME']?>[0][CODE]" value="<?=$values['CODE']?>" type="text" /></td>
				<td><input name="<?=$arParams['HTML_CTRL']['NAME']?>[0][VALUE]" value="<?=$values['VALUE']?>" type="text" /></td>
				<td><input name="<?=$arParams['HTML_CTRL']['NAME']?>[0][DESC]" value="<?=$values['DESC']?>" type="text" /></td>
			</tr>
			</tbody>
		</table>
	</td>
</tr>
