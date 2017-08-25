<?php namespace Dress\Catalog;
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
use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web;
use Online1c\Iblock;
use Bitrix\Highloadblock as HL;

Main\Loader::includeModule('online1c.iblock');
Main\Loader::includeModule('iblock');
Main\Loader::includeModule('catalog');
Main\Loader::includeModule('sale');
Main\Loader::includeModule('highloadblock');

Loc::loadLanguageFile(__FILE__);

class ProductList extends \CBitrixComponent
{
	protected $customFilter = [];

	/** @var  Main\UI\PageNavigation */
	protected $navigation;

	protected $nameCmp = 'ad:product.list';

	/** @var  Main\Type\Dictionary */
	protected $queryParams;

	/**
	 * @param \CBitrixComponent|bool $component
	 */
	function __construct($component = false)
	{
		parent::__construct($component);

		$this->queryParams = new Main\Type\Dictionary([
			'select' => [],
			'filter' => [],
			'order' => []
		]);
	}

	/**
	 * Return parsed conditions array.
	 *
	 * @param $condition
	 * @param $params
	 *
	 * @return array
	 */
	protected function parseCondition($condition, $params)
	{
		$result = array();

		if (!empty($condition) && is_array($condition)){
			if ($condition['CLASS_ID'] === 'CondGroup'){
				if (!empty($condition['CHILDREN'])){
					foreach ($condition['CHILDREN'] as $child) {
						$childResult = $this->parseCondition($child, $params);

						// is group
						if ($child['CLASS_ID'] === 'CondGroup'){
							$result[] = $childResult;
						} // same property names not overrides each other
						elseif (isset($result[key($childResult)])) {
							$fieldName = key($childResult);

							if (!is_array($result[$fieldName]) || !isset($result[$fieldName]['LOGIC'])){
								$result[$fieldName] = array(
									'LOGIC' => $condition['DATA']['All'],
									$result[$fieldName],
								);
							}

							$result[$fieldName][] = $childResult[$fieldName];
						} else {
							$result += $childResult;
						}
					}

					if (!empty($result)){
						$this->parsePropertyCondition($result, $condition, $params);

						if (count($result) > 1){
							$result['LOGIC'] = $condition['DATA']['All'];
						}
					}
				}
			} else {
				$result += $this->parseConditionLevel($condition, $params);
			}
		}

		return $result;
	}

	protected function parseConditionLevel($condition, $params)
	{
		$result = array();

		if (!empty($condition) && is_array($condition)){
			$name = $this->parseConditionName($condition);
			if (!empty($name)){
				$operator = $this->parseConditionOperator($condition);
				$value = $this->parseConditionValue($condition, $name);
				$result[$operator.$name] = $value;

				if ($name === 'SECTION_ID'){
					$result['INCLUDE_SUBSECTIONS'] = isset($params['INCLUDE_SUBSECTIONS']) && $params['INCLUDE_SUBSECTIONS'] === 'N' ? 'N' : 'Y';

					if (isset($params['INCLUDE_SUBSECTIONS']) && $params['INCLUDE_SUBSECTIONS'] === 'A'){
						$result['SECTION_GLOBAL_ACTIVE'] = 'Y';
					}

					$result = array($result);
				}
			}
		}

		return $result;
	}

	protected function parsePropertyCondition(array &$result, array $condition, $params)
	{
		if (!empty($result)){
			$subFilter = array();

			$metaProperty = new Main\Type\Dictionary();
			$tmpProps = Iblock\BaseProperty::getMetaProperty($params['IBLOCK_ID']);
			foreach ($tmpProps as $value) {
				$metaProperty->offsetSet($value['ID'], $value['CODE']);
			}

			foreach ($result as $name => $value) {
				if (($ind = strpos($name, 'CondIBProp')) !== false){
					list($prefix, $iblock, $propertyId) = explode(':', $name);
					$operator = $ind > 0 ? substr($prefix, 0, $ind) : '';

					$catalogInfo = \CCatalogSku::GetInfoByIBlock($iblock);
					if (!empty($catalogInfo)){
						if (
							$catalogInfo['CATALOG_TYPE'] != \CCatalogSku::TYPE_CATALOG
							&& $catalogInfo['IBLOCK_ID'] == $iblock
						){
							$subFilter[$operator.'PROPERTY.'.$metaProperty->get($propertyId)] = $value;
						} else {
							$result[$operator.'PROPERTY.'.$metaProperty->get($propertyId)] = $value;
						}
					}

					unset($result[$name]);
				}
			}
		}
	}

	protected function parseConditionName(array $condition)
	{
		$name = '';
		$conditionNameMap = array(
			'CondIBXmlID' => 'XML_ID',
//			'CondIBActive' => 'ACTIVE',
			'CondIBSection' => 'SECTION_ID',
			'CondIBDateActiveFrom' => 'DATE_ACTIVE_FROM',
			'CondIBDateActiveTo' => 'DATE_ACTIVE_TO',
			'CondIBSort' => 'SORT',
			'CondIBDateCreate' => 'DATE_CREATE',
			'CondIBCreatedBy' => 'CREATED_BY',
			'CondIBTimestampX' => 'TIMESTAMP_X',
			'CondIBModifiedBy' => 'MODIFIED_BY',
			'CondIBTags' => 'TAGS',
			'CondCatQuantity' => 'CATALOG_QUANTITY',
			'CondCatWeight' => 'CATALOG_WEIGHT',
		);

		if (isset($conditionNameMap[$condition['CLASS_ID']])){
			$name = $conditionNameMap[$condition['CLASS_ID']];
		} elseif (strpos($condition['CLASS_ID'], 'CondIBProp') !== false) {
			$name = $condition['CLASS_ID'];
		}

		return $name;
	}

	protected function parseConditionOperator($condition)
	{
		$operator = '';

		switch ($condition['DATA']['logic']) {
			case 'Equal':
				$operator = '=';
				break;
			case 'Not':
				$operator = '!';
				break;
			case 'Contain':
				$operator = '%';
				break;
			case 'NotCont':
				$operator = '!%';
				break;
			case 'Great':
				$operator = '>';
				break;
			case 'Less':
				$operator = '<';
				break;
			case 'EqGr':
				$operator = '>=';
				break;
			case 'EqLs':
				$operator = '<=';
				break;
		}

		return $operator;
	}

	protected function parseConditionValue($condition, $name)
	{
		$value = $condition['DATA']['value'];

		switch ($name) {
			case 'DATE_ACTIVE_FROM':
			case 'DATE_ACTIVE_TO':
			case 'DATE_CREATE':
			case 'TIMESTAMP_X':
				$value = ConvertTimeStamp($value, 'FULL');
				break;
		}

		return $value;
	}

	/**
	 * @method onPrepareComponentParams
	 * @param array $arParams
	 *
	 * @return array
	 */
	public function onPrepareComponentParams($arParams)
	{
		if(strlen($arParams['CUSTOM_FILTER']) > 0 && substr($arParams['CUSTOM_FILTER'], 0, 1) == '{'){
			$customFilter = Web\Json::decode($arParams['CUSTOM_FILTER']);
			$arParams['CUSTOM_FILTER'] = $this->parseCondition($customFilter, $arParams);
			$this->customFilter = $arParams['CUSTOM_FILTER'];
		}

		if (strlen($arParams['PAGE_NAVIGATION_ID']) == 0){
			$arParams['PAGE_NAVIGATION_ID'] = 'page';
		}

		TrimArr($arParams['PROPERTY_CODE']);

		$arParams['ELEMENT_SORT_FIELD'] = strtoupper($arParams['ELEMENT_SORT_FIELD']);
		$arParams['ELEMENT_SORT_FIELD2'] = strtoupper($arParams['ELEMENT_SORT_FIELD2']);
		$arParams['ELEMENT_SORT_ORDER'] = strtoupper($arParams['ELEMENT_SORT_ORDER']);
		$arParams['ELEMENT_SORT_ORDER2'] = strtoupper($arParams['ELEMENT_SORT_ORDER2']);

		$arParams['PAGE_ELEMENT_COUNT'] = (int)$arParams['PAGE_ELEMENT_COUNT'];
		if($arParams['PAGE_ELEMENT_COUNT'] == 0){
			$arParams['PAGE_ELEMENT_COUNT'] = 18;
		}

		if($this->request->get('temple')){
			$param = unserialize(base64_decode($_SESSION['CMP'][$this->nameCmp.':'.$this->request->get('temple')]));
			if(count($param) > 0){
				$this->arParams = $param;
			}
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

	protected function makeSubSectionFilter($currentSection = [])
	{
		$result = [];
		if((int)$currentSection['ID'] > 0){
			$oSections = Iblock\SectionTable::getList([
				'filter' => [
					'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
					'=ACTIVE' => 'Y',
					'=GLOBAL_ACTIVE' => 'Y',
					'>=LEFT_MARGIN' => $currentSection['LEFT_MARGIN'],
					'<=RIGHT_MARGIN' => $currentSection['RIGHT_MARGIN'],
				],
				'select' => ['ID'],
				'limit' => 30,
				'order' => ['LEFT_MARGIN' => 'ASC','SORT' => 'ASC']
			]);

			while ($res = $oSections->fetch()){
				$result[] = $res['ID'];
			}
		}

		return $result;
	}

	protected function buildFilter($filter = [])
	{
		$result = [
			'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			'=ACTIVE' => 'Y',
		];

		$filterSection = [
			'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
			'=ACTIVE' => 'Y',
			'=GLOBAL_ACTIVE' => 'Y',
		];
		$selectSection = [
			'ID','NAME','SECTION_CODE' => 'CODE', 'IBLOCK_ID',
			'LEFT_MARGIN', 'RIGHT_MARGIN'
		];
		$currentSection = null;
		if(intval($this->arParams['SECTION_ID']) > 0){
			$filterSection['=ID'] = $this->arParams['SECTION_ID'];
			$currentSection = Iblock\SectionTable::getRow([
				'select' => $selectSection,
				'filter' => $filterSection
			]);
		} else if(strlen($this->arParams['SECTION_CODE']) > 0) {
			$filterSection['=CODE'] = $this->arParams['SECTION_CODE'];
			$currentSection = Iblock\SectionTable::getRow([
				'select' => $selectSection,
				'filter' => $filterSection
			]);
		}

		if(!is_null($currentSection)){
			$subSectionIds = $this->makeSubSectionFilter($currentSection);
			if(count($subSectionIds) > 0){
				$result['IBLOCK_SECTION_ID'] = $subSectionIds;
			}
		}

		$this->queryParams->offsetSet('filter', array_merge($result, $filter, $this->customFilter));

		return $this;
	}

	protected function buildSelect($select = [])
	{
		$result = [
			'ID', 'NAME', 'IBLOCK_ID', 'CODE', 'XML_ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
			'PREVIEW_TEXT', 'DETAIL_TEXT', 'ACTIVE',
			'SECTION_ID' => 'IBLOCK_SECTION_ID', 'SECTION_CODE' => 'IBLOCK_SECTION.CODE',
			'ELEMENT_CODE' => 'CODE'
		];

		foreach ($this->arParams['PROPERTY_CODE'] as $code) {
			$result[$code] = 'PROPERTY.'.$code;
		}

		$this->queryParams->offsetSet('select', array_merge($result, $select));

		return $this;
	}

	protected function buildOrder($order = [])
	{
		$result = [];
		if(!$this->request->get('sort')){
			if(strlen($this->arParams['ELEMENT_SORT_FIELD']) > 0 && strlen($this->arParams['ELEMENT_SORT_ORDER']) > 0){
				$result[$this->arParams['ELEMENT_SORT_FIELD']] = $this->arParams['ELEMENT_SORT_ORDER'];
			}
			if(strlen($this->arParams['ELEMENT_SORT_FIELD2']) > 0 && strlen($this->arParams['ELEMENT_SORT_ORDER2']) > 0){
				$result[$this->arParams['ELEMENT_SORT_FIELD2']] = $this->arParams['ELEMENT_SORT_ORDER2'];
			}
		} else {
			$order = [];
			switch ($this->request->get('sort')){
				case 'price_desc':
					$order['PRICE.PRICE'] = 'DESC';
					break;
				case 'price_asc':
					$order['PRICE.PRICE'] = 'ASC';
					break;
				case 'date_update':
					$order['ID'] = 'DESC';
					break;
			}
		}

		$this->queryParams->offsetSet('order', array_merge($result, $order));

		return $this;
	}

	private function getUrlOptions()
	{
		$detailUrl = $this->arParams['DETAIL_URL'];
		if(strlen($detailUrl) == 0){
			$iblock = \Bitrix\Iblock\IblockTable::getRowById($this->arParams['IBLOCK_ID']);
			$detailUrl = $iblock['DETAIL_PAGE_URL'];
		}
		$detailUrl = str_replace("#SITE_DIR#", '', $detailUrl);

		return $detailUrl;
	}

	protected function getProductIterator()
	{

		$this->navigation = new Main\UI\PageNavigation($this->arParams['PAGE_NAVIGATION_ID']);
		$this->navigation->allowAllRecords(false)
			->setPageSize($this->arParams['PAGE_ELEMENT_COUNT'])
			->initFromUri();

		$select = $this->queryParams->get('select');

		$select['PRICE_VALUE'] = 'PRICE.PRICE';
		$select['QUANTITY'] = 'CATALOG.QUANTITY';

		$obItems = Iblock\Element::getList([
			'select' => $select,
			'filter' => $this->queryParams->get('filter'),
			'order' => $this->queryParams->get('order'),
			'count_total' => true,
			'limit' => $this->navigation->getLimit(),
			'offset' => $this->navigation->getOffset(),
			'runtime' => [
				new Main\Entity\ReferenceField(
					'CATALOG',
					\Bitrix\Catalog\ProductTable::getEntity(),
					['=this.ID' => 'ref.ID']
				),
				new Main\Entity\ReferenceField(
					'PRICE',
					\Bitrix\Catalog\PriceTable::getEntity(),
					['=this.ID' => 'ref.PRODUCT_ID']
				)
			]
		]);

		$this->arResult['COUNT_ITEMS'] = $obItems->getCount();
		$this->navigation->setRecordCount($this->arResult['COUNT_ITEMS']);

		$this->arResult['COUNT_ITEMS'] = \SaleFormatCurrency($this->arResult['COUNT_ITEMS'], 'RUB', true);
		$this->arResult['COUNT_ITEMS_FORMAT'] = self::getCountProductsFormat($this->arResult['COUNT_ITEMS']);

		return $obItems;
	}

	protected function fromSmartFilter()
	{
		if($this->request->get('set_filter') == 'Y'){
			$propCodes = array_keys($this->request->toArray());
			$meta = \Online1c\Iblock\BaseProperty::getMetaProperty($this->arParams['IBLOCK_ID']);
			$filter = [];
			foreach ($propCodes as $code) {
				if(array_key_exists($code, $meta)){
					if($meta[$code]['MULTIPLE'] != 'Y'){
						$filter['PROPERTY.'.$code] = explode(',', $this->request->get($code));
					}
					else {
						$filter['PROPERTY.'.$code.'_BIND.VALUE'] = explode(',', $this->request->get($code));
					}
				}
			}

			$this->buildFilter($filter);

			return true;
		}

		return false;
	}

	public function refreshDataAction($data = [])
	{
		if((int)$this->arParams['IBLOCK_ID'] == 0)
			return [];

		$this->buildFilter();

		$this->fromSmartFilter();

		$this->buildOrder()->buildSelect();

		$iterator = $this->getProductIterator();
		$iterator->addFetchDataModifier(Iblock\Helpers\PageUrlHelper::makeDetailUrl($this->getUrlOptions()));

		$result = ['items' => [], 'nav' => []];
		while ($product = $iterator->fetch()){
			$result['items'][] = $this->prepareResultItem($product);
		}

		$result['nav'] = [
			'page' => $this->navigation->getCurrentPage(),
			'totalPages' => $this->navigation->getPageCount(),
			'limit' => $this->navigation->getLimit(),
			'total' => $this->navigation->getRecordCount()
		];

		return $result;
	}

	public static function getCountProductsFormat($number)
	{
		$titles = ['товар','товара','товаров'];
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100 > 4 && $number %100 < 20) ? 2 : $cases[min($number%10, 5)] ];
	}
	/**
	 * @method executeComponent
	 * @return mixed|void
	 */
	public function executeComponent()
	{
		$this->buildFilter();
		$page = $this->request->get($this->arParams['PAGE_NAVIGATION_ID']);
		$cacheId = md5(serialize($this->arParams).$page);

		$globalFilter = \Dresscode\Main\ModifierComponentsFilter::getInstance()->get($this->arParams['FILTER_NAME']);
		if(count($globalFilter) > 0){
			$this->buildFilter($globalFilter);
			$cacheId = md5($cacheId.serialize($globalFilter));
		}

		$cache = new DataCache($this->arParams['CACHE_TIME'], '/dress/catalog/list', $cacheId);

		if($this->arParams['CACHE_TYPE'] == 'N'){
			$cache->clear();
		}

		if($this->fromSmartFilter()){
			$cache->clear();
		} elseif(count($globalFilter) > 0) {
			$this->buildFilter($globalFilter);
		}

		$products = [];

		if($cache->isValid()){
			$products = $cache->getData();
		} else {

			$this->buildSelect()->buildOrder();

			$iterator = $this->getProductIterator();

			$urlOption = $this->getUrlOptions();
			$iterator->addFetchDataModifier(Iblock\Helpers\PageUrlHelper::makeDetailUrl($urlOption));

			while ($element = $iterator->fetch()){
				$products['ITEMS'][] = $this->prepareResultItem($element);
			}
			$products['NAV'] = $this->navigation;

			$cache->addCache($products);
		}

		$this->arResult['ITEMS'] = $products['ITEMS'];
		$this->arResult['NAV'] = $products['NAV'];

//		dump($arParams);
		$this->includeComponentTemplate();

		$params = [];
		foreach ($this->arParams as $code => $param) {
			if(substr($code, 0, 1) !== '~'){
				$params[$code] = $param;
			}
		}
		$_SESSION['CMP'][$this->nameCmp.':'.$this->getTemplate()->GetName()] = base64_encode(serialize($params));
	}

	/**
	 * @method prepareResultItem
	 * @param $element
	 *
	 * @return array
	 */
	protected function prepareResultItem($element)
	{
		if((int)$element['DETAIL_PICTURE']){
			$element['SMALL_PICTURE'] = \CFile::ResizeImageGet(
				$element['DETAIL_PICTURE'],
				['width' => $this->arParams['PREVIEW_IMG_WIDTH'], 'height' => $this->arParams['PREVIEW_IMG_HEIGHT']],
				BX_RESIZE_IMAGE_PROPORTIONAL_ALT
			);
		}

		$element['PRICE_FORMAT'] = \SaleFormatCurrency($element['PRICE_VALUE'], 'RUB', true);

		return $element;
	}

	/**
	 * @method getNavigation - get param navigation
	 * @return Main\UI\PageNavigation
	 */
	public function getNavigation()
	{
		return $this->navigation;
	}

}