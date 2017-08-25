<?php namespace Dresscode;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use DigitalWand\MVC\BaseComponent;
use Online1c\Iblock;

Main\Loader::includeModule('digitalwand.mvc');
Main\Loader::includeModule('iblock');
Main\Loader::includeModule('online1c.iblock');

Loc::loadLanguageFile(__FILE__);

class CatalogMain extends BaseComponent
{
	public function onPrepareComponentParams($arParams)
	{
		$arParams['CACHE_ACTION'] = [
			'actionSection' => md5(serialize($this->request->toArray()))
		];

		$this->arResult['SECTION_PATH'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['section'];
		$this->arResult['ELEMENT_PATH'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['element'];
		$this->arResult['FILTER_PATH'] = $arParams['SEF_FOLDER'].$arParams['SEF_URL_TEMPLATES']['smart_filter'];

		$this->arResult['UF_SECTION_ID'] = 'IBLOCK_'.$arParams['IBLOCK_ID'].'_SECTION';

		$arParams = parent::onPrepareComponentParams($arParams);
		return $arParams;
	}


	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser()
	{
		global $USER;

		if (!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	public function actionIndex()
	{
		LocalRedirect('/');
	}

	public function actionSection($sectionCode)
	{
		$this->arResult['RAW_SECTION_CODE'] = $sectionCode;
		switch ($sectionCode) {
			case 'man':
				$sectionCode = 'muzhskaya-odezhda';
				$this->getCurrentSection($sectionCode);

				$this->arResult['SUB_SECTIONS'] = $this->getSubsections($this->arResult['SECTION']['ID']);

				$this->includeComponentTemplate('middleware');
				break;
			case 'woman':
				$sectionCode = 'zhenskaya-odezhda';
				$this->getCurrentSection($sectionCode);

				$this->arResult['SUB_SECTIONS'] = $this->getSubsections($this->arResult['SECTION']['ID']);

				$this->includeComponentTemplate('middleware');
				break;
			case 'kids':
				$sectionCode = 'detskaya-odezhda';
				$this->getCurrentSection($sectionCode);

				$this->arResult['SUB_SECTIONS'] = $this->getSubsections($this->arResult['SECTION']['ID']);

				$this->includeComponentTemplate('middleware');
				break;
			default:
				$this->getCurrentSection($sectionCode);
				$this->chainSection();

				break;
		}
	}

	protected function chainSection()
	{
		global $APPLICATION;
		if($this->arParams['ADD_SECTIONS_CHAIN'] == 'Y'){

			$obChain = \CIBlockSection::GetNavChain(
				$this->arParams['IBLOCK_ID'],
				$this->arResult['SECTION']['ID'],
				array('ID','NAME','SECTION_PAGE_URL')
			);
			while ($rs = $obChain->GetNext(true, false)){
				$APPLICATION->AddChainItem($rs['NAME'], $rs['SECTION_PAGE_URL']);
				$this->arResult['SECTION']['CHAIN'][] = $rs;
			}

			unset($obChain);
		}
	}

	public function getCurrentSection($sectionCode = '')
	{

		Iblock\SectionTable::setUserFieldId($this->arResult['UF_SECTION_ID']);
		Main\UserFieldTable::attachFields(Iblock\SectionTable::getEntity(), $this->arResult['UF_SECTION_ID']);

		$oSection = Iblock\SectionTable::getList([
			'select' => [
				'ID', 'NAME', 'IBLOCK_ID', 'UF_BACKGROUND_IMAGE', 'SECTION_CODE' => 'CODE',
				'UF_BANNER_TEXT', 'LEFT_MARGIN', 'RIGHT_MARGIN', 'DESCRIPTION'
			],
			'filter' => [
				'=IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
				'=CODE' => $sectionCode,
			],
			'limit' => 1,
		]);
		$oSection->addFetchDataModifier(Iblock\Helpers\PageUrlHelper::makeDetailUrl($this->arResult['SECTION_PATH']));
		$section = $oSection->fetch();

		if ((int)$section['UF_BACKGROUND_IMAGE'] > 0){
			$section['BANNER']['DESKTOP'] = \CFile::GetFileArray($section['UF_BACKGROUND_IMAGE']);
		}

		$this->arResult['SECTION_CODE'] = $sectionCode;
		$this->arResult['SECTION'] = $section;

		return $section;
	}

	protected function getSubsections($parentId, $limit = 5)
	{
		$parentId = (int)$parentId;
		if ($parentId == 0)
			return null;

		$sections = null;

		Iblock\SectionTable::setUserFieldId($this->arResult['UF_SECTION_ID']);
		Main\UserFieldTable::attachFields(Iblock\SectionTable::getEntity(), $this->arResult['UF_SECTION_ID']);

		$oSection = Iblock\SectionTable::getList([
			'select' => [
				'ID', 'NAME', 'IBLOCK_ID', 'UF_BACKGROUND_IMAGE', 'SECTION_CODE' => 'CODE',
				'UF_BANNER_TEXT', 'UF_MAIN', 'LEFT_MARGIN', 'RIGHT_MARGIN'
			],
			'filter' => [
				'=IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
				'=IBLOCK_SECTION_ID' => $parentId,
			],
			'limit' => $limit,
			'order' => ['SORT' => 'ASC', 'UF_MAIN' => 'DESC'],
		]);

		$i = 0;
		$mailSections = [];
		$oSection->addFetchDataModifier(Iblock\Helpers\PageUrlHelper::makeDetailUrl($this->arResult['SECTION_PATH']));
		while ($section = $oSection->fetch()) {
			if ((int)$section['UF_BACKGROUND_IMAGE'] > 0){
				$section['BANNER']['DESKTOP'] = \CFile::GetFileArray($section['UF_BACKGROUND_IMAGE']);
			}
			if($i < 2 && $section['UF_MAIN'] != false){
				$mailSections[] = $section;
			} else {
				$sections[] = $section;
			}
			$i++;
		}

		return ['MAIN' => $mailSections, 'OTHER' => $sections];
	}

	public function actionElement($elementCode, $sectionCode)
	{
		$this->getCurrentSection($sectionCode);
		$this->chainSection();
		$this->arResult['ELEMENT_CODE'] = $elementCode;
	}
}
