/**
 * Created by dremin_s on 24.07.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import SearchComponent from './searchTop.vue';

$(function () {
	const Search = new Vue({
		el: '#search',
		components: {
			SearchComponent
		}
	});
	const SearchNew = new Vue({
		el: '#search-mobile',
		data: {
			searchDesc: true
		}
	});
});