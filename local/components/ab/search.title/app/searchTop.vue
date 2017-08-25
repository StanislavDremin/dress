<template>
	<div :class="{'search': show, '_search': !show}">
		<a class="icon_search_link" href="javascript:" @click="show = !show">
			<img class="top-nav__search__icon_search" src="/local/dist/img/icons/icon_serch.png">
		</a>
		<form autocomplete="off" action="" v-show="show" style="display: none" @submit.prevent="">
			<suggestion :items="results" :loader="showLoader" :start-search="loadSearch" :searching="searching" :search-path="SearchPath"/>
			<button type="button" @click="searchSubmit"><img src="/local/dist/img/icons/icon_search_enter.png"></button>
		</form>
	</div>
</template>
<script>
	import debounce from 'lodash/debounce';
	import suggestion from './suggestion.vue';
	import Util from 'Utils';
	
	const isTestMode = false;
	
	Vue.use(Util);
	
	export default {
		props: ['SearchPath'],
		data(){
			return {
				show: false,
				restOptions: {
					baseURL: '/rest/s/search'
				},
				query: '',
				results: {
					products: [],
					sections: []
				},
				loadSearch: false
			}
		},
		watch:{
			query(){
				if(this.query.length > 2){
					this.startSearch();
					this.startLoad();
					this.rest().get('/searching',{params: {q: this.query}}).then(res => {
						if(res.data.STATUS === 1){
							let sections = [];
							this.$foreach(res.data.DATA.sections, (el, code) => {
								sections.push(el);
							});
							
							this.results = {
								products: res.data.DATA.products,
								sections: sections
							};
						}
						this.stopLoad();
					});
				} else {
					this.stopSearch();
				}
			}
		},
		methods: {
			searching: debounce(function (e) {
				this.query = e.target.value;
			}, 300),
			
			startSearch(){
				this.loadSearch = true;
			},
			stopSearch(){
				this.loadSearch = false;
			},
			searchSubmit(){
				let url = this.$addUrlParam(this.SearchPath, {q: encodeURIComponent(this.query)});
				window.location.assign(url);
			}
		},
		created(){
			if(isTestMode){
				setTimeout(() => {
					this.query = 'пухов';
				}, 1000);
			}
		},
		components: {
			suggestion
		}
	};
</script>
<style>

</style>