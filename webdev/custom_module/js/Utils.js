/**
 * Created by GrandMaster on 23.06.2017.
 /** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";

export default {
	install (Vue, options) {

		Vue.prototype.$foreach = function (items, callback) {
			for (let key in items) {
				if (items.hasOwnProperty(key)) {
					callback(items[key], key);
				}
			}
		};

		Vue.prototype.$keyExist = function (key, items) {
			return items.hasOwnProperty(key);
		};

		Vue.prototype.$inData = function (search, items) {
			let res = false;
			if (search !== undefined) {
				for (let key in items) {
					if (items[key] == search) {
						res = true;
						break;
					}
				}
			}
			return res;
		};

		Vue.prototype.$indexOf = function (search, items) {
			let res = false;
			if (search !== undefined) {
				for (let key in items) {
					if (items[key] == search) {
						res = key;
						break;
					}
				}
			}
			return res;
		};

		Vue.prototype.$objToArray = function (items, callback = null) {
			let result = [];
			this.$foreach(items, (el, code) => {
				if (callback instanceof Function) {
					result.push(callback(el, code))
				} else {
					result.push(el);
				}
			});

			return result;
		};

		Vue.prototype.$forMap = function (items, callback) {
			let result, bType = Array;

			if (items instanceof Array) {
				result = [];
			}
			if (items instanceof Object) {
				result = {};
				bType = Object;
			}

			this.$foreach(items, (el, code) => {
				if (items instanceof Object) {
					result[code] = callback(el, code);
				} else if (items instanceof Array) {
					result.push(callback(el, code));
				}
			});

			return result;
		};

		Vue.prototype.$intVal = (val) => {
			let result = null;

			if(typeof val == 'string')
				val = val.trim(val);

			switch (typeof val) {
				case 'string':
					result = parseInt(val);
					if (!/^[0-9]+$/gi.test(result)) {
						result = null;
					}
					break;
				case 'number':
					result = val;
					if (!/^[0-9]+$/gi.test(result)) {
						result = null;
					}
					break;
				default:
					result = null;
					break;
			}
			return result;
		};

		Vue.prototype.$count = (data) => {
			let result = 0;
			if (data instanceof Object) {
				result = Object.keys(data).length;
			} else if (data instanceof Array) {
				result = data.length;
			}

			return result;
		};

		Vue.prototype.$arrayMerge = function () {
			let result = [];
			this.$foreach(arguments, (el, i) => {
				if (el instanceof Array) {
					el.forEach((item) => {
						result.push(item);
					});
				}
			});

			return result;
		};

		Vue.prototype.$addUrlParam = function (url, params) {
			let additional = '';
			let hash = '';
			let pos;

			for (let param in params) {
				url = this.$removeUrlParam(url, param);
				additional += (additional !== '' ? '&' : '') + param + '=' + params[param];
			}

			if ((pos = url.indexOf('#')) >= 0) {
				hash = url.substr(pos);
				url = url.substr(0, pos);
			}

			if ((pos = url.indexOf('?')) >= 0) {
				url = url + (pos !== url.length - 1 ? '&' : '') + additional + hash;
			}
			else {
				url = url + '?' + additional + hash;
			}

			return url;
		};

		Vue.prototype.$removeUrlParam = function (url, param) {
			if (param instanceof Array) {
				for (let i = 0; i < param.length; i++) {
					url = this.$removeUrlParam(url, param[i]);
				}
			}
			else {
				let pos, params;
				if ((pos = url.indexOf('?')) >= 0 && pos !== url.length - 1) {
					params = url.substr(pos + 1);
					url = url.substr(0, pos + 1);

					params = params.replace(new RegExp('(^|&)' + param + '=[^&#]*', 'i'), '');
					params = params.replace(/^&/, '');

					if (params instanceof String && params.length > 0) {
						url = url + params;
					}
					else {
						url = url.substr(0, url.length - 1);
					}
				}
			}
			return url;
		};

		Vue.prototype.$declOfNum = (function(titles, number){
			let cases = [2, 0, 1, 1, 1, 2];
			let declOfNumSubFunction = function(titles, number){
				number = Math.abs(number);
				return titles[ (number%100>4 && number%100<20)? 2 : cases[(number%10<5)?number%10:5] ];
			};

			return function(_titles) {
				if ( arguments.length === 1 ){
					return function(_number){
						return declOfNumSubFunction(_titles, _number)
					}
				}else{
					return declOfNumSubFunction.apply(null,arguments)
				}
			}
		})();



		Vue.prototype.$shift = function (items) {
			if(items instanceof Array){
				return items.shift();
			} else if(items instanceof Object){
				for(let k in items){
					return items[k];
				}
			}

			return null;
		};

		Vue.prototype.$formatPrice = function(data, dec = 0) {
			data = (data + "").replace(/(\D)/g, ".");

			let price = Number.prototype.toFixed.call(parseFloat(data) || 0, dec);
			let	price_sep = (typeof +price === 'integer' ? parseInt(price, 10) + "" : price);

			price_sep = price_sep.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1 ");

			return price_sep;
		}
	}
};
