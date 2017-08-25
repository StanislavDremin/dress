<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
$this->setFrameMode(true);
$this->addExternalCss($templateFolder.'/script.css');
?>
<div id="order_app" >
	<!--begin container-->
	<div class="container container-inner l-r-bas" v-loading="loading">
		<!--begin basket-l-->
		<div class="basket-l">
			<div class="basket-wr">
				<div class="basket-title">Корзина</div>
				<basket-items
						@update-quantity="updateQuantity"
						@delete-item="deleteBasketItem"
						:basket-items="BasketItems"
						:total-sum="totalSum" :total-sum-format="totalSumFormat" :total-count="totalCount"
						:basket-is-empty="basketIsEmpty">
					<div slot="certificate">
						<div class="desc">
							<p><b>Бесплатный,</b> подарочный сертификат на химчистку <br>
								при покупке от 30.000 рублей. <a href="#">Подробнее</a></p>
						</div>
					</div>
					<div class="basket-desc" slot="basket_desc">
						<div class="basket-desc__item">
							<span class="img1 img" style="background-image: url(/local/dist/img/icons/icon-basket-1.png)"></span>
							<span>Безопасность</span>
						</div>
						<div class="basket-desc__item">
							<span class="img2 img" style="background-image: url(/local/dist/img/icons/icon-basket-2.png)"></span>
							<span>Бесплатная доставка <br> от 5000 рублей</span>
						</div>
						<div class="basket-desc__item">
							<span class="img3 img" style="background-image: url(/local/dist/img/icons/icon-basket-3.png)"></span>
							<span>Примерка</span>
						</div>
						<div class="basket-desc__item">
							<span class="img4 img" style="background-image: url(/local/dist/img/icons/icon-basket-4.png)"></span>
							<span>Курьерская доставка <br> на следующий день</span>
						</div>
					</div>
				</basket-items>
			</div>
		</div>
		<!--¯\_(ツ)_/¯ end basket-l-->
		<!--begin basket-r-->
		<div class="basket-r">
			<sidebars-baskets :basket-items="BasketItems"
					:total-sum="totalSum" :total-sum-format="totalSumFormat"
					:total-count="totalCount">
			</sidebars-baskets>
			<!--begin basket-desc-->
			<div class="basket-desc-mobile">
				<div class="basket-desc-mobile__item"> <span class="img1 img" style="background-image: url(/local/dist/img/icons/icon-basket-1.png)"></span> <span>Безопасность</span> </div>
				<div class="basket-desc-mobile__item"> <span class="img2 img" style="background-image: url(/local/dist/img/icons/icon-basket-2.png)"></span> <span>Бесплатная доставка <br> от 5000 рублей</span> </div>
				<div class="basket-desc-mobile__item"> <span class="img3 img" style="background-image: url(/local/dist/img/icons/icon-basket-3.png)"></span> <span>Примерка</span> </div>
				<div class="basket-desc-mobile__item"> <span class="img4 img" style="background-image: url(/local/dist/img/icons/icon-basket-4.png)"></span> <span>Курьерская доставка <br> на следующий день</span> </div>
			</div>
			<!--¯\_(ツ)_/¯ end basket-desc-->
		</div>
		<!--¯\_(ツ)_/¯ end basket-r-->
	</div>
	<!--¯\_(ツ)_/¯ container-->
</div>
