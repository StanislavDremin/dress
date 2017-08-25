<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?>

<?$APPLICATION->IncludeComponent('ab:order.creator', '', array(), false)?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>