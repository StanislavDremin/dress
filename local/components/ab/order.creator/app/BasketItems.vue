<template>
	<div class="basket-items">
		<!--begin column-name-->
		<div class="empty_basket" v-if="basketIsEmpty">
			<p>Ваша корзина пуста.</p>
			<p><a href="/">Начать покупки</a></p>
		</div>
		<div class="column-name" v-else>
			<span class="name1">Наименование</span>
			<span class="name2">Количество</span>
			<span class="nam3">Цена</span>
		</div>
		<!--¯\_(ツ)_/¯ end column-name-->
		<!--begin basket-item-->
		<div class="basket-item" v-for="(item, index) in BasketItems">
			<span class="icon-dell" @click="deleteBasketItem(item.ID)"><i class="fa fa-times" aria-hidden="true"></i></span>
			<div class="column-one">
				<!--<div class="column-name">Наименование</div>-->
				<div class="column-one__desc">
					<div class="img">
						<a class="basket_item_link_detail" href="javascript:" @click.prevent="loadProductPopup(item.PRODUCT_ID)">
							<img v-if="item.PRODUCT_DATA.IMAGE" :src="item.PRODUCT_DATA.IMAGE.src" />
						</a>
					</div>
					<div class="options">
						<span class="title">{{item.NAME}}</span>
						<span class="size" v-if="item.PROPS && item.PROPS.SIZE">Размер: {{item.PROPS.SIZE.VALUE}}</span>
						<span class="colors" v-if="item.PROPS && item.PROPS.COLOR">Цвет: {{item.PROPS.COLOR.VALUE}}</span>
						<div class="column-free-mobil">
							<div class="prise-total">
								<span class="prise">{{item.SUM_FORMAT}} руб.</span>
								<span class="total">{{item.PRICE_FORMAT}} руб. x {{item.QUANTITY}} шт.</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="column-two">
				<count-basket :product="index" @update-quantity="updateQuantity" :quantity="item.QUANTITY"></count-basket>
			</div>
			<div class="column-free">
				<div class="prise-total"><span class="prise">{{item.SUM_FORMAT}} руб.</span>
					<span class="total">{{item.PRICE_FORMAT}} руб. x {{item.QUANTITY}} шт.</span></div>
			</div>
		</div>
		<!--¯\_(ツ)_/¯ end basket-item-->
		
		<!--begin basket-certificate-totals--->
		<div class="basket-certificate-totals">
			<div class="basket-certificate-totals__certificate">
				<div class="img"><img src="/local/dist/img/certificate.png" alt=""></div>
				<slot name="certificate"></slot>
			</div>
			<div class="basket-certificate-totals__totals">
				<span class="total">Итого {{totalFormat}} на сумму {{totalSumFormat}} руб.</span>
				<!--<span class="total-sale">Итого со скидкой: 57 600 руб.</span>-->
			</div>
		</div>
		<!--¯\_(ツ)_/¯ end basket-certificate-totals-->
		<!--begin basket-desc-->
		<slot name="basket_desc"></slot>
		<!--¯\_(ツ)_/¯ end basket-desc-->
		
		<product-popup :load-product="LoadProduct"></product-popup>
	</div>
</template>
<script>
	import BasketCount from './Count-basket.vue';
	import Actions from './ActionApi';
	import productPopup from './productPopup.vue';

	export default {
		props: {
			BasketItems: {type: Array, default: []},
			totalSum: {type: Number|String, default: 0},
			totalSumFormat: {type: String, default: ''},
			totalCount: {type: Number|String, default: 0},
			basketIsEmpty: {type: Boolean}
		},
		data() {
			return {
				Rest: this.$resource('', {}, Actions),
				LoadProduct: null,
				showPopup: false
			};
		},
		components: {
			'count-basket': BasketCount,
			productPopup,
		},
		methods: {
			startLoading() {
				this.$emit('is-loading', true);
			},
			stopLoading() {
				this.$emit('is-loading', false);
			},
			updateQuantity(data) {
				this.$emit('update-quantity', {
					id: this.BasketItems[data.id]['ID'],
					quantity: data.quantity
				});
			},
			deleteBasketItem(id) {
				this.$emit('delete-item', id);
			},
			loadProductPopup(id) {
				this.LoadProduct = id;
				this.showPopup = true;
			},
		},
		created() {},
		computed: {
			totalFormat() {
				return this.totalCount + this.$declOfNum([' товар', ' товара', ' товаров'])(this.totalCount);
			}
		},
	}
</script>
<style>
	.basket_item_link_detail {
		text-align: center;
		display: flex;
		justify-content: center;
	}
	.empty_basket {
		margin: 30px auto;
		color: #ec7574;
	}
	.empty_basket a {
		color: #09f;
		text-decoration: underline !important;
	}
</style>