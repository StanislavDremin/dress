/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import BasketItems from './BasketItems.vue';
import SidebarBasket from './Sidebar-basket.vue';
import Util from 'Utils';
import Actions from './ActionApi';

Vue.use(Util);

Vue.http.interceptors.push(function(request, next) {
	next(function(response) {

		if( typeof response.body !== 'object'){
			swal('Ошибка', 'Системная ошибка', 'error')
		} else {
			if(response.body.hasOwnProperty('ERRORS') && response.body.ERRORS !== null){
				if(response.body.ERRORS instanceof Array){
					swal('Ошибка', response.body.ERRORS.join(', '), 'error');
				} else {
					swal('Ошибка', 'Системная ошибка', 'error');
				}
			}
		}
	});
});

$(function () {
	if($('#order_app'.length > 0)){
		new Vue({
			el: '#order_app',
			components: {
				'sidebars-baskets': SidebarBasket,
				BasketItems
			},
			data: {
				loading: false,
				Rest: null,
				BasketItems: [],
				totalSum: 0,
				totalSumFormat: '',
				totalCount: 0,
				basketIsEmpty: false,
			},
			methods: {
				preloader(load){
					this.loading = load;
				},
				startLoading() {
					this.loading = true;
				},
				stopLoading() {
					this.loading = false;
				},
				getBasket() {
					this.startLoading();
					this.Rest.getBasketItems().then(res => {
						if (res.data.STATUS === 1) {
							this.BasketItems = res.data.DATA.items;
							this.totalSum = res.data.DATA.totalSum;
							this.totalSumFormat = res.data.DATA.totalSumFormat;
							this.totalCount = res.data.DATA.totalCount;
						}
						this.stopLoading();
					});
				},
				updateQuantity(data) {
					this.startLoading();
					this.Rest.updateQuantity(data).then(res => {
						if (res.data.STATUS === 1) {
							this.getBasket();
						}
					});
				},
				deleteBasketItem(id) {
					this.Rest.deleteBasketItem({id}).then(res => {
						if(res.data.STATUS === 1){
							this.getBasket();
						}
					});
				},
			},
			created(){
				this.Rest = this.$resource('', {}, Actions);
				this.getBasket();
			},
			watch: {
				BasketItems(val){
					this.basketIsEmpty = val.length === 0;
				}
			}
		});
	}
});

