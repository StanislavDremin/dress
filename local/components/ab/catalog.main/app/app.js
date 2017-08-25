/**
 * Created by dremin_s on 04.08.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import products from './productItems.vue';
import createBrowserHistory from 'history/createBrowserHistory';
import queryString from 'query-string';
import Ajax from 'preloader/RestService';
import EventBus from 'EventBus';

const Rest = new Ajax({
	baseURL: '/rest/s/catalogList/'
});

let url = window.location.pathname;
let lastChar = url.slice(-1);
if(lastChar === '/'){
	url = url.slice(0, -1);
}

// const history = createBrowserHistory({
// 	basename: url,
// 	forceRefresh: false,
// });
// const location = history.location;

const SidebarSections = {
	template: '#sidebar-nav'
};

const MainCatalog = new Vue({
	data: {
		sorting: [{
			value: 'rating',
			label: 'по рейтингу'
		}, {
			value: 'price_desc',
			label: 'по убыванию цены'
		}, {
			value: 'price_asc',
			label: 'по возростанию цены'
		}, {
			value: 'popular',
			label: 'по популярности'
		}, {
			value: 'date_update',
			label: 'по обновлению'
		}],
		sortingValue: '',
		productItems: {
			items: [],
			nav: {}
		},
		productStore: {},
		currentPage: 1,
		history: createBrowserHistory({
			basename: url,
			forceRefresh: false,
		}),
		locationQuery: window.location.search,
		filterSelected: {},
		isNotAvailable: false
	},
	beforeUpdate(){
		if(this.productItems.items !== undefined && this.productItems.items.length === 0){
			this.isNotAvailable = true;
		} else {
			this.isNotAvailable = false;
		}
	},
	watch: {
		sortingValue(value){
			this.locationQuery = BX.util.add_url_param(this.locationQuery, {sort: value});
		},
		locationQuery(data){
			this.history.push(data);
		},
		filterSelected(data){
			let itemFilter = {};
			this.$foreach(data, (el, code) => {
				if(Object.keys(el).length > 0)
					itemFilter[code] = Object.keys(el);
			});
			let url = '';
			if(this.$count(itemFilter) > 0)
				itemFilter['set_filter'] = 'Y';

			this.locationQuery = BX.util.add_url_param(url, itemFilter);
		}
	},
	components: {
		SidebarSections,
		products
	},
	methods: {
		refresh(params = {}){
			let url = this.$addUrlParam('/refreshData'+ window.location.search, Object.assign({}, params, {temple: '.default'}));
			this.startLoad();
			Rest.get(url).then(res => {
				if(res.data.STATUS === 1){
					Vue.set(this.productStore, this.currentPage, res.data.DATA);
					let productData = {};
					if(params.more !== undefined){
						productData.items = BX.util.array_merge(this.productItems.items, res.data.DATA.items);
						productData.nav = res.data.DATA.nav;
						Vue.set(this, 'productItems', productData);
					} else {
						this.productItems = res.data.DATA;
					}
					this.currentPage = res.data.DATA.nav.page;
				}
				this.stopLoad();
			});
		},
		morePage(){
			this.currentPage++;
			this.locationQuery = BX.util.add_url_param(this.locationQuery, {part: 'page-'+ this.currentPage, more:'Y'});
		},
		makeFilter(data){
			let itemFilters = {};
			this.$foreach(data.selected, (el, code) => {
				if(itemFilters[data.code] === undefined){
					itemFilters[data.code] = {};
				}
				itemFilters[data.code][el.id] = el.value;
			});
			if(this.$count(data.selected) === 0){
				itemFilters[data.code] = {};
			}

			this.filterSelected = Object.assign({}, this.filterSelected, itemFilters);
		},
		updatePage(pageNum){
			this.currentPage = pageNum;
			let url = BX.util.remove_url_param(this.locationQuery, 'more');
			this.locationQuery = BX.util.add_url_param(url, {part: 'page-'+ pageNum});
			$('body').animate({
				scrollTop: $(this.$el).offset().top
			}, 300);
		},
		unListen(location){
			return this.history.listen((location) => {
				this.refresh(queryString.parse(location.search));
			});
		}
	},
	created(){
		let params = queryString.parse(window.location.search);
		if(window.location.pathname.split('/').length <= 4)
			this.refresh(params);
	},
	mounted(){
		EventBus.getBus().$on('setFilter', (data) => {
			this.makeFilter(data);
		});

		this.unListen();
	}
});

$(function () {
	if($('#app_catalog').length > 0)
		MainCatalog.$mount('#app_catalog');
});