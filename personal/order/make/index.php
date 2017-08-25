<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заказы");
?>
<?=$APPLICATION->IncludeComponent(
	"ab:detail.order.print",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"ORDER_ID" => $_REQUEST['ORDER_ID'],
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>