<?php namespace AB\Highload;
/** @var \CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var \CBitrixComponent $component */
/** @global \CUser $USER */
/** @global \CMain $APPLICATION */

use AB\Tools\Helpers\DataCache;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Highloadblock as HL;
use Dresscode\Main\ModifierComponentsFilter;

Main\Loader::includeModule('highloadblock');
Main\Loader::includeModule('iblock');

Loc::loadLanguageFile(__FILE__);

class Items extends \CBitrixComponent
{
	/** @var array */
	protected $blockType = null;

	/** @var  Main\Entity\Query */
	protected $query;

	/** @var  Main\ErrorCollection */
	private $errorCollection;

	/** @var Main\Type\Dictionary */
	private $select;

	private $navigation;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);
		$this->errorCollection = new Main\ErrorCollection();
		$this->select = new Main\Type\Dictionary([]);
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		if ((int)$arParams['BLOCK_TYPE'] == 0){
			$this->errorCollection->setError(new Main\Error('Не установлен тип списка'));
		}
		if ((int)$arParams['ITEMS_COUNT'] == 0){
			$arParams['ITEMS_COUNT'] = 20;
		}
		TrimArr($arParams['PROPERTY_CODE']);

		if(strlen($arParams['GUIDE_ID']) > 0){

		}

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

	/**
	 * Return settings script path with modified time postfix.
	 *
	 * @param string $componentPath Path to component.
	 * @param string $settingsName Settings name.
	 *
	 * @return string
	 */
	public static function getSettingsScript($componentPath, $settingsName)
	{
		$path = $componentPath.'/settings/'.$settingsName.'/script.js';
		$file = new Main\IO\File(Main\Application::getDocumentRoot().$path);

		return $path.'?'.$file->getModificationTime();
	}

	protected function initBlock($id = null)
	{
		if ((int)$id > 0){
			$this->arParams['BLOCK_TYPE'] = $id;
		}

		$this->blockType = HL\HighloadBlockTable::getRowById($this->arParams['BLOCK_TYPE']);
		if (is_null($this->blockType)){
			throw new \Exception('Тип списка не найден');
		}

		return $this;
	}

	protected function initEntity()
	{
		$this->query = new Main\Entity\Query(HL\HighloadBlockTable::compileEntity($this->blockType));

		return $this;
	}

	protected function buildFilter()
	{
		$this->query->setFilter(['UF_ACTIVE' => 1]);

		$mod = ModifierComponentsFilter::getInstance()->get($this->arParams['GUIDE_ID']);
		if(!is_null($mod)){
			foreach ($mod as $code => $item) {
				$this->query->addFilter($code, $item);
			}
		}
	}

	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		global $USER_FIELD_MANAGER;
		try {
			$this->navigation = new Main\UI\PageNavigation($this->arParams['PAGE_NAVIGATION_ID']);

			$page = $this->request->get($this->arParams['PAGE_NAVIGATION_ID']);
			$cache = new DataCache($this->arParams['CACHE_TIME'], '/dress/banners', md5(serialize($this->arParams).$page));
			if($this->arParams['CACHE_TYPE'] == 'N'){
				$cache->clear();
			}

			if($cache->isValid()){
				$this->arResult['ITEMS'] = $cache->getData();
			} else {

				$arFields = new Main\Type\Dictionary($USER_FIELD_MANAGER->GetUserFields('HLBLOCK_'.$this->arParams['BLOCK_TYPE'], 0, 'ru'));

				foreach ($this->arParams['PROPERTY_CODE'] as $code) {
					$alias = preg_replace('#^UF_#i', "", $code);
					$this->select->offsetSet($alias, $code);
				}

				$this->initBlock()->initEntity();
				$this->buildFilter();

				$this->query
					->setSelect($this->select->toArray())
					->setLimit($this->navigation->getLimit())
					->setOffset($this->navigation->getOffset());

				if (strlen($this->arParams['SORT_BY1']) > 0){
					$order = strlen($this->arParams['SORT_ORDER1']) > 0 ? $this->arParams['SORT_ORDER1'] : 'ASC';
					$this->query->addOrder($this->arParams['SORT_BY1'], $order);
				}
				if (strlen($this->arParams['SORT_BY2']) > 0){
					$order = strlen($this->arParams['SORT_ORDER2']) > 0 ? $this->arParams['SORT_ORDER2'] : 'ASC';
					$this->query->addOrder($this->arParams['SORT_BY2'], $order);
				}

				$fileEntity = \Bitrix\Main\FileTable::getEntity();
				$field = new Main\Entity\ExpressionField(
					'FORMAT_SIZE',
					'%s',
					array('FILE_SIZE')
				);
				$field->addFetchDataModifier(function ($value) {
					return \CFile::FormatSize($value);
				});
				$fileEntity->addField($field);

				if($arFields->offsetExists('UF_DETAIL_PHOTO') ){
					$this->query->registerRuntimeField(new Main\Entity\ReferenceField(
						'PICTURE_DETAIL',
						$fileEntity,
						['=this.UF_DETAIL_PHOTO' => 'ref.ID']
					));

					$this->query->addSelect('PICTURE_DETAIL.ID', 'DETAIL_PICTURE_ID');
					$this->query->addSelect('PICTURE_DETAIL.SUBDIR', 'DETAIL_PICTURE_SUBDIR');
					$this->query->addSelect('PICTURE_DETAIL.ORIGINAL_NAME', 'DETAIL_PICTURE_ORIGINAL_NAME');
				}

				if($arFields->offsetExists('UF_SMALL_PHOTO') ){
					$this->query->registerRuntimeField(new Main\Entity\ReferenceField(
						'PICTURE_SMALL',
						$fileEntity,
						['=this.UF_SMALL_PHOTO' => 'ref.ID']
					));
					$this->query->addSelect('PICTURE_SMALL.ID', 'PREVIEW_PICTURE_ID');
					$this->query->addSelect('PICTURE_SMALL.SUBDIR', 'PREVIEW_PICTURE_SUBDIR');
					$this->query->addSelect('PICTURE_SMALL.ORIGINAL_NAME', 'PREVIEW_PICTURE_ORIGINAL_NAME');
				}

				if($arFields->offsetExists('UF_FILE') ){
					$this->query->registerRuntimeField(new Main\Entity\ReferenceField(
						'FILE_IMG',
						$fileEntity,
						['=this.UF_FILE' => 'ref.ID']
					));
					$this->query->addSelect('FILE_IMG.ID', 'PICTURE_ID');
					$this->query->addSelect('FILE_IMG.SUBDIR', 'PICTURE_SUBDIR');
					$this->query->addSelect('FILE_IMG.ORIGINAL_NAME', 'PICTURE_ORIGINAL_NAME');
				}


				$cnt = $this->query->getEntity()->getDataClass()::getCount($this->query->getFilter());
				$this->query->countTotal($cnt);
				$this->navigation->setRecordCount($cnt);

//		    	PR($this->query->getQuery());

				$obItems = $this->query->exec();

				while ($item = $obItems->fetch()) {
					if((int)$item['DETAIL_PICTURE_ID'] > 0){
						$item['DETAIL_PICTURE_SRC'] = sprintf(
							'/upload/%s/%s',
							$item['DETAIL_PICTURE_SUBDIR'], $item['DETAIL_PICTURE_ORIGINAL_NAME']
						);
					}
					if((int)$item['PREVIEW_PICTURE_ID'] > 0){
						$item['PREVIEW_PICTURE_SRC'] = sprintf(
							'/upload/%s/%s',
							$item['PREVIEW_PICTURE_SUBDIR'], $item['PREVIEW_PICTURE_ORIGINAL_NAME']
						);
					}
					if((int)$item['PICTURE_ID'] > 0){
						$item['PICTURE_SRC'] = sprintf(
							'/upload/%s/%s',
							$item['PICTURE_SUBDIR'], $item['PICTURE_ORIGINAL_NAME']
						);
					}

					if((int)$item['SECTION'] > 0){
						$item['SECTION'] = \CIBlockSection::GetList(
							array(),
							array('=ID' => $item['SECTION']),
							false,
							array('ID','IBLOCK_ID','SECTION_PAGE_URL')
						)->GetNext(true, false);
						$item['LINK'] = $item['SECTION']['SECTION_PAGE_URL'];
					}

					$this->arResult['ITEMS'][] = $item;
				}

				$cache->addCache($this->arResult['ITEMS']);
			}

		} catch (\Exception $err) {
			$this->errorCollection->setError(new Main\Error($err->getMessage(), $err->getCode()));
		}

		if ($this->errorCollection->count() > 0){
			/** @var Main\Error $err */
			foreach ($this->errorCollection as $err) {
				\ShowError($err->getMessage());
			}
		} else {
			$this->includeComponentTemplate();
		}

	}
}