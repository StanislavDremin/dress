<template>
	<div v-loading.fullscreen.lock="bigLoader || LoadProduct" element-loading-text="Загрузка...">
		<div class="catalog-list">
			<slot v-if="items.length === 0"></slot>
			<template v-if="!getAvailable()">
				<div class="catalog-list__items" v-for="product in products()" :key="product.ID">
					<span class="lable" v-if="product.NEWPRODUCT > 0"
							style="background: url(/local/dist/img/icons/icons-lable-new.png)">
					</span>
					<div class="catalog-list__items__img" :style="product.bgPicture"
							@click="goToDetailProduct(product.DETAIL_PAGE_URL)"></div>
					<div class="catalog-list__items__desc">
						<div class="catalog-list__items__desc__title"><span>{{product.NAME}}</span></div>
						<div class="catalog-list__items__desc__prise">
							<span>{{product.PRICE_FORMAT}} руб.</span>
							<!--<span class="sale"></span>-->
						</div>
						<div class="catalog-list__items__hidden">
							<div class="catalog-list__items__hidden__size" v-if="product.SIZE">
								<span>Размеры (RUS):</span>&nbsp;&nbsp;{{product.sizeFormat}}
							</div>
							<div class="catalog-list__items__hidden__btn">
								<a class="shop-item-btn" href="javascript:" @click.prevent="loadProductPopup(product.ID, product.DETAIL_PAGE_URL)">Подробнее</a>
							</div>
						</div>
					</div>
				</div>
			</template>
			<template v-else>
				<div class="is_not_available">
					<div class="inner_massage">Товары не найдены</div>
				</div>
			</template>
		</div>
		<div class="bx-pagination">
			<paging :page-count="nav.totalPages" prev-text="Назад" next-text="Вперед"
					prev-class="bx-pag-prev" next-class="bx-pag-next" page-class="paging_item"
					:force-page="getCurrentPage()" :click-handler="updater"
					container-class="bx-pagination-container row" :more-page="moreHandler">
			</paging>
		</div>
		<product-popup :load-product="LoadProduct" :show-basket-btn="true"
				@productIsLoading="isLoadingProduct" @onProductClose="productClose"></product-popup>
	</div>
</template>
<script>
	import paging from 'paginator';
	import productPopup from '../../order.creator/app/productPopup.vue';
	
	
	export default{
		props: {
			items: {type: Array, default: []},
			nav: {type: Object, default: {}},
			morePage: {type: Function},
			updater: {type: Function},
			isNotAvailable: {}
		},
		data() {
			return {
				LoadProduct: null,
				showPopup: false,
				bigLoader: false,
				scrollY: 0,
			}
		},
		methods:{
			products(){
				return this.items.map((el) => {
					let product = Object.assign({}, el);

					if (product.SMALL_PICTURE !== undefined) {
						product.bgPicture = 'background-image: url(' + product.SMALL_PICTURE.src + ')';
					}
					if (product.SIZE.hasOwnProperty('VALUE')) {
						product.sizeFormat = product.SIZE.VALUE.join(' ');
					}

					return product;
				});
			},
			moreHandler(){
				this.morePage();
			},
			getCurrentPage(){
				return this.nav.page - 1;
			},
			getAvailable(){
				return this.isNotAvailable
			},
			loadProductPopup(id, url) {
				if($(window).width() > 768){
					this.LoadProduct = id;
					this.showPopup = true;
				} else {
					window.location.assign(url);
				}
			},
			goToDetailProduct(url){
				this.bigLoader = true;
				window.location.assign(url);
			},
			isLoadingProduct(){
				
				this.LoadProduct = null;
				this.scrollY = window.pageYOffset;
				
				setTimeout(() => {
					$('html, body').scrollTop(0);
				}, 300);
			},
			productClose(){
				$('html, body').scrollTop(this.scrollY);
			}
		},
		components: {
			paging,
			productPopup
		}
	}
</script>
<style>
	.pager_catalog_nav {
		margin-top: 35px;
	}
	.bx-pagination-container ul .active,
	.bx-pagination-container ul li:hover {
		background-color: #ec7574;
		color: #fff;
		transition: all .5s;
		border-radius: 3px;
	}
	.bx-pagination-container ul li.active a {
		color: #fff;
	}
	.bx-pagination-container ul li {
		font-size: 12px;
		font-family: 'Roboto Light',sans-serif;
	}
	.bx-pagination-container ul li.paging_item a {
		font-size: 13px;
		padding: 0 8px;
		border: 1px solid #d7d7d7;
		border-radius: 3px;
		margin-right: 0;
		background: none;
	}
	.bx-pagination-container .bx-pag-next, .bx-pagination-container .bx-pag-prev,
	.bx-pagination-container .bx-pag-next:hover, .bx-pagination-container .bx-pag-prev:hover {
		border: none;
		background: none;
	}
	.bx-pagination-container .bx-pag-next, .bx-pagination-container .bx-pag-prev {
		display: flex;
		align-items: baseline;
		font-size: 12px;
		font-family: 'Roboto Light',sans-serif;
		justify-content: space-around;
	}
	.bx-pagination-container .bx-pag-next a, .bx-pagination-container .bx-pag-prev a {
		font-size: 12px;
		font-family: 'Roboto Light',sans-serif;
	}
	.bx-pagination-container .bx-pag-prev:before {
		padding-right: 12px;
	}
	.bx-pagination-container .bx-pag-next:after {
		padding-left: 12px;
	}
	.bx-pagination-container .bx-pag-prev a {
		margin-right: 10px;
	}
	.is_not_available {
		width: 100%;
	}
	.is_not_available .inner_massage {
		padding: 10px;
		text-align: center;
	}
	.catalog-list__items .catalog-list__items__img {
		cursor: pointer !important;
	}
</style>