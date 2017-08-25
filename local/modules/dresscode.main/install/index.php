<?
IncludeModuleLangFile(__FILE__);
use Bitrix\Main\ModuleManager;

if (class_exists("dresscode_main"))
	return;

class dresscode_main extends CModule
{
	public $MODULE_ID = "dresscode.main";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $MODULE_CSS;
	public $PARTNER_NAME;
	public $PARTNER_URI;

	protected $eventManager;

	protected $events = [];

	function __construct()
	{
		$arModuleVersion = array();

		include(dirname(__FILE__) . "/version.php");

		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

		$this->MODULE_NAME = 'dresscode';

		$this->eventManager = \Bitrix\Main\EventManager::getInstance();
	}

	public function DoInstall()
	{
		ModuleManager::registerModule($this->MODULE_ID);

		return true;
	}

	public function DoUninstall()
	{
		ModuleManager::unRegisterModule($this->MODULE_ID);

		return true;
	}
}