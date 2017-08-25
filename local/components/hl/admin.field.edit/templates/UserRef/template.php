<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
$arHtmlControl = $arParams['HTML_CTRL'];
$name_x = $arResult['NAME_X'];
$user = $arResult['USER'];
if(is_array($arResult['USER']))
	$userName = "[<a title='".GetMessage("MAIN_EDIT_USER_PROFILE")."'  href='/bitrix/admin/user_edit.php?ID=".$user['ID']."&lang=".LANG."'>".$user['ID']."</a>] (".$user['ID'].") ".$user['NAME']." ".$user['LAST_NAME'];

$select = $arResult['SELECT'];
?>
	<select id="SELECT<?=htmlspecialcharsbx($arHtmlControl["NAME"])?>" name="SELECT<?=htmlspecialcharsbx($arHtmlControl["NAME"])?>"
			onchange="changeSelect(this.value);">
		<option value="none"<?if($select=="none")echo " selected"?>><?=GetMessage("IBLOCK_PROP_USERID_NONE")?></option>
		<option value="CU"<?if($select=="CU")echo " selected"?>><?=GetMessage("IBLOCK_PROP_USERID_CURR")?></option>
		<option value="SU"<?if($select=="SU")echo " selected"?>><?=GetMessage("IBLOCK_PROP_USERID_OTHR")?></option>
	</select>&nbsp;
<?=FindUserIDNew(
	htmlspecialcharsbx($arHtmlControl["NAME"]),
	htmlspecialcharsbx($arHtmlControl["VALUE"]),
	$userName,
	$arResult['FORM_NAME'],
	$select
);?>
<script type="text/javascript">
	function changeSelect(val)
	{
		var v;
		this.value = val;
		if(this.value == 'none')
		{
			v = document.getElementById('<?=htmlspecialcharsbx($arHtmlControl["NAME"])?>');
			v.value = '';
			v.readOnly = true;
			document.getElementById('FindUser<?=$name_x?>').disabled = true;
		}
		else
		{
			v = document.getElementById('<?=htmlspecialcharsbx($arHtmlControl["NAME"])?>');
			v.value = this.value == 'CU'?'<?=$USER->GetID()?>':'';
			v.readOnly = false;
			document.getElementById('FindUser<?=$name_x?>').disabled = false;
		}
	}
</script>