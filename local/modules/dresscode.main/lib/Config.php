<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 17.07.2017
 */

namespace Dresscode\Main;

use Illuminate\Container\Container;

use Bitrix\Main;

class Config
{
	private $request;
	private static $myCity = null;

	/** @var \Dresscode\Main\Config */
	private static $instance = null;

	private static $iblocks = [
		'CATALOG' => 2,
		'COMMENTS' => 4
	];

	private static $personTypes = [
		'FIZ' => 1,
		'UR' => 2
	];

	const TOP_MENU_CACHE = 'top_section';
	const LEFT_MENU_CACHE = 'left_sections';

	public function __construct()
	{
		$this->request = Main\Context::getCurrent()->getRequest();
		self::$myCity = CityServices::getMyCity();
	}

	public function currentDir()
	{
		return $this->request->getRequestedPageDirectory();
	}

	public function currentPage()
	{
		return $this->request->getRequestedPage();
	}

	public function currentUri()
	{
		return $this->request->getRequestUri();
	}

	/**
	 * @method instance
	 * @return Config
	 */
	public static function instance()
	{
		if (is_null(self::$instance)){
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * @method getMyCity - get param myCity
	 * @return mixed|null
	 */
	public static function getMyCity()
	{
		return self::$myCity;
	}

	public static function getUser()
	{
		global $USER;
		if (!is_object($USER))
			$USER = new \CUser();

		return $USER;
	}

	public static function getUserName()
	{
		if (self::getUser()->IsAuthorized()){
			$result = '';
			$login = self::getUser()->GetLogin();
			$email = self::getUser()->GetEmail();
			$fio = self::getUser()->GetFormattedName();
			if (strlen($fio) > 0){
				return $fio;
			}
			if (strlen($login) > 0){
				return $login;
			}
			if (strlen($email) > 0){
				return $email;
			}
		}

		return null;
	}

	/**
	 * @method getIblock
	 * @param $code
	 *
	 * @return int|null
	 */
	public static function getIblock($code)
	{
		$iblock = null;
		if (strlen($code) > 0){
			if (self::$iblocks[$code])
				$iblock = self::$iblocks[$code];
		}

		return $iblock;
	}

	/**
	 * @method getModuleInfo
	 * @return Main\Type\Dictionary
	 */
	public static function getModuleInfo()
	{
		$localPath = '/'.Main\IO\Path::combine('local', 'modules', 'dresscode.main');
		$root = Main\Application::getDocumentRoot();
		$dir = new Main\IO\Directory($root.$localPath);

		$result = [
			'localPath' => '/local/modules/dresscode.main',
			'physicalPath' => $dir->getPhysicalPath(),
		];
		/** @var Main\IO\Directory $child */
		foreach ($dir->getChildren() as $child) {
			if ($child->getName() === 'asset'){
				$result['asset'] = Main\IO\Path::combine($localPath, 'asset');
			}
		}

		return new Main\Type\Dictionary($result);
	}

	public static function addRatioPropertyJs()
	{
		$modulePath = self::getModuleInfo()->get('asset');

		if (file_exists(self::getModuleInfo()->get('physicalPath').'/asset/js/ratioProperty.min.js')){
			$ratioJs = 'ratioProperty.min.js';
		} else {
			$ratioJs = 'ratioProperty.js';
		}
		$jsExt = [
			'ratio_property' => [
				'js' => [
					$modulePath.'/js/'.$ratioJs,
				],
				'css' => [
					$modulePath.'/js/ratioProperty.css',
					$modulePath.'/css/_admin.css'
				],
			],
		];
		foreach ($jsExt as $name => $item) {
			\CJSCore::RegisterExt($name, $item);
		}

		\CJSCore::Init(['isjs', 'animatecss', 'font_fa', 'jquery3', 'sweetalert', 'popup', 'vue', 'ratio_property']);
	}

	public static function getPersonTypes($code)
	{
		return !empty(self::$personTypes[$code]) ? self::$personTypes[$code] : null;
	}

}