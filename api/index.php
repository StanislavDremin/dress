#!/usr/bin/env php
<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__).'/..');
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include.php");


use InSales\API\ApiClient;
use Dresscode\Main\InSaleImport;
use Bitrix\Main;

Main\Loader::includeModule('iblock');
/*
 * Acess-Key
Идентификатор5329f7fc792e1b9bd843726637e6614f
Пароль41bf6b43bc004f2c64ca76df9f27fa02
Формат URLhttp://apikey:password@hostname/admin/resource.xml
Пример URLhttp://5329f7fc792e1b9bd843726637e6614f:41bf6b43bc004f2c64ca76df9f27fa02@www.dresscod.org/admin/orders.xml
Дата подключения18.07.2017
Права
 *
 * */
//$client = new \InSales\API\ApiClient(
//	'5329f7fc792e1b9bd843726637e6614f',
//	'41bf6b43bc004f2c64ca76df9f27fa02',
//	'www.dresscod.org'
//);
//dump($client->getCollections(['parent_id' => 325231, 'is_hidden' => 'false'])->getData());
$options = ['IBLOCK_ID' => 2];
//$SectionImport = new InSaleImport\Sections($options);

//$Result = $SectionImport->importFirstLevel();

//$Result = $SectionImport->importByParent(1345568);
//dump($Result);

//$str = '-1?a%5B%5D=t';
//dd(urldecode($str));
$obSections = \Bitrix\Iblock\SectionTable::getList([
	'select' => ['ID', 'XML_ID'],
	'filter' => ['IBLOCK_ID' => 2, '=IBLOCK_SECTION_ID' => 3],
	'count_total' => true
]);

$bar = new \AB\Tools\Console\ProgressBar();
$bar->reset('# %fraction% [%bar%] %percent%', '=>', '-', 100, $obSections->getCount());

$ImportProduct = new InSaleImport\Products($options);
$Result = $ImportProduct->productsBySection(1412144);
if(!$Result->isSuccess()){
	\AB\Tools\Console\ProgressBar::pre($Result->getErrorMessages());
}
exit;

$i = 1;
while ($section = $obSections->fetch()){
	$bar->update($i);
	$Result = $ImportProduct->productsBySection($section['XML_ID']);
	if(!$Result->isSuccess()){
		\AB\Tools\Console\ProgressBar::pre($Result->getErrorMessages());
	}
	$i++;
}

echo "\r\n\r\n";