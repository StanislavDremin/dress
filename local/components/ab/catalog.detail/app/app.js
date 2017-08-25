/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
// import './lib/owl-carousel/owl.carousel.min';
// import 'slick-carousel';
// import Tag from './Tag-pink.vue'
// import Tags from './Tags.vue'
// import ElDesc from './el-hidden-desc.vue';
// import Attributes from './attributes.vue';
// import ElementSizes from './Element-size.vue';
import ElementTabs from './Element-tabs.vue';
import Ajax from 'preloader/RestService';
const Rest = new Ajax({
	baseURL: '/rest/catalogElement'
});

import EventBus from 'EventBus';

$(function () {
	new Vue({
		el: '#catalog-element',
		data: {
			sizeSelect: null,
			colorSelect: null
		},
		components: {
			// 'tag': Tag,
			// 'el-tags': Tags,
			// 'el-descs': ElDesc,
			// 'attributes': Attributes,
		},
		methods: {
			addToFavorite(productId){
				let post = {
					productId,
					size: this.sizeSelect,
					color: this.colorSelect
				};

				Rest.post('/addToFavorite', post).then(res => {
					if(res.data.STATUS === 1){
						swal('', 'Товар добавлен в избранное','success')
					}
				});
			},
			addToCart(productId){
				let post = {
					productId,
					size: this.sizeSelect,
					color: this.colorSelect
				};

				Rest.post('/addToCart', post).then(res => {
					if(res.data.STATUS === 1){
						swal('', 'Товар добавлен в корзину','success');
						if(res.data.DATA.quantity > 0){
							EventBus.getBus().$emit('onDressAddBasket', res.data.DATA);
						}
					}
				});
			},
			checkColor(ev){
				let _self = $(ev.currentTarget);
				$(this.$el).find('.sidebar-element__el-colors .color').removeClass('isActive');
				if(_self.hasClass('isActive'))
					_self.removeClass('isActive');
				else
					_self.addClass('isActive');

				this.colorSelect = _self.data('color');

				// this.addBasketProperty('colorSelect', _self.data('color'));
			},
			checkSize(ev){
				let _self = $(ev.currentTarget);
				$(this.$el).find('.sidebar-element__el-sizes-desc .size').removeClass('isActive');
				if(_self.hasClass('isActive'))
					_self.removeClass('isActive');
				else
					_self.addClass('isActive');

				this.sizeSelect = _self.data('size');

				// this.addBasketProperty('sizeSelect', _self.data('size'));
			},

			addBasketProperty(name, value){
				if(this.hasOwnProperty(name)){
					let k = BX.util.array_search(value, this[name]);
					if(k === -1){
						this[name].push(value);
					} else {
						this[name].splice(k, 1);
					}
				}
			}
		},
		mounted(){
			let color = $(this.$el).find('.sidebar-element__el-colors .color').eq(0);
			let size = $(this.$el).find('.sidebar-element__el-sizes-desc .size').eq(0);
			this.colorSelect = color.data('color');
			this.sizeSelect = size.data('size');
			color.addClass('isActive');
			size.addClass('isActive');
		}
	});

	var sync1 = $("#sync1");
	var sync2 = $("#sync2");

	var slidesPerPage = sync1.find('.owl-item').length; //globaly define number of elements per page
	var syncedSecondary = true;

	function syncPosition(el) {
		//if you set loop to false, you have to restore this next line
		// var current = el.item.index;

		//if you disable loop you have to comment this block
		var count = el.item.count - 1;
		var current = Math.round(el.item.index - (el.item.count / 2) - .5);

		if (current < 0) {
			current = count;
		}
		if (current > count) {
			current = 0;
		}

		//end block

		sync2
			.find(".owl-item")
			.removeClass("current")
			.eq(current)
			.addClass("current");
		var onscreen = sync2.find('.owl-item.active').length - 1;
		var start = sync2.find('.owl-item.active').first().index();
		var end = sync2.find('.owl-item.active').last().index();

		if (current > end) {
			sync2.data('owl.carousel').to(current, 100, true);
		}
		if (current < start) {
			sync2.data('owl.carousel').to(current - onscreen, 100, true);
		}
	}

	function syncPosition2(el) {
		if (syncedSecondary) {
			var number = el.item.index;
			sync1.data('owl.carousel').to(number, 100, true);
		}
	}

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
		// items: 5,
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
		lazyLoad: true
	});

	sync2.on("click", ".owl-item", function (e) {
		e.preventDefault();
		var number = $(this).index();
		sync1.data('owl.carousel').to(number, 300, true);
	});

	new Vue({
		el: '#element_tabs',
		components: {
			'elements-tabs': ElementTabs,
		}
	})
});