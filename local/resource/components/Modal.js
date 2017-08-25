import SearchCity from './SearchCity.vue';
import Rest from 'Rest';
Vue.use(Rest, {
	rest: {	baseURL: '/rest/services/city'}
});
// register modal component
Vue.component('modal', {
	template: '#modal-template',
	data() {
		return {}
	},
	components: {
		SearchCity
	},
	methods: {
		handlerClose() {
			this.showModal = false;
			this.$emit('close');
		}
	},
	mounted() {
		window.eventVue.$on('closeModal', () => {
			this.$emit('close');
		});
	}
});

$(function () {
	// start app
	const Modal = new Vue({
		el: '#modalApp',
		data: {
			showModal: false,
			topTitle: 'Выберите свой город'
		},
		mounted() {
			window.eventVue.$on('newTopTitle', (title) => {
				this.topTitle = title;
			});
		},
		created(){
			this.$rest.get('/getMyCity').then(res => {
				if(res.data.STATUS === 1){
					this.topTitle = res.data.DATA.TITLE;
				}
			});
		}
	});
});
