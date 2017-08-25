<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 18.07.2017
 */

namespace Dresscode\Main\InSaleImport;

use AB\Tools\Debug;
use Bitrix\Main;
use Bitrix\Highloadblock as HL;

Main\Loader::includeModule('iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');
Main\Loader::includeModule('highloadblock');

class Products extends BaseImport
{
	/** @var  \CIBlockElement */
	private $CElement;
	private $CCatalogProduct;

	private $stop = false;
	private $fromFile = false;
	private $Result;
	private $step = 0;

	/**
	 * Sections constructor.
	 *
	 * @param $params
	 */
	public function __construct($params = false)
	{
		parent::__construct($params);
		$this->CElement = new \CIBlockElement();
		$this->Result = new Main\Result();
		$this->CCatalogProduct = new \CCatalogProduct();
	}

	private function getSections($ids)
	{
		$result = null;
		$oList = \Bitrix\Iblock\SectionTable::getList([
			'filter' => ['IBLOCK_ID' => $this->getOptions()->get('IBLOCK_ID'), '=XML_ID' => $ids],
			'select' => ['ID'],
		]);
		while ($r = $oList->fetch()) {
			$result[] = $r['ID'];
		}

		return $result;
	}

	public function productsBySection($sectionId, $startPage = 0)
	{
		if($startPage > 0){
			$this->step = $startPage;
		}
		set_time_limit(600);

		$lastId = 0;
		$this->step++;

//		if ($this->step > 1){
//			$this->stop = true;
//			return $this->Result;
//		}

		$params = ['collection_id' => $sectionId, 'per_page' => 100, 'page' => $this->step];

		if ($this->fromFile){
			$file = new Main\IO\File($_SERVER['DOCUMENT_ROOT'].'/api/products.json');
			if (!$file->isExists()){
				$items = $this->client->getProducts($params)->getData();
				$json = Main\Web\Json::encode($items);
				$file->putContents($json);
			} else {
				$items = Main\Web\Json::decode($file->getContents());
			}
		} else {
			$oItems = $this->client->getProducts($params);
			$head = $oItems->getHeaders();
			if($head['API-Usage-Limit'] == '499/500'){
				Debug::toLog($this->step);
				Debug::toLog($sectionId);
				sleep(320);
//				$this->stop = true;
//				return $this->Result;
			}

			$items = $oItems->getData();
		}

		if (count($items) == 0){
			$this->stop = true;

			return $this->Result;
		}

		$arItems = [];


		$brandBlock = HL\HighloadBlockTable::getRowById(6);
		$brandClass = HL\HighloadBlockTable::compileEntity($brandBlock)->getDataClass();

		$colorBlock = HL\HighloadBlockTable::getRowById(1);
		$colorClass = HL\HighloadBlockTable::compileEntity($colorBlock)->getDataClass();


		$sizeBlock = HL\HighloadBlockTable::getRowById(5);
		$sizeClass = HL\HighloadBlockTable::compileEntity($sizeBlock)->getDataClass();



		foreach ($items as $item) {
			if (empty($item['is_hidden'])){
				$lastId = $item['id'];
				$arItems[] = $item;

				$sections = $this->getSections($item['collections_ids']);
				$properties = [];
				$save = [
					'IBLOCK_ID' => $this->getOptions()->get('IBLOCK_ID'),
					'NAME' => $item['title'],
					'CODE' => $item['permalink'],
					'XML_ID' => $item['id'],
					'PREVIEW_TEXT' => $item['short_description'],
					'PREVIEW_TEXT_TYPE' => 'html',
					'DETAIL_TEXT' => $item['description'],
					'DETAIL_TEXT_TYPE' => 'html',
				];

				if (count($sections) == 1){
					$save['IBLOCK_SECTION_ID'] = array_shift($sections);
				} elseif (count($sections) > 1) {
					$save['IBLOCK_SECTION'] = $sections;
					$save['IBLOCK_SECTION_ID'] = array_shift($sections);
				}

				$arImage = [];
				if (count($item['images']) > 0){
					foreach ($item['images'] as $image) {
						$arImage[] = \CFile::MakeFileArray($image['original_url']);
					}
				}
				if (count($arImage) > 0){
					$save['DETAIL_PICTURE'] = array_shift($arImage);
					$properties['MORE_PHOTO'] = [];
					$i = 0;
					foreach ($arImage as $value) {
						$properties['MORE_PHOTO']["n".$i] = [
							"VALUE" => $value,
						];
						$i++;
					}
				}

				$optionValues = [];
				foreach ($item['option_names'] as $value) {
					$optionValues[$value['id']] = $value;
				}
				foreach ($item['properties'] as $value) {
					$optionValues[$value['id']] = $value;
				}
				foreach ($item['characteristics'] as $value) {
					switch ($optionValues[$value['property_id']]['permalink']){
						case 'Brend':
							$rowBrand = $brandClass::getRow([
								'select' => ['ID','UF_XML_ID','UF_NAME'],
								'filter' => ['=UF_XML_ID' => $value['permalink']]
							]);
							if(!is_null($rowBrand)){
								$properties['BRAND'] = $rowBrand['UF_XML_ID'];
							} else {
								$brandSave = [
									'UF_NAME' => $value['title'],
									'UF_XML_ID' => $value['permalink']
								];
								$brandClass::add($brandSave);
								$properties['BRAND'] = $brandSave['UF_XML_ID'];
							}
							break;

						case 'tsviet':
						case 'color':
							$rowColor = $colorClass::getRow([
								'select' => ['ID','UF_XML_ID','UF_NAME'],
								'filter' => ['=UF_XML_ID' => $value['permalink']]
							]);
							if(!is_null($rowColor)){
								$properties['COLOR'][] = ['VALUE' => $rowColor['UF_XML_ID']];
							} else {
								$colorClass::add([
									'UF_XML_ID' => $value['permalink'],
									'UF_NAME' => $value['title']
								]);
								$properties['COLOR'][] = ['VALUE' => $value['permalink']];
							}
							break;
					}
				}

				$properties['SIZE'] = [];
				$quantity = 0;
				foreach ($item['variants'] as $variant) {
					$price = $variant['base_price'] ? $variant['base_price'] : $variant['price'];
					$quantity += (int)$variant['quantity'];
					$properties['ARTNUMBER'] = $variant['sku'];

					foreach ($variant['option_values'] as $option_value) {
						switch ($option_value['option_name_id']){
							case '39807':
								$valSize = $option_value['title'];
								if(preg_match('#[à-ÿÀ-ß]+#i', $valSize)){
									$xml_id = \CUtil::translit($valSize, 'ru');
								}else {
									$xml_id = $valSize;
								}

								$rowSize = $sizeClass::getRow([
									'select' => ['ID','UF_XML_ID','UF_NAME'],
									'filter' => ['=UF_XML_ID' => $xml_id]
								]);
								if(!is_null($rowSize)){
									$properties['SIZE'][] = ['VALUE' => $rowSize['UF_XML_ID']];
								} else {
									$sizeClass::add([
										'UF_NAME' => $valSize,
										'UF_XML_ID' => $xml_id
									]);
									$properties['SIZE'][] = ['VALUE' => $xml_id];
								}
								break;
						}
					}

				}

				$save['PROPERTY_VALUES'] = $properties;

				$rowProduct = $this->getProductByXml($save['XML_ID']);
				$success = false;
				$ID = null;
				if (!is_null($rowProduct)){

					$this->deletePhotos($rowProduct['ID']);

					if (!$this->CElement->Update($rowProduct['ID'], $save)){
						$this->addError(strip_tags($this->CElement->LAST_ERROR));
						$success = false;
					} else {
						$success = true;
					}
					$ID = $rowProduct['ID'];
				} else {
					$ID = $this->CElement->Add($save);
					if ((int)$ID == 0){
						$this->addError(strip_tags($this->CElement->LAST_ERROR));
						$success = false;
					} else {
						$success = true;
					}
				}

				if ($success){
					\CPrice::SetBasePrice($ID, $price, 'RUB');
					$this->CCatalogProduct->Add([
						'ID' => $ID,
						'QUANTITY' => $quantity,
						'BUNDLE' => 'N',
					]);
				}

				Debug::toLog($item);
			}
		}

		if (!$this->stop){
			$this->productsBySection($sectionId);
		}

		return $this->Result;
	}

	private function deletePhotos($productId)
	{
		$iblock = $this->getOptions()->get('IBLOCK_ID');
		$res = \CIBlockElement::GetProperty(
			$iblock,
			$productId,
			"sort", "asc",
			array("CODE" => "MORE_PHOTO"));
		while ($value = $res->Fetch()) {
			$fileId = (int)$value['VALUE'];
			if($fileId > 0){
				\CFile::Delete($fileId);
			}
		}
		\CIBlockElement::SetPropertyValuesEx($productId, $iblock, ['MORE_PHOTO' => false]);
	}

	protected function getProductByXml($xmlId)
	{
		return \Bitrix\Iblock\ElementTable::getRow([
			'filter' => ['=XML_ID' => $xmlId, 'IBLOCK_ID' => $this->getOptions()->get('IBLOCK_ID')],
			'select' => ['ID'],
		]);
	}

	protected function addError($err, $code = 0)
	{
		$this->Result->addError(new Main\Error($err, $code));
	}
}