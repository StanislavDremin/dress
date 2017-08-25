<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => 'Комплексный каталог',
	"COMPLEX" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "ab",
		"CHILD" => array(
			"ID" => "catalog",
			"SORT" => 10,
		)
	)
);
?>