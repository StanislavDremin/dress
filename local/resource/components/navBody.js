import ElementUI from 'element-ui';
import SearchCity from './SearchCity.vue';

Vue.use(ElementUI);

$(function () {
	const navBody = new Vue({
		el: '#navBody',
		components: {
			SearchCity
		}
	});
});
