<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

use Bitrix\Main;
use Dresscode\Main\Config;

$asset = Main\Page\Asset::getInstance();
$request = Main\Context::getCurrent()->getRequest();
$config = Config::instance();

CJSCore::Init(['isjs', 'sweetalert', 'jquery3', 'fancybox3', 'vue', 'animatecss', 'font_fa', 'vue_resource']);
global $APPLICATION, $USER;

if ($config->getUser()->IsAuthorized() && $request->offsetExists('logout')){
	$config->getUser()->Logout();
	LocalRedirect('/');
}
?>
<!doctype html>
<html>
<head>
	<? $APPLICATION->ShowHead(); ?>
	<title><? $APPLICATION->ShowTitle() ?></title>

	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#fff">
	<meta name="format-detection" content="telephone=no">

	<meta name="ss" content="<?=bitrix_sessid()?>">
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">

	<?
	$asset->addCss('/local/dist/css/app.css');
	?>
</head>
<body>
<? $APPLICATION->ShowPanel() ?>
<!-- BEGIN content -->
<div id="wrap">
	<div class="container_print">
		<div class="container">
			<div class="print_header">
				<a href="/" class="print_logo">
					<img src="/local/dist/img/logo_top.png" alt="log" />
				</a>
			</div>