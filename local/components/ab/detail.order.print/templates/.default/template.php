<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true); ?>

<div class="print_body">
	<h2>Подверждение</h2>

	<div class="info_text">
		<p><b><?=$arResult['PROFILE']['fio']?></b></p>
		<p>Благодорим за заказ.</p>
		<p>Вы получите письмо с подтверждением заказа на следующий адрес: <?=$arResult['PROFILE']['email']?></p>
		<p>&nbsp;</p>
		<p>Вы можете распечатать подтверждение заказа для вашей документации</p>
		<p>&nbsp;</p>
		<p>Желаем вам приятных покупок!</p>
		<p>&nbsp;</p>
		<p>С уважением,<br />Ваш интернет-магазин Dresscode.org</p>
	</div>
	<div style="max-width: 400px; padding-bottom: 30px">
		<?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax", "", array(
			"PAY_FROM_ACCOUNT" => "Y",
			"COUNT_DELIVERY_TAX" => "N",
			"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
			"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
			"ALLOW_AUTO_REGISTER" => "Y",
			"SEND_NEW_USER_NOTIFY" => "Y",
			"DELIVERY_NO_AJAX" => "N",
			"TEMPLATE_LOCATION" => "popup",
			"PROP_1" => array(),
			"PATH_TO_BASKET" => "/personal/cart/",
			"PATH_TO_PERSONAL" => "/personal/order/",
			"PATH_TO_PAYMENT" => "/personal/order/payment/",
			"PATH_TO_ORDER" => "/personal/order/make/",
			"SET_TITLE" => "Y",
			"SHOW_ACCOUNT_NUMBER" => "Y",
			"DELIVERY_NO_SESSION" => "Y",
			"COMPATIBLE_MODE" => "N",
			"BASKET_POSITION" => "before",
			"BASKET_IMAGES_SCALING" => "adaptive",
			"SERVICES_IMAGES_SCALING" => "adaptive",
		),
			false
		); ?>
	</div>

	<div class="order_info">
		<div class="title_border">
			Заказ № <?=$arResult['ORDER_FIELDS']['ACCOUNT_NUMBER']?>
		</div>
	</div>

	<div class="order_property">
		<div class="property_row">
			<div class="property_col"><b>Статус</b></div>
			<div class="property_col"><?=$arResult['ORDER_FIELDS']['STATUS_NAME']['NAME']?></div>
			<div class="property_col right"></div>
		</div>
		<div class="property_row">
			<div class="property_col"><b>Сумма</b></div>
			<div class="property_col">
				<ul>
					<li>Доставка</li>
					<li><b>Итого</b></li>
				</ul>
			</div>
			<div class="property_col right">
				<ul>
					<li><?=\SaleFormatCurrency($arResult['ORDER_FIELDS']['PRICE_DELIVERY'], 'RUB')?></li>
					<li><b><?=\SaleFormatCurrency($arResult['ORDER_FIELDS']['PRICE'], 'RUB')?></b></li>
				</ul>
			</div>
		</div>
		<div class="property_row">
			<div class="property_col"><b>Оплата</b></div>
			<div class="property_col">
				<ul>
					<li>Способ оплаты</li>
					<li>Статус оплаты</li>
				</ul>
			</div>
			<div class="property_col right">
				<ul>
					<li><?=$arResult['PAYMENTS'][0]['PAY_SYSTEM_NAME']?></li>
					<li style="color: #D75656">не оплачен</li>
				</ul>
			</div>
		</div>
		<div class="property_row">
			<div class="property_col"><b>Доставка</b></div>
			<div class="property_col">
				<ul>
					<li>Получатель</li>
					<li>Телефон получателя</li>
					<li>E-mail</li>
				</ul>
			</div>
			<div class="property_col right">
				<ul>
					<li><?=$arResult['PROFILE']['fio']?></li>
					<li><?=$arResult['PROFILE']['phone']?></li>
					<li><?=$arResult['PROFILE']['email']?></li>
				</ul>
			</div>
		</div>
		<div class="property_row">
			<div class="property_col"></div>
			<div class="property_col">Способ доставки</div>
			<div class="property_col right">
				<?=$arResult['DELIVERY']['NAME']?><br />
				<?=$arResult['DELIVERY']['DESCRIPTION']?>
			</div>
		</div>
		<div class="property_row">
			<div class="property_col"></div>
			<div class="property_col">
				<ul>
					<li>Регион</li>
					<li>Город/Район</li>
					<li>Адрес</li>
				</ul>
			</div>
			<div class="property_col right">
				<ul>
					<li><?=$arResult['PROFILE']['region']?></li>
					<li><?=$arResult['PROFILE']['city']?></li>
					<li><?=$arResult['PROFILE']['ADDRESS']?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="basket_wrap title_border">
		<? foreach ($arResult['BASKET'] as $arBasket): ?>
			<div class="basket_item">
				<div class="img_basket">
					<img src="<?=$arBasket['PRODUCT']['IMG']['src']?>" />
				</div>
				<div class="name_basket">
					<b><?=$arBasket['NAME']?></b>
					<div class="props_basket">
						<? if ($arBasket['PROPS']['COLOR']): ?>
							<p>Цвета: <?=$arBasket['PROPS']['COLOR']['VALUE']?></p>
						<? endif; ?>
						<? if ($arBasket['PROPS']['SIZE']): ?>
							<p>Размеры: <?=$arBasket['PROPS']['SIZE']['VALUE']?></p>
						<? endif; ?>
					</div>
				</div>
				<div class="count_basket">
					<span style="font-size: 110%"><?=(int)$arBasket['QUANTITY']?></span>&nbsp;
					<span class="grey_small">X <?=SaleFormatCurrency($arBasket['PRICE'], 'RUB')?></span>
				</div>
				<div class="sum_basket">
					<?=SaleFormatCurrency($arBasket['PRICE'] * $arBasket['QUANTITY'], 'RUB')?>
				</div>
			</div>
		<? endforeach; ?>
	</div>
	<div class="go_back">
		<a href="/">Вернуться в магазин</a>
	</div>
</div>