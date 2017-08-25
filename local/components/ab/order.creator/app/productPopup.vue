<template>
	<product-modal :show="showWindow" @onModalClose="showWindow = false" class-name="data_product" :click-overlay-close="true">
		<!--begin container container-inner-->
		<div class="popup_product_wrap" slot="m_body">
			<div v-if="arResult" class="container container-inner l-r-wr" style="flex-direction: column">
				<!--begin l-element-->
				<div class="l-element">
					<!--begin el-el-tags-mob-->
					<div class="el-tags el-tags-mob" v-if="arResult.COUNT_TAGS > 0" style="display: block;">
						<span class="el-tag el-tag-pink" v-for="tag in arResult.TAG_ITEMS" @click="goOnTag(tag)">{{tag}}</span>&nbsp;&nbsp;
					</div>
					<!--¯\_(ツ)_/¯ end el-el-tags-mob-->
					<!--begin title-mobile-->
					<div class="el-titles el-titles-mob" style="display: block;">
					<span class="el-titles__lable el-titles-mob__lable" v-if="arResult.NEWPRODUCT > 0"
							style="background-image: url(/local/dist/img/icons/icons-lable-new.png)"></span>
						<span class="el-titles__title el-titles-mob__title">{{arResult.NAME}}</span>
					</div>
					<!--¯\_(ツ)_/¯ end title-mobile-->
					<!--begin -->
					<div class="el-art-brand el-art-brand-mob">
						<div class="el-art-brand__articul el-art-brand-mob__articul">Артикул: {{arResult.ARTNUMBER}}
						</div>
						<div class="el-art-brand__brands el-art-brand-mob__brands" v-if="arResult.BRAND">
							Все товары бренда:&nbsp;&nbsp;
							<a :href="'/brands/' + arResult.BRAND.CODE">{{arResult.BRAND.ITEM.UF_NAME}}</a>
						</div>
					</div>
					<!--¯\_(ツ)_/¯ end -->
					<!--begin slider-element-->
					<div class="slider-element">
						<div :id="'popupSliderBig_' + arResult.ID" class="popupSliderBig owl-carousel owl-theme"></div>
						<div :id="'popupSliderSmall_' + arResult.ID" class="popupSliderSmall owl-carousel owl-theme"></div>
					</div>
					<!--¯\_(ツ)_/¯ end slider-element-->
				</div>
				<!--¯\_(ツ)_/¯ end l-element-->
				<!--begin r-element-->
				<div class="r-element">
					<!--begin sidebar-element-->
					<div class="sidebar-element">
						<div class="el-prises">
							<div class="el-prises__prises">
								цена:&nbsp;<span class="prise">{{arResult.PRICE_FORMAT}}</span>
								<!--<span class="sale-prise">27 999 Р</span> <span class="sale">-45%</span> -->
							</div>
							<div class="shop">
								<a href="javascript:">
									{{arResult.QUANTITY > 0 ? 'Товар в наличии' : 'Нет в наличии' }}
								</a>
							</div>
						</div>
						<!--begin выбор размера-->
						<div>
							<div class="sidebar-element__el-sizes-desc">
								<div class="sizes-hero">
									<span>Российский размер</span>
									<a href="javascript:">Как узнать свой размер?</a>
								</div>
								<div class="sizes-list">
									<span class="size" v-for="size in arResult.SIZE.VALUE">{{size}}</span>
								</div>
							</div>
						</div>
						<!--¯\_(ツ)_/¯ end выбор размера-->
						<div class="sidebar-element__el-colors" v-if="arResult.COLOR.ITEMS">
							<span class="title">Цвет</span>
							<span class="color" data-color="<?=$item['UF_XML_ID']?>"
									v-for="color in arResult.COLOR.ITEMS" v-if="color.IMG"
									:style="bgImage(color.IMG.SRC)">
						</span>
						</div>
						<!--begin Описание-->
						<div class="sidebar-element__el-descs" v-html="arResult.PREVIEW_TEXT"></div>
						<!--¯\_(ツ)_/¯ end Описание-->
						<!--begin Характеристики-->
						<div class="sidebar-element__el-attributes" v-html="arResult.DETAIL_TEXT"></div>
						<!--¯\_(ツ)_/¯ end Характеристики-->
					</div>
					<!--¯\_(ツ)_/¯ end sidebar-element-->
				</div>
				<!--¯\_(ツ)_/¯ end r-element-->
			</div>
		</div>
		<!--¯\_(ツ)_/¯ end container container-inner-->
	</product-modal>
</template>
<script>
	import ProductModal from 'DetailModal.vue';

	const initSlider = (big = {}, small = {}) => {

		const sync1 = big.container;
		const sync2 = small.container;
		
		sync1.owlCarousel({
			items: 1,
			slideSpeed: 2000,
			nav: true,
			autoplay: false,
			dots: false,
			loop: false,
			responsiveRefreshRate: 200,
			navText: ['<svg \n' +
			' xmlns="http://www.w3.org/2000/svg"\n' +
			' xmlns:xlink="http://www.w3.org/1999/xlink"\n' +
			' width="35px" height="25px">\n' +
			'<path fill-rule="evenodd"  fill="rgb(0, 0, 0)"\n' +
			' d="M11.581,0.855 C12.046,0.373 12.821,0.373 13.303,0.855 C13.768,1.320 13.768,2.094 13.303,2.558 L4.576,11.285 L33.372,11.285 C34.043,11.286 34.577,11.820 34.577,12.491 C34.577,13.162 34.043,13.713 33.372,13.713 L4.576,13.713 L13.303,22.423 C13.768,22.905 13.768,23.680 13.303,24.145 C12.821,24.626 12.045,24.626 11.581,24.145 L0.789,13.352 C0.308,12.887 0.308,12.113 0.789,11.648 L11.581,0.855 Z"/>\n' +
			'</svg>',
				'<svg \n' +
				' xmlns="http://www.w3.org/2000/svg"\n' +
				' xmlns:xlink="http://www.w3.org/1999/xlink"\n' +
				' width="35px" height="25px">\n' +
				'<path fill-rule="evenodd"  fill="rgb(0, 0, 0)"\n' +
				' d="M23.417,0.855 C22.952,0.373 22.177,0.373 21.696,0.855 C21.231,1.320 21.231,2.094 21.696,2.558 L30.423,11.285 L1.627,11.285 C0.955,11.286 0.421,11.820 0.421,12.491 C0.421,13.162 0.955,13.713 1.627,13.713 L30.423,13.713 L21.696,22.423 C21.231,22.905 21.231,23.680 21.696,24.145 C22.177,24.626 22.953,24.626 23.417,24.145 L34.210,13.352 C34.691,12.887 34.691,12.113 34.210,11.648 L23.417,0.855 Z"/>\n' +
				'</svg>'
			],
			lazyLoad: true,
			callbacks: true,
			onChanged: function (ev) {
				if (ev.item.index !== null && ev.item.index !== undefined) {
					sync2.find(".owl-item").removeClass('current');
					sync2.find(".owl-item").eq(ev.item.index).addClass('current');
				}
			}
		});


		sync2.owlCarousel({
			items: small.items,
			dots: false,
			nav: true,
			smartSpeed: 200,
			slideSpeed: 500,
			loop: false,
			// slideBy: 5, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
			responsiveRefreshRate: 100,
			mouseDrag: false,
			touchDrag: false,
			freeDrag: false,
			pullDrag: false,
			lazyLoad: false
		});

		sync2.on("click", ".owl-item", function (e) {
			e.preventDefault();
			let number = $(this).index();
			sync1.data('owl.carousel').to(number, 300, true);
		});


	};

	export default {
		props: ['LoadProduct'],
		data() {
			return {
				arResult: false,
				id: this.LoadProduct,
				url: '/rest/catalogElement/getSmallDetail',
				showWindow: false
			}
		},
		methods: {
			bgImage(src) {
				return 'background-image: url(' + src + ')';
			},
			goOnTag(tag){
			
//				window.location.assign('/catalog/?tag='+ tag +'&set_filter=Y')
			}
		},
		watch: {
			LoadProduct(id) {
				let url = BX.util.add_url_param(this.url, {id, temple: '.default'});

				this.$http.get(url).then(res => {
					if (res.data.STATUS === 1) {
						this.arResult = res.data.DATA;

						setTimeout(() => {
							let sync1 = $(".popupSliderBig");
							let sync2 = $("#popupSliderSmall_" + this.arResult.ID);
							sync1.trigger('destroy.owl.carousel');
							sync2.trigger('destroy.owl.carousel');

							let cntPhoto = this.$count(this.arResult.SLIDER_PHOTO);
							initSlider(
								{container: sync1, items: cntPhoto},
								{container: sync2, items: cntPhoto}
							);

							sync1.trigger('remove.owl.carousel');
							sync2.trigger('remove.owl.carousel');

							let itemsBig = this.arResult.SLIDER_PHOTO.map((el) => {
								return '<div class="item" style="' + this.bgImage(el.src) + '"></div>';
							});
							sync1.trigger('replace.owl.carousel', itemsBig.join(''));
							
							let itemsSmall = this.arResult.SLIDER_PHOTO.map((el) => {
								return '<div class="item" style="' + this.bgImage(el.SMALL.src) + '"></div>';
							});
							sync2.trigger('replace.owl.carousel', itemsSmall.join(''));
							
							this.showWindow = true;

						});

					}
					
					this.$emit('productIsLoading');
				})
			},
			showWindow(val){
				if(val === false){
					this.$emit('onProductClose');
				}
			}
		},
		components: {
			ProductModal
		},
	}
</script>
<style>
	.data_product .modal_data_wrap {
		position: absolute;
		top: 25%;
	}
	
	.popup_product_wrap .el-tag-pink {
		display: inline-block;
		margin-right: 10px;
	}
	
	.popup_product_wrap .el-tags-mob {
		padding-left: 60px;
		padding-bottom: 20px;
	}
	.popup_product_wrap .popupSliderSmall {
		margin: 15px auto;
	}
	.popup_product_wrap .sidebar-element__el-sizes-desc .sizes-list .size {
		padding: 2px 7px;
	}
	.el-tag {
		cursor: pointer;
	}
</style>