<?php
require_once ($_SERVER['DOCUMENT_ROOT'].'/local/lib/vendor/autoload.php');
spl_autoload_register(function ($className) {
	preg_match('/^(.*?)([\w]+)$/i', $className, $matches);
	if (count($matches) < 3){
		return;
	}

	$filePath = implode(DIRECTORY_SEPARATOR, array(
		__DIR__,
		"lib",
		str_replace('\\', DIRECTORY_SEPARATOR, trim($matches[1], '\\')),
		str_replace('_', DIRECTORY_SEPARATOR, $matches[2]).'.php',
	));
	$filePath = str_replace('Dresscode'.DIRECTORY_SEPARATOR.'Main'.DIRECTORY_SEPARATOR, '', $filePath);
	$filePath = preg_replace('#Dresscode\/Main#', '', $filePath);
	$filePath = str_replace(DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $filePath);

	if (is_readable($filePath) && is_file($filePath)){
		/** @noinspection PhpIncludeInspection */
		require_once $filePath;
	}
});

$path = \Dresscode\Main\Config::getModuleInfo()->get('asset');
$regJsLibs = [
	'jquery3' => [
		'js' => $path.'/js/jquery-3.2.1.min.js'
	],
	'fancybox3' => [
		'js' => [$path.'/js/fancybox/jquery.fancybox.min.js'],
		'css' => [$path.'/js/fancybox/jquery.fancybox.min.css']
	]
];
foreach ($regJsLibs as $lib => $data) {
	CJSCore::RegisterExt($lib, $data);
}