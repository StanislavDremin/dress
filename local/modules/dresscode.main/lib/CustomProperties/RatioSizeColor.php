<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 20.07.2017
 */

namespace Dresscode\Main\CustomProperties;

use Bitrix\Iblock\PropertyTable;
use Bitrix\Main;
use Dresscode\Main\Config;
use Bitrix\Highloadblock as HL;
use Online1c\Iblock;

Main\Loader::includeModule('iblock');
Main\Loader::includeModule('highloadblock');
Main\Loader::includeModule('online1c.iblock');

Config::addRatioPropertyJs();

class RatioSizeColor
{
	const RATIO_SIZE_TYPE = 'RatioSizeColor';

	public static function GetUserTypeDescription()
	{
		return array(
			'PROPERTY_TYPE' => PropertyTable::TYPE_STRING,
			'USER_TYPE' => self::RATIO_SIZE_TYPE,
			'DESCRIPTION' => 'Таблица цвет + размер',
			'ConvertToDB' => array(__CLASS__, 'ConvertToDB'),
			'ConvertFromDB' => array(__CLASS__, 'ConvertFromDB'),
			'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml'),
			'GetAdminListViewHTML' => array(__CLASS__, 'GetAdminListViewHTML'),
//			'GetPublicViewHTML' => array(__CLASS__, 'GetPublicViewHTML'),
			'GetPublicEditHTML' => array(__CLASS__, 'GetPublicEditHTML'),
//			'GetAdminFilterHTML' => array(__CLASS__,'GetAdminFilterHTML'),
//			'GetPublicFilterHTML' => array(__CLASS__, 'GetPublicFilterHTML'),
			'GetSettingsHTML' => array(__CLASS__, 'GetSettingsHTML'),
			'PrepareSettings' => array(__CLASS__, 'PrepareSettings'),
		);
	}

	public static function ConvertToDB($arProperty, $value)
	{
		return $value;
	}

	public static function ConvertFromDB($arProperty, $value)
	{
		return $value;
	}

	/**
	 * @method GetPublicEditHtml - При показе в списке
	 * @param $arProperty
	 * @param $arValue
	 * @param $strHTMLControlName
	 */
	public static function GetPublicEditHtml($arProperty, $arValue, $strHTMLControlName)
	{
	}

	/**
	 * @method GetPropertyFieldHtml - при редактировании элемента
	 * @param $arProperty
	 * @param $arValue
	 * @param $strHTMLControlName
	 *
	 * @return mixed
	 */
	public static function GetPropertyFieldHtml($arProperty, $arValue, $strHTMLControlName)
	{
		if ($strHTMLControlName['VALUE'] == 'PROPERTY_DEFAULT_VALUE'){
			return '';
		}
		/** @var \Bitrix\Main\HttpRequest $request */
		$request = \Bitrix\Main\Context::getCurrent()->getRequest();

		echo '<div id="ratio_app"><ratio :product="'.$request->get('ID').'" :options="'.\CUtil::PhpToJSObject($arProperty).'"></ratio></div>';
	}

	public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields)
	{
		$blocks = [];
		$oBlocks = HL\HighloadBlockTable::getList([
			'select' => ['*', 'TITLE' => 'LANG.NAME'],
		]);
		while ($block = $oBlocks->fetch()) {
			$title = strlen($block['TITLE']) > 0 ? $block['TITLE'] : $block['NAME'];
			$blocks[$block['ID']] = $title;
		}
		$settings = $arProperty['USER_TYPE_SETTINGS'];

		$props = Iblock\BaseProperty::getMetaProperty($arProperty['IBLOCK_ID']);
		ob_start();
		?>
		<tr valign="top">
			<td>Справочник цветов:</td>
			<td>
				<select name="<?=$strHTMLControlName["NAME"]?>[COLOR_GUIDE]">
					<? foreach ($blocks as $id => $value): ?>
						<option value="<?=$id?>" <?=($settings['colorGuide'] == $id ? 'selected' : false)?>><?=$value?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<td>Свойство с цветом:</td>
			<td>
				<select name="<?=$strHTMLControlName["NAME"]?>[COLOR_PROPERTY]">
					<? foreach ($props as $code => $value): ?>
						<option value="<?=$code?>" <?=($settings['colorProperty'] == $code ? 'selected' : false)?>><?=$value['NAME']?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<td>Справочник размеров:</td>
			<td>
				<select name="<?=$strHTMLControlName["NAME"]?>[SIZE_GUIDE]">
					<? foreach ($blocks as $id => $value): ?>
						<option value="<?=$id?>" <?=($settings['sizeGuide'] == $id ? 'selected' : false)?>><?=$value?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<td>Свойство с размером:</td>
			<td>
				<select name="<?=$strHTMLControlName["NAME"]?>[SIZE_PROPERTY]">
					<? foreach ($props as $code => $value): ?>
						<option value="<?=$code?>" <?=($settings['sizeProperty'] == $code ? 'selected' : false)?>><?=$value['NAME']?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>

		<?
		$html = ob_get_contents();
		ob_end_clean();

//		dump($strHTMLControlName);
//		dump($arProperty);
//		dump($arPropertyFields);

		return $html;
	}

	public static function PrepareSettings($arProperty)
	{
		return array(
			"colorGuide" => $arProperty['USER_TYPE_SETTINGS']['COLOR_GUIDE'],
			"sizeGuide" => $arProperty['USER_TYPE_SETTINGS']['SIZE_GUIDE'],
			"sizeProperty" => $arProperty['USER_TYPE_SETTINGS']['SIZE_PROPERTY'],
			"colorProperty" => $arProperty['USER_TYPE_SETTINGS']['COLOR_PROPERTY'],
		);
	}

	public function loadDataAction($data = [])
	{
		$productId = (int)$data['product'];

		if ($productId == 0)
			return null;

//		$iblock = Iblock\Element::getIblockByElement($productId);
//		$arElement = Iblock\Element::getRow([
//			'select' => [
//				'ID','NAME','IBLOCK_ID',
//				$data['sizeProperty'] => 'PROPERTY.'.$data['sizeProperty'],
//				$data['colorProperty'] => 'PROPERTY.'.$data['colorProperty'],
//			],
//			'filter' => ['IBLOCK_ID' => $iblock, '=ID' => $productId]
//		]);

//		dump($arElement);
		$result = [
			'sizes' => $this->getGuideItems($data['sizeGuide'])->fetchAll(),
			'colors' => $this->getGuideItems($data['colorGuide'])->fetchAll(),
		];

//		$result['sizes'] = $arElement['SIZE']['VALUE'];
//		$result['colors'] = $arElement['COLOR']['VALUE'];

		return $result;
	}

	/**
	 * @method getGuideItems
	 * @param $guideId
	 *
	 * @return Main\DB\Result
	 */
	private function getGuideItems($guideId)
	{
		$block = HL\HighloadBlockTable::getRowById($guideId);
		$entity = HL\HighloadBlockTable::compileEntity($block);
		$entity->addField(new Main\Entity\ReferenceField(
			'FILE_REF',
			\Dresscode\Main\FileTable::getEntity(),
			['=this.UF_FILE' => 'ref.ID']
		));
		$blockClass = $entity->getDataClass();

		return $blockClass::getList([
			'select' => [
				'id' => 'ID', 'xml_id' => 'UF_XML_ID', 'title'=>'UF_NAME', 'src' => 'FILE_REF.SRC',
			],
		]);
	}
}
