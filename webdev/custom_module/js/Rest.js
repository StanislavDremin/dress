/**
 * Created by dremin_s on 13.07.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import swal from 'sweetalert.min';
import Preloader from './Preloader.vue';

let Ajax;
if (window.axios === undefined) {
	Ajax = require('axios');
} else {
	Ajax = window.axios;
}
export default {
	install(Vue, options = {}){
		const SHOW_SYSTEM_ERR = true;

		let ajaxOption = Ajax.defaults;

		let ErrorRest = false;
		ajaxOption.validateStatus = function (status) {
			if (status >= 400) {
				ErrorRest = true;
			}
			return status;
		};
		ajaxOption.headers = {'ContentType': 'application/json', 'Accept': 'application/json'};
		if (options.hasOwnProperty('rest')) {
			ajaxOption = Object.assign({}, options.rest);
		}

		if (!ajaxOption.transformResponse instanceof Array || ajaxOption.transformResponse === undefined)
			ajaxOption.transformResponse = [];



		ajaxOption.transformResponse.push(function (data) {
			if (typeof data === 'string') {
				data = JSON.parse(data);
			}

			let errTxt = 'Внутренняя ошибка сервера';
			if (data === null) {
				data = {
					DATA: null,
					ERRORS: null,
					STATUS: 0,
					SYSTEM: [],
				};
				swal({title: "", text: errTxt, type: "error"});
			} else if (data.STATUS === 0) {
				if (data.ERRORS !== null && data.ERRORS.length > 0) {
					errTxt = data.ERRORS.join("\n");
					// if (SHOW_SYSTEM_ERR === true && data.SYSTEM !== null && data.SYSTEM.length > 0) {
						// errTxt = data.SYSTEM.join("\n");
						// data.SYSTEM = null;
					// }
				}
				swal({
					title: 'Ошибка',
					text: errTxt,
					type: 'error'
				});
			}

			return data;
		});

		$(function () {
			let sessid = $('meta[name=ss]').attr('content');
			ajaxOption.headers = {'X-csrf-data': sessid};
			Vue.prototype.$rest = Ajax.create(ajaxOption);
		});
		Vue.mixin({
			data(){
				return {
					hasError: ErrorRest,
					showLoader: false,
					restOptions: {}
				}
			},
			watch: {
				hasError(){
					Preloader.$emit('ajax_error', true);
				}
			},
			methods: {
				startLoad(){
					this.showLoader = true;
				},
				stopLoad(){
					this.showLoader = false;
				},
				rest(){
					let option = Object.assign(ajaxOption, this.restOptions);
					return  Ajax.create(option);
				},
				// showAlert(){
				// 	let obj = {
				// 		title: 'Custom Function',
				// 		message: 'Click close to trigger custom function',
				// 		type: 'error',
				// 	};
				// 	console.info(this.$refs);
				// 	this.$refs.simplert.openSimplert(obj)
				// }
			},
			components: {
				Preloader,
			}
		})
	}
}