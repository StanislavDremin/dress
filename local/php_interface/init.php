<?php
define('AB_DEBUG', true);
define('BX_DEBUG', 'Y');

function PR($o, $show = false)
{
	global $USER;
	if ($USER->IsAdmin() || $show){
		$bt = debug_backtrace();
		$bt = $bt[0];
		$dRoot = $_SERVER["DOCUMENT_ROOT"];
		$dRoot = str_replace("/", "\\", $dRoot);
		$bt["file"] = str_replace($dRoot, "", $bt["file"]);
		$dRoot = str_replace("\\", "/", $dRoot);
		$bt["file"] = str_replace($dRoot, "", $bt["file"]);
		?>
		<div style='font-size:9pt; color:#000; background:#fff; border:1px dashed #000;'>
			<div style='padding:3px 5px; background:#99CCFF; font-weight:bold;'>File: <?=$bt["file"]?> [<?=$bt["line"]?>
				]
			</div>
			<pre style='padding:10px; text-align: left'><? print_r($o) ?></pre>
		</div>
		<?
	} else {
		return false;
	}
}

\Bitrix\Main\Loader::includeModule('ab.tools');
\Bitrix\Main\Loader::includeModule('digitalwand.admin_helper');
\Bitrix\Main\Loader::includeModule('dresscode.main');

spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3){
		return;
	}

	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"../lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
//		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]).'.php',
		$matches[2].'.php',
	));
	$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);
	if (is_readable($filePath) && is_file($filePath)){
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

$EventManager = \Bitrix\Main\EventManager::getInstance();

/** ============================= сброс кеша списка категорий ======================================================= */

$EventManager->addEventHandlerCompatible(
	'iblock',
	'OnAfterIBlockSectionUpdate',
	array('Dresscode\Main\EventHandlers\IblockData', 'clearSectionMenuCache')
);
$EventManager->addEventHandlerCompatible(
	'iblock',
	'OnAfterIBlockSectionAdd',
	array('Dresscode\Main\EventHandlers\IblockData', 'clearSectionMenuCache')
);
$EventManager->addEventHandlerCompatible(
	'iblock',
	'OnAfterIBlockSectionDelete',
	array('Dresscode\Main\EventHandlers\IblockData', 'clearSectionMenuCache')
);
/** ================================================================================================================= */
/** ==================== свойство соотношения цыета и размера ======================================================= */
$EventManager->addEventHandlerCompatible(
	'iblock',
	'OnIBlockPropertyBuildList',
	array('Dresscode\Main\CustomProperties\RatioSizeColor', 'GetUserTypeDescription')
);
/** ================================================================================================================= */