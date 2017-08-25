<template>
	<div class="search-city-wr">
		<div class="search-city-wr__city">
			<el-menu default-active="2" class="el-menu-vertical-demo">
				<el-submenu index="1">
					<template slot="title">{{topTitle}}</template>
					<el-menu-item-group> <input type="text" placeholder="Укажите свой город" v-model="search">
						<vue-perfect-scrollbar class="search-city-wr__city__scroll-area" :settings="settings">
							<li v-for="city in filteredCity">
								<a href="#" @click.prevent="onClick(city.TITLE, city.ID)">{{city.TITLE}}</a>
							</li>
						</vue-perfect-scrollbar>
					</el-menu-item-group>
				</el-submenu>
			</el-menu>
		</div>
		<div class="search-city-wr__phone">
			<slot name="phones">
				<span>8 (800) 777-83-29</span>
				<span>+7 (495) 642-26-18</span>
			</slot>
		</div>
		<div class="search-city-wr__shops">
			<span class="modal-container__shops__title">Магазины</span>
			<slot name="shops" class="address">
				<span>г. Москва <br> ул Большая Очаковская., 47 А</span>
			</slot>
		</div>
	</div>
</template>
<script>
	import VuePerfectScrollbar from 'vue-perfect-scrollbar';
	import ElementUI from 'element-ui';
	import Rest from 'Rest';

	Vue.use(ElementUI);
	Vue.use(VuePerfectScrollbar);
	Vue.use(Rest, {
		rest: {	baseURL: '/rest/services/city'}
	});

	window.eventVue = new Vue();

	export default {
		data() {
			return {
				search: '',
				cities: [],
				settings: {
					maxScrollbarLength: 60,
				},
				topTitle: ''
			}
		},
		props: {
			cityName: {
				type: String,
				default: 'Выберите свой город'
			}
		},
		components: {
			VuePerfectScrollbar
		},
		methods: {
			onClick(title, id) {
				this.topTitle = title;
				this.$rest.post('/setMyCity', {id}).then(res => {
					if(res.data.STATUS === 1){
						window.eventVue.$emit('newTopTitle', title);
						window.eventVue.$emit('closeModal');
					}
				});
			}
		},
		computed: {
			filteredCity() {
				return this.cities.filter((el) => {
					return el.SEARCH.indexOf(this.search.toUpperCase()) !== -1;
				})
			},
		},
		created(){
			this.$rest.get('/getCityList').then(res => {
				if(res.data.STATUS === 1){
					this.cities = res.data.DATA;
				}
			});
			this.topTitle = this.cityName;
		}
	}
</script>
