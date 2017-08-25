<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 18.07.2017
 */

namespace Dresscode\Main\InSaleImport;

use Bitrix\Main;
Main\Loader::includeModule('iblock');

class Sections extends BaseImport
{
	const FIRST_ID = 325231;

	/** @var  \CIBlockSection */
	private $CSection;

	/**
	 * Sections constructor.
	 *
	 * @param $params
	 */
	public function __construct($params = false)
	{
		parent::__construct($params);
		$this->CSection = new \CIBlockSection();
	}


	public function importFirstLevel()
	{
		$Result = new Main\Result();

		$items = $this->client->getCollections(['parent_id' => self::FIRST_ID])->getData();

		if(count($items) == 0){
			$Result->addError(new Main\Error('Категории не найдены', 404));
		}

		foreach ($items as $item) {
			if($item['is_hidden'] == false){
				$save = [
					'IBLOCK_ID' => $this->getOptions()->get('IBLOCK_ID'),
					'NAME' => $item['title'],
					'CODE' => $item['permalink'],
					'XML_ID' => $item['id'],
					'DESCRIPTION' => $item['description'],
					'DESCRIPTION_TYPE' => 'html',
				];
				if($item['image']['original_url']){
					$save['PICTURE'] = \CFile::MakeFileArray($item['image']['original_url']);
				}

				$row = $this->getSectionByXml($save['XML_ID']);
				if(is_null($row)){
					$result = $this->CSection->Add($save);
					if((int)$result == 0)
						$Result->addError(new Main\Error(strip_tags($this->CSection->LAST_ERROR)));
				} else {
					$result = $this->CSection->Update($row['ID'], $save);
					if(!$result)
						$Result->addError(new Main\Error(strip_tags($this->CSection->LAST_ERROR)));
				}
			}
		}

		return $Result;
	}

	public function importByParent($parent)
	{
		$Result = new Main\Result();
		$items = $this->client->getCollections(['parent_id' => $parent])->getData();

		$parentSection = $this->getSectionByXml($parent);

		if(count($items) > 0){
			foreach ($items as $item) {
//				if($item['is_hidden'] == false){
					$save = [
						'IBLOCK_ID' => $this->getOptions()->get('IBLOCK_ID'),
						'NAME' => $item['title'],
						'CODE' => $item['permalink'],
						'XML_ID' => $item['id'],
						'DESCRIPTION' => $item['description'],
						'DESCRIPTION_TYPE' => 'html',
						'IBLOCK_SECTION_ID' => $parentSection['ID']
					];
					if($item['image']['original_url']){
						$save['PICTURE'] = \CFile::MakeFileArray($item['image']['original_url']);
					}
					$row = $this->getSectionByXml($save['XML_ID']);
					if(is_null($row)){
						$result = $this->CSection->Add($save);
						if((int)$result == 0)
							$Result->addError(new Main\Error(strip_tags($this->CSection->LAST_ERROR)));
					} else {
						$result = $this->CSection->Update($row['ID'], $save);
						if(!$result)
							$Result->addError(new Main\Error(strip_tags($this->CSection->LAST_ERROR)));
					}
//				}
			}
		} else {
			$Result->addError(new Main\Error('Категории не найдены', 404));
		}

		return $Result;
	}

	private function getSectionByXml($xmlId)
	{
		return \Bitrix\Iblock\SectionTable::getRow([
			'select' => ['ID'],
			'filter' => ['IBLOCK_ID' => $this->getOptions()->get('IBLOCK_ID'), '=XML_ID' => $xmlId]
		]);
	}

}