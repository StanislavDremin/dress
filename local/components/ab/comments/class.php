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

use AB\Tools\Helpers\DateFetchConverter;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Dresscode\Main\Config;
use Online1c\Iblock;
use Bitrix\Highloadblock\HighloadBlockTable as HL;

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('highloadblock');

Loc::loadLanguageFile(__FILE__);

class Comments extends \CBitrixComponent
{
	protected $mainSection = [
		'review' => 44,
		'comments' => 43,
		'question' => 42
	];

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		return $arParams;
	}

	/**
	 * @method getUser
	 * @return \CUser
	 */
	public function getUser(){
		global $USER;

		if(!is_object($USER)){
			$USER = new \CUser();
		}

		return $USER;
	}

	public function getReviewsAction($data = [])
	{
		return $this->getReviews($data['element']);
	}

	public function getReviews($elementId)
	{
		$elementId = (int)$elementId;
		if($elementId == 0)
			throw new \Exception('Нет элемента', 500);

		$result = [];
		$iterator = Iblock\Element::getList([
			'select' => [
				'ID','NAME','IBLOCK_ID','DATE_CREATE', 'PREVIEW_TEXT',
				'RATING' => 'PROPERTY.RATING',
				'ACORD_PHOTO' => 'PROPERTY.ACORD_PHOTO',
				'ACORD_SIZE' => 'PROPERTY.ACORD_SIZE',
				'ACORD_DESC' => 'PROPERTY.ACORD_DESC',
				'COLOR' => 'PROPERTY.COLOR',
				'SIZE' => 'PROPERTY.SIZE',
				'AVAIL' => 'PROPERTY.AVAIL',
				'USER_LOGIN' => 'CREATED_BY_USER.LOGIN',
				'USER_SHORT_NAME' => 'CREATED_BY_USER.SHORT_NAME',
			],
			'filter' => [
				'IBLOCK_ID' => Config::getIblock('COMMENTS'),
				'=XML_ID' => $elementId,
				'=IBLOCK_SECTION_ID' => $this->mainSection['review']
			],
			'limit' => 20,
			'order' => ['ID' => 'DESC']
		]);

		while ($res = $iterator->fetch()){
			if($res['DATE_CREATE'] instanceof Main\Type\DateTime){
				$timeStamp = $res['DATE_CREATE']->getTimestamp();
				$res['DATE'] = \FormatDate("x", $timeStamp);
			}
			$res['AVAIL'] = (int)$res['AVAIL'];
			$res['RATING'] = (int)$res['RATING'];

			$result[] = $res;
		}

		unset($iterator);

		return $result;
	}

	public function getCommentsAction()
	{
		$elementId = (int)$this->request->get('element');
		if($elementId == 0)
			throw new \Exception('Нет элемента', 500);

		$iterator = Iblock\Element::getList([
			'select' => [
				'ID','NAME','IBLOCK_ID','DATE_CREATE', 'PREVIEW_TEXT',
				'USER_LOGIN' => 'CREATED_BY_USER.LOGIN',
				'USER_SHORT_NAME' => 'CREATED_BY_USER.SHORT_NAME',
			],
			'filter' => [
				'IBLOCK_ID' => Config::getIblock('COMMENTS'),
				'=XML_ID' => $elementId,
				'=IBLOCK_SECTION_ID' => $this->mainSection['comments']
			],
			'limit' => 20,
			'order' => ['ID' => 'DESC'],
		]);
		$result = [];
		while ($res = $iterator->fetch()){
			if($res['DATE_CREATE'] instanceof Main\Type\DateTime){
				$timeStamp = $res['DATE_CREATE']->getTimestamp();
				$res['DATE'] = \FormatDate("x", $timeStamp);
			}

			$result[] = $res;
		}

		unset($iterator);

		return $result;
	}

	public function getQuestionsAction()
	{
		$elementId = (int)$this->request->get('element');
		if($elementId == 0)
			throw new \Exception('Нет элемента', 500);

		$iterator = Iblock\Element::getList([
			'select' => [
				'ID','NAME','IBLOCK_ID','DATE_CREATE', 'PREVIEW_TEXT',
				'USER_LOGIN' => 'CREATED_BY_USER.LOGIN',
				'USER_SHORT_NAME' => 'CREATED_BY_USER.SHORT_NAME',
			],
			'filter' => [
				'IBLOCK_ID' => Config::getIblock('COMMENTS'),
				'=XML_ID' => $elementId,
				'=IBLOCK_SECTION_ID' => $this->mainSection['question']
			],
			'limit' => 20,
			'order' => ['ID' => 'DESC'],
		]);
		$result = [];
		while ($res = $iterator->fetch()){
			if($res['DATE_CREATE'] instanceof Main\Type\DateTime){
				$timeStamp = $res['DATE_CREATE']->getTimestamp();
				$res['DATE'] = \FormatDate("x", $timeStamp);
			}

			$result[] = $res;
		}

		unset($iterator);

		return $result;
	}

	public function getParametersAction($data = [])
	{
		$product = Iblock\Element::getRow([
			'select' => ['ID','NAME','IBLOCK_ID', 'COLORS' => 'PROPERTY.COLOR', 'SIZES' => 'PROPERTY.SIZE'],
			'filter' => ['=ID' => $data['id'], 'IBLOCK_ID' => Config::getIblock('CATALOG')]
		]);

		$result = [
			'sizes' => $this->getData(5, $product['SIZES']['VALUE']),
			'colors' => $this->getData(1, $product['COLORS']['VALUE'])
		];

		return $result;
	}

	protected function getData($idBlock, $items = [])
	{
		$dataClass = HL::compileEntity(HL::getRowById($idBlock))->getDataClass();
		return $dataClass::getList([
			'filter' => ['=UF_XML_ID' => $items],
			'select' => ['ID', 'UF_NAME']
		])->fetchAll();
	}

	public function saveReviewAction($data = [])
	{
		$CIBlockElement = new \CIBlockElement();
		$res = (int)$CIBlockElement->Add([
			'IBLOCK_ID' => Config::getIblock('COMMENTS'),
			'NAME' => 'Отзыв '.date('d.m.Y H:i:s'),
			'ACTIVE' => 'Y',
			'PREVIEW_TEXT' => $data['text'],
			'XML_ID' => $data['element'],
			'IBLOCK_SECTION_ID' => $this->mainSection['review'],
			'PROPERTY_VALUES' => [
				'RATING' => $data['rating'],
				'ACORD_PHOTO' => $data['accordPhoto'],
				'ACORD_SIZE' => $data['accordSize'],
				'ACORD_DESC' => $data['accordDesc'],
				'COLOR' => $data['color'],
				'SIZE' => $data['size'],
				'ELEMENT_ID' => $data['element'],
				'AVAIL' => 0,
			]
		]);
		if($res == 0){
			throw new \Exception(strip_tags($CIBlockElement->LAST_ERROR), 502);
		}
		return $res;
	}

	public function saveCommentAction($data = [])
	{
		$CIBlockElement = new \CIBlockElement();
		$res = (int)$CIBlockElement->Add([
			'IBLOCK_ID' => Config::getIblock('COMMENTS'),
			'NAME' => 'Комментарий '.date('d.m.Y H:i:s'),
			'ACTIVE' => 'Y',
			'PREVIEW_TEXT' => $data['text'],
			'XML_ID' => $data['element'],
			'IBLOCK_SECTION_ID' => $this->mainSection['comments'],
		]);
		if($res == 0){
			throw new \Exception(strip_tags($CIBlockElement->LAST_ERROR), 502);
		}
		return $res;
	}

	public function saveQuestionAction($data = [])
	{
		$CIBlockElement = new \CIBlockElement();
		$res = (int)$CIBlockElement->Add([
			'IBLOCK_ID' => Config::getIblock('COMMENTS'),
			'NAME' => 'Вопрос '.date('d.m.Y H:i:s'),
			'ACTIVE' => 'Y',
			'PREVIEW_TEXT' => $data['text'],
			'XML_ID' => $data['element'],
			'IBLOCK_SECTION_ID' => $this->mainSection['question'],
		]);
		if($res == 0){
			throw new \Exception(strip_tags($CIBlockElement->LAST_ERROR), 502);
		}
		return $res;
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->includeComponentTemplate();
	}
}