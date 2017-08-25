<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die(); ?>
<?
\Dresscode\Main\ModifierComponentsFilter::getInstance()->add('brand_guide_footer', [
	'!UF_FILE' => false,
]);
?>
<? $APPLICATION->IncludeComponent(
	"ab:hl.items",
	"brand_footer",
	array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BLOCK_TYPE" => "6",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => "brand_footer",
		"ITEMS_COUNT" => "15",
		"PAGE_NAVIGATION_ID" => "page",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "UF_NAME",
			1 => "UF_FILE",
			2 => "UF_XML_ID",
		),
		"SORT_BY1" => "UF_SORT",
		"SORT_BY2" => "",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "",
		"GUIDE_ID" => "brand_guide_footer",
	),
	false
); ?>


<!--begin bottom-line-->
<div class="bottom-line-wr">
	<div class="container">
		<div class="bottom-line">
			<div class="bottom-line__phone">
				<a href="tel:">
					<? $APPLICATION->IncludeComponent(
						"bitrix:main.include",
						".default",
						Array(
							"AREA_FILE_SHOW" => "sect",     // Показывать включаемую область
							"AREA_FILE_SUFFIX" => "phone_800",      // Суффикс имени файла включаемой области
							"EDIT_TEMPLATE" => "",         // Шаблон области по умолчанию
						)
					); ?>
				</a>
				<span>Бесплатный звонок</span>
			</div>
			<div class="bottom-line__email"><span>Подпишитесь <br> на наши новости</span>
				<form class="form"><input type="text" placeholder="Укажите ваш E-mail">
					<button>Подпиcаться</button>
				</form>
			</div>
			<div class="bottom-line__social"><span>Dresscod <br> в соцсетях</span>
				<a href="#" target="_blank">
					<div class="bottom-line__social__icons" style="background-image: url(/local/dist/img/icons/instagram.svg);"></div>
				</a>
				<a href="#" target="_blank">
					<div class="bottom-line__social__icons" style="background-image: url(/local/dist/img/icons/vk.svg);"></div>
				</a>
				<a href="#" target="_blank">
					<div class="bottom-line__social__icons" style="background-image: url(/local/dist/img/icons/fb.svg);"></div>
				</a>
				<a href="#" target="_blank">
					<div class="bottom-line__social__icons" style="background-image: url(/local/dist/img/icons/twit.svg);"></div>
				</a>
				<a href="#" target="_blank">
					<div class="bottom-line__social__icons" style="background-image: url(/local/dist/img/icons/odclass.svg);"></div>
				</a>
			</div>
		</div>
	</div>
</div>
<!--begin social mobile-->
<div class="container">
	<div class="social-mobile">
		<div class="social-mobile__social"><span>Dresscod <br> в соцсетях</span>
			<a href="#" target="_blank">
				<div class="social-mobile__social__icons" style="background-image: url(/local/dist/img/icons/instagram.svg);"></div>
			</a>
			<a href="#" target="_blank">
				<div class="social-mobile__social__icons" style="background-image: url(/local/dist/img/icons/vk.svg);"></div>
			</a>
			<a href="#" target="_blank">
				<div class="social-mobile__social__icons" style="background-image: url(/local/dist/img/icons/fb.svg);"></div>
			</a>
			<a href="#" target="_blank">
				<div class="social-mobile__social__icons" style="background-image: url(/local/dist/img/icons/twit.svg);"></div>
			</a>
			<a href="#" target="_blank">
				<div class="social-mobile__social__icons" style="background-image: url(/local/dist/img/icons/odclass.svg);"></div>
			</a>
		</div>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
<!--¯\_(ツ)_/¯-->
<!--begin footer-->
<div class="container">
	<div class="footer">
		<div class="footer__dresscod"><span>Dresscod</span>
			<? $APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
				"ROOT_MENU_TYPE" => "bottom",    // Тип меню для первого уровня
				"MAX_LEVEL" => "1",    // Уровень вложенности меню
				"CHILD_MENU_TYPE" => "bottom",    // Тип меню для остальных уровней
				"USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
				"DELAY" => "N",    // Откладывать выполнение шаблона меню
				"ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
				"MENU_CACHE_TYPE" => "N",    // Тип кеширования
				"MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
				"MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
				"MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
			),
				false
			); ?>
		</div>
		<div class="footer__faq"><span>Помощь</span>
			<? $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"bottom", 
	array(
		"ROOT_MENU_TYPE" => "help",
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "bottom",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"COMPONENT_TEMPLATE" => "bottom"
	),
	false
); ?>
		</div>
		<div class="footer__contact">
			<span>Наши контакты</span>
			<? $APPLICATION->IncludeComponent(
				"bitrix:main.include",
				".default",
				Array(
					"AREA_FILE_SHOW" => "sect",     // Показывать включаемую область
					"AREA_FILE_SUFFIX" => "contact_bottom",      // Суффикс имени файла включаемой области
					"EDIT_TEMPLATE" => "",         // Шаблон области по умолчанию
				)
			); ?>
		</div>
		<div class="footer__payment"><span>Способы оплаты</span>
			<p>Вы можете оплатить покупки наличными <br> при получении, либо выбрать другой <br> способ оплаты.</p>
			<div class="footer__payment__icon">
				<span class="footer__payment__icon__master-card">
					<img src="/local/dist/img/icons/icon_master_card.png" />
				</span>
				<span class="footer__payment__icon__visa">
					<img src="/local/dist/img/icons/icon_visa.png" />
				</span>
			</div>
		</div>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
<!--begin footer mobile-->
<div class="footer-mobile">
	<div class="footer-mobile__desc">
		<? $APPLICATION->IncludeComponent(
			"bitrix:main.include",
			".default",
			Array(
				"AREA_FILE_SHOW" => "sect",     // Показывать включаемую область
				"AREA_FILE_SUFFIX" => "contact_bottom",      // Суффикс имени файла включаемой области
				"EDIT_TEMPLATE" => "",         // Шаблон области по умолчанию
			)
		); ?>
	</div>
	<div class="footer-mobile__icon">
		<span class="footer-mobile__icon__master-card">
			<img src="/local/dist/img/icons/icon_master_card.png" />
		</span>
		<span class="footer-mobile__icon__visa">
			<img src="/local/dist/img/icons/icon_visa.png" />
		</span>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
<!--begin footer-desc-->
<div class="footer-desc-wr">
	<div class="container">
		<div class="footer-desc">
			<div class="footer-desc__left">© Dresscod.org <?=date('Y')?></div>
			<div class="footer-desc__right"><a class="footer-desc__right__name" href="#">Design – Mahaev Nikita</a>
				<a class="footer-desc__right__name" href="https://abraxabra.ru">Web – Abr@X@bra.ru</a></div>
		</div>
	</div>
</div>
<!--¯\_(ツ)_/¯-->
<!--begin modal-component-city-->
<template id="modal-template">
	<transition name="modal">
		<div>
			<div class="modal-mask-wr" @click="handlerClose"></div>
			<div class="modal-container modal-container_h">
				<search-city city-name="<?=$config->getMyCity()['TITLE']?>">
							<span slot="phones">
								<? $APPLICATION->IncludeComponent(
									"bitrix:main.include",
									".default",
									Array(
										"AREA_FILE_SHOW" => "sect",     // Показывать включаемую область
										"AREA_FILE_SUFFIX" => "phones",      // Суффикс имени файла включаемой области
										"EDIT_TEMPLATE" => "",         // Шаблон области по умолчанию
									),
									 false,
									['HIDE_ICONS' => 'Y']
								); ?>
							</span>
							<span slot="shops">
								<? $APPLICATION->IncludeComponent(
									"bitrix:main.include",
									".default",
									Array(
										"AREA_FILE_SHOW" => "sect",     // Показывать включаемую область
										"AREA_FILE_SUFFIX" => "shops",      // Суффикс имени файла включаемой области
										"EDIT_TEMPLATE" => "",         // Шаблон области по умолчанию
									),
									false,
									['HIDE_ICONS' => 'Y']
								); ?>
							</span>
				</search-city>
			</div>
		</div>
	</transition>
</template>
<!--¯\_(ツ)_/¯-->
</div>
<!-- END content -->


<?$APPLICATION->IncludeComponent('ab:auth.enter','', array(), false)?>

<!--begin popUp-pass-dell-->
<div class="popUp-pass-dell" style="display: none;" id="hidden-content-b2">
	<div class="popUp-pass-dell__title">Восстановления пароля</div>
	<div class="popUp-pass-dell__form" id="app-input2">
		<div class="title">Укажите e-mail к которому <br> привязан ваш аккаунт</div>
		<form>

			<span class="lable">E-mail</span>
			<el-input placeholder="" v-model="input3"></el-input>

			<div class="popUp-sign-up__form__btn">
				<a class="btn_pink" href="#">Восстановить</a>
			</div>
		</form>
	</div>
	<div class="popUp-pass-dell__line"></div>
	<div class="popUp-pass-dell__desc">
		На указаную почту будет высланно <br> 
		письмо с ссылкой, пройдя по которой <br>
		Вы сможете сменить пароль.
	</div>
</div>
<!--¯\_(ツ)_/¯-->

<?// Вставка включаемой области - http://dev.1c-bitrix.ru/user_help/settings/settings/components_2/include_areas/main_include.php
$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	".default",
	Array(
		"AREA_FILE_SHOW"      => "sect",     // Показывать включаемую область
		"AREA_FILE_SUFFIX"    => "counters",      // Суффикс имени файла включаемой области
		"EDIT_TEMPLATE"       => "",         // Шаблон области по умолчанию
	)
);?>

</body>
</html>