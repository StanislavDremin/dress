<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
$cnt = 5;
$name = $arParams['FIELD']['FIELD_NAME'];
$nameHash = md5($name);
$tableName = 'tb'.$nameHash;
$values = $arParams['HTML_CTRL']['VALUE'];
$bVarsFromForm = false;

$multi = false;
if($arParams['FIELD']['MULTIPLE'] == 'Y'){
	$multi = true;
}

if(intval($values) > 0){
	$arSelect = array('ID','NAME','IBLOCK_ID','IBLOCK_TYPE'=>'IBLOCK.TYPE.ID');
	if(!$multi){
		$row = \Bitrix\Iblock\ElementTable::getRow(array(
			'select'=>$arSelect,
			'filter'=>array('=ID'=>$values),
		));
		if(!is_null($row)){
			$bVarsFromForm = true;
		}
	} elseif(is_array($values) && count($values) > 0) {
		$obList = \Bitrix\Iblock\ElementTable::getList(array(
			'select'=>$arSelect,
			'filter'=>array('ID'=>$values)
		));
		$arListValues = array();
		while($list = $obList->fetch()){
			$arListValues[] = $list;
		}
	}
}?>
	<table cellpadding="0" cellspacing="0" border="0" class="nopadding" width="100%" id="<?=$tableName?>">
		<?if(!$multi):
			$key = 1;?>
			<tr>
				<td>
					<input name="<?=$name?>"
						   id="<?=$name?>"
						   value="<?=$values?>"
						   size="5"
						   type="text"
						/>
					<input type="button" value="..."
						   onClick="jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=<?=LANGUAGE_ID?>&amp;IBLOCK_ID=<?=$arParams['FIELD']['SETTINGS']['IBLOCK_ID']?>&amp;n=<?=$name?>&amp;k=<?=$key?>', 1024, 800);"/>
					&nbsp;
					<span id="sp_<?=$nameHash?>_<?=$key?>">
						<?if($bVarsFromForm):?>
							<a target="_blank" href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?=$row['IBLOCK_ID']?>&type=<?=$row['IBLOCK_TYPE']?>&ID=<?=$row['ID']?>&lang=<?=LANGUAGE_ID?>"><?=$row['NAME']?></a>
						<?endif;?>
					</span>
				</td>
			</tr>
		<?else:?>
			<?for($i = 0; $i <= $cnt; $i++):
				$key = $i;
				$row = $arListValues[$key];
				$name = $arParams['FIELD']['FIELD_NAME'].'['.$key.']';
				$link = '/bitrix/admin/iblock_element_edit.php?IBLOCK_ID='.$row['IBLOCK_ID'].'&type='.$row['IBLOCK_TYPE'].'&ID='.$row['ID'].'&lang='.LANGUAGE_ID;
				?>
				<tr>
					<td>
						<input name="<?=$name?>"
							   id="<?=$name?>"
							   value="<?=$arListValues[$key]['ID']?>"
							   size="5"
							   type="text"
							/>
						<input type="button" value="..."
							   onClick="jsUtils.OpenWindow('/bitrix/admin/iblock_element_search.php?lang=<?=LANGUAGE_ID?>&amp;IBLOCK_ID=<?=$arParams['FIELD']['SETTINGS']['IBLOCK_ID']?>&amp;n=<?=$name?>&amp;k=<?=$key?>', 1024, 800);"/>
						&nbsp;
						<span id="sp_<?=$nameHash?>_<?=$key?>">
							<?if(!empty($row)):?>
								<a target="_blank" href="<?=$link?>"><?=$row['NAME']?></a>
							<?endif;?>
						</span>
					</td>
				</tr>
			<?endfor;?>
		<?endif?>
	</table>
<?
$jsNameInput = $name;
if($multi){
	$jsNameInput = $name.'[MV_'.$nameHash.']';
}?>
<script type="text/javascript">
	var MV_<?=$nameHash?> = <?=$key?>;
	function InS<?=$nameHash?>(id, name){
		oTbl=document.getElementById('<?=$tableName?>');
		oRow=oTbl.insertRow(oTbl.rows.length-1);
		oCell=oRow.insertCell(-1);
		oCell.innerHTML='<input name="<?=$jsNameInput?>" value="'+id+'" size="5" type="text">'+
			'<input type="button" value="..." '+
			'onClick="jsUtils.OpenWindow(\'/bitrix/admin/iblock_element_search.php?lang=ru&amp;IBLOCK_ID=<?=$arParams['FIELD']['SETTINGS']['IBLOCK_ID']?>&amp;n=<?=$name?>&amp;k='+MV_<?=$nameHash?>+'\', '+
			' 600, 500);" />'+'' +
			'&nbsp;<span id="sp_<?=$nameHash?>_'+MV_<?=$nameHash?>+'" >'+name+
			'</span>';
		MV_<?=$nameHash?>++;
	}
</script>