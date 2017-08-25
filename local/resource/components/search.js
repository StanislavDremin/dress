$(function () {
	const Search = new Vue({
		el: '#search',
		data: {
			show: false
		}
	});

	new Vue({
		el: '#search-mobile',
		data: {
			searchDesc: true
		}
	});
});
