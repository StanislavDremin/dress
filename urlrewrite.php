<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "/admin/ratioProperty",
		"RULE" => "",
		"ID" => "dresscode.main",
		"PATH" => "Dresscode\\Main\\CustomProperties\\RatioSizeColor",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "#^/personal/order/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.order",
		"PATH" => "/personal/order/index.php",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "/admin/components",
		"RULE" => "",
		"ID" => "digitalwand.admin_helper",
		"PATH" => "DigitalWand\\AdminHelper\\ComponentCreator",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "/services/city",
		"RULE" => "",
		"ID" => "dresscode.main",
		"PATH" => "\\Dresscode\\Main\\CityServices",
		"SORT" => "100600",
	),
	array(
		"CONDITION" => "#^/personal/#",
		"RULE" => "",
		"ID" => "bitrix:sale.personal.section",
		"PATH" => "/personal/index.php",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "#^/rest/(.*)#",
		"RULE" => "data=\$1",
		"ID" => "restModule",
		"PATH" => "/rest/index.php",
		"SORT" => "100500",
	),
	array(
		"CONDITION" => "/forms/iblock",
		"RULE" => "",
		"ID" => "ab:form.iblock",
		"PATH" => "\\AB\\Tools\\Forms\\FormIblock",
		"SORT" => "100510",
	),
	array(
		"CONDITION" => "/reviews/ajax",
		"RULE" => "",
		"ID" => "online1c:reviews.list",
		"PATH" => "\\Online1c\\Reviews\\ReviewsList",
		"SORT" => "100520",
	),
	array(
		"CONDITION" => "#^/catalog/#",
		"RULE" => "",
		"ID" => "ab:catalog.main",
		"PATH" => "/catalog/index.php",
	),
	array(
		"CONDITION" => "#^/store/#",
		"RULE" => "",
		"ID" => "bitrix:catalog.store",
		"PATH" => "/store/index.php",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
		"SORT" => "100",
	),
	array(
		"CONDITION" => "/s/search",
		"RULE" => "",
		"ID" => "ab:search.title",
		"PATH" => "\\Dresscode\\Search\\SearchTitle",
		"SORT" => "100550",
	),
	array(
		"CONDITION" => "/s/auth",
		"RULE" => "",
		"ID" => "ab:auth.enter",
		"PATH" => "\\Dresscode\\Main\\Auth",
		"SORT" => "100560",
	),
	array(
		"CONDITION" => "/s/catalogList",
		"RULE" => "",
		"ID" => "ab:product.list",
		"PATH" => "\\Dress\\Catalog\\ProductList",
		"SORT" => "100570",
	),
	array(
		"CONDITION" => "/catalogElement",
		"RULE" => "",
		"ID" => "ab:catalog.detail",
		"PATH" => "\\Dress\\Catalog\\DetailComponent",
		"SORT" => "100580",
	),
	array(
		"CONDITION" => "/elementComments",
		"RULE" => "",
		"ID" => "ab:comments",
		"PATH" => "\\Dresscode\\Comments",
		"SORT" => "100590",
	),
	array(
		"CONDITION" => "/orderCreate",
		"RULE" => "",
		"ID" => "ab:order.creator",
		"PATH" => "\\Dresscode\\OrderComponent",
		"SORT" => "100600",
	),
);

?>