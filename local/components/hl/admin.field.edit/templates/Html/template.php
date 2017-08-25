<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
use Bitrix\Main\Config\Option;

$arUserField = $arParams['FIELD'];
$arHtmlControl = $arParams['HTML_CTRL'];?>
<table>
	<?if(Option::get("iblock", "use_htmledit", "Y")=="Y" && Bitrix\Main\Loader::includeModule("fileman")):?>
		<tr>
			<td colspan="2" align="center">
				<input type="hidden" name="<?=$arHtmlControl["NAME"]?>" value="" />
				<?if (is_null($arUserField["VALUE"])){
					$arHtmlControl["VALUE"] = $arUserField["SETTINGS"]["DEFAULT_VALUE"];
				}
				$text_type = preg_replace("/([^a-z0-9])/is", "_", $arHtmlControl["NAME"]."[TYPE]");
				\CFileMan::AddHTMLEditorFrame(
					$arHtmlControl["NAME"],
					htmlspecialcharsback($arHtmlControl["VALUE"]),
					$text_type,
					strToLower("html"),
					$arUserField["SETTINGS"]["HEIGHT"],
					"N", 0, "", "", false, true
				);
				?>
			</td>
		</tr>
	<?else:?>
		<tr>
			<td colspan="2" align="center">
				<textarea cols="70"
						  rows="10"
						  name="<?=$arHtmlControl["NAME"]?>"
						  style="width:100%"><?=$arHtmlControl["VALUE"]?></textarea>
			</td>
		</tr>
	<?endif;?>
</table>
