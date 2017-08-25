<?php require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');

$dadata = new \Dresscode\Main\Dadata();

$q = ['query' => 'ленина', 'city' => 'ульяновск'];

$result = $dadata->getStreetAction($q);

PR(\Bitrix\Main\Web\Json::decode($result));