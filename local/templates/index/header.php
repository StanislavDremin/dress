<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
use Bitrix\Main;
use Dresscode\Main\Config;

$asset = Main\Page\Asset::getInstance();
$request = Main\Context::getCurrent()->getRequest();
$config = Config::instance();

CJSCore::Init(['isjs', 'sweetalert', 'jquery3', 'fancybox3', 'vue', 'animatecss', 'font_fa', 'vue_resource']);
global $APPLICATION, $USER;

if($config->getUser()->IsAuthorized() && $request->offsetExists('logout')){
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
	$asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css');
	$asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css');
	$asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/hover.css/2.1.1/css/hover-min.css');
	$asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/element-ui/1.4.2/theme-default/index.css');
	$asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.carousel.min.css');
	$asset->addCss('https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.0.0-beta.3/assets/owl.theme.default.min.css');
	$asset->addCss('/local/dist/vendor/slider/settings.css');

	$asset->addCss('/local/dist/css/app.css');
	$asset->addCss('/local/dist/css/custom.css');

	$asset->addJs('/local/dist/vendor/slick/slick.min.js');
	$asset->addJs('/local/dist/vendor/jquery.lazyload.min.js');
	$asset->addJs('/local/dist/vendor/owl-carousel/owl.carousel.min.js');

	$asset->addJs('/local/dist/js/app.js');
	$asset->addJs('/local/dist/js/main.js');
	?>
</head>
<body>
<? $APPLICATION->ShowPanel() ?>
<!-- BEGIN content -->
<div id="mobile-nav-body" class="mobile-nav-body">
	<div class="mobile-nav-body__top-line"> <span><?=$config->getUserName()?></span> <a href="/?loguot">Выход</a> </div>
	<div id="navBody" class="navBody">
		<? $APPLICATION->IncludeComponent("bitrix:menu", "mobile", Array(
			"ROOT_MENU_TYPE" => "left",    // Тип меню для первого уровня
			"MAX_LEVEL" => "2",    // Уровень вложенности меню
			"CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
			"USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
			"DELAY" => "N",    // Откладывать выполнение шаблона меню
			"ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
			"MENU_CACHE_TYPE" => "N",    // Тип кеширования
			"MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
			"MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
			"MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
			"COMPONENT_TEMPLATE" => ".default",
		),
			false
		); ?>

		<div class="msearch">
			<search-city city-name="<?=$config->getMyCity()['TITLE']?>"></search-city>
		</div>
	</div>
</div>
<div id="mobile-nav-wr">
	<!--begin top_line-->
	<div class="top-line-wr">
		<div class="container">
			<div class="top-line">
				<div class="top-line__city" id="modalApp" v-cloak>
					<a id="show-modal" @click="showModal = true" href="#"><span>{{topTitle}}</span>
						<i class="fa fa-angle-down" aria-hidden="true"></i>
					</a>
					<modal v-if="showModal" @close="showModal = false"></modal>
				</div>
				<div class="top-line__language"> <a href="#"><span>RU</span><i class="fa fa-angle-down" aria-hidden="true"></i></a> </div>
			</div>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
	<!--begin contact_logo-->
	<div class="container">
		<div class="contact-logo">
			<!--begin hamburger-icon-->
			<div id="mobileNav" v-cloak> <button @click="ShowMobile('.square')" :class="{ 'hamburger hamburger--collapse': true, 'is-active': show }" type="button">
              <span class="hamburger-box">
                <span class="hamburger-inner"></span>
              </span>
				</button> </div>
			<!--¯\_(ツ)_/¯-->
			<div class="contact-logo__shops">
				<span style="position: relative;">
					<span style="color: #000; padding-bottom: 5px; display: block"><strong>Магазины</strong></span>
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						".default",
						Array(
							"AREA_FILE_SHOW"      => "sect",     // Показывать включаемую область
							"AREA_FILE_SUFFIX"    => "shops",      // Суффикс имени файла включаемой области
							"EDIT_TEMPLATE"       => "",         // Шаблон области по умолчанию
						)
					);?>
				</span> <a href="#">
					<i style="font-size: 12px;" class="fa fa-angle-down" aria-hidden="true"></i>
				</a> </div>
			<div class="contact-logo__logo">
				<a class="logo-desktop" href="/">
					<img src="/local/dist/img/logo_top.png" alt="log"></a>
				<a class="logo-mobile" href="/">
					<img src="/local/dist/img/logo-mobile.png" alt="log">
				</a>
				<a class="logo-mobile-xs" href="/"><img src="/local/dist/img/logo-mobile-xs.png" alt="log"></a>
			</div>
			<div class="top-nav-lk-mobile"> <a class="hvr-underline-from-left" href="#">Вход</a>
			</div>
			<div class="contact-logo__phone">
				<a href="tel:">
					<?$APPLICATION->IncludeComponent(
						"bitrix:main.include",
						".default",
						Array(
							"AREA_FILE_SHOW"      => "sect",     // Показывать включаемую область
							"AREA_FILE_SUFFIX"    => "phone_800",      // Суффикс имени файла включаемой области
							"EDIT_TEMPLATE"       => "",         // Шаблон области по умолчанию
						)
					);?>
				</a>
				<span>Бесплатный звонок</span>
			</div>
		</div>
	</div>
	<div class="contact-logo-line"></div>
	<!--¯\_(ツ)_/¯-->
	<!--begin nav_search_cart-->
	<div class="top-nav-wr">
		<div class="container">
			<!--begin mobile-search-->
			<div id="search-mobile" v-cloak> <a class="icon-search-mobile" v-if="searchDesc" href="#" @click="searchDesc = !searchDesc">
					<img class="top-nav__search__icon_search" src="/local/dist/img/icons/icon_serch.png">
				</a>
				<div v-else="searchDesc" class=""> <a class="icon-search-mobile" href="#" @click="searchDesc = !searchDesc">
						<img class="top-nav__search__icon_search" src="/local/dist/img/icons/icon_serch.png">
					</a>
					<div class="search-desc">
						<form action="/search/" method="get" autocomplete="off" novalidate>
							<input name="q" type="text" placeholder="Найти товар">
							<button type="submit">Найти</button>
						</form>
					</div>
				</div>
			</div>
			<!--¯\_(ツ)_/¯-->
			<div class="top-nav">

				<? $APPLICATION->IncludeComponent("bitrix:menu", "main_top", Array(
					"ROOT_MENU_TYPE" => "top",    // Тип меню для первого уровня
					"MAX_LEVEL" => "1",    // Уровень вложенности меню
					"CHILD_MENU_TYPE" => "top",    // Тип меню для остальных уровней
					"USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
					"DELAY" => "N",    // Откладывать выполнение шаблона меню
					"ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
					"MENU_CACHE_TYPE" => "N",    // Тип кеширования
					"MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
					"MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
					"MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
					"COMPONENT_TEMPLATE" => ".default",
				),
					false
				); ?>

				<div class="search-lk-wr">
					<?$APPLICATION->IncludeComponent('ab:search.title','', array(), false)?>
					<?if($config->getUser()->IsAuthorized()):?>
						<div class="top-nav__lk"> <a class="hvr-underline-from-left" href="/?logout">Выход</a> </div>
					<?else:?>
						<div class="top-nav__lk">
							<a class="hvr-underline-from-left" href="javascript:"
									data-fancybox data-src="#enter_form" >Вход</a>
						</div>
					<?endif;?>
				</div>
				<div class="top-nav-cart-wr">
					<?$APPLICATION->IncludeComponent('ab:basket.top', '', [], false)?>
				</div>
			</div>
		</div>
	</div>
	<!--¯\_(ツ)_/¯-->
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.include",
		".default",
		Array(
			"AREA_FILE_SHOW"      => "page",     // Показывать включаемую область
			"AREA_FILE_SUFFIX"    => "slider_top",      // Суффикс имени файла включаемой области
			"EDIT_TEMPLATE"       => "",         // Шаблон области по умолчанию
		)
	);?>

