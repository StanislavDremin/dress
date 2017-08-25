/**
 * Created by dremin_s on 27.07.2017.
 */
/** @var o is */
/** @var o $ */
"use strict";
import filterApp from './filterApp.vue';

window.AB.components.smartFilter = function(params = {}) {
	this.defaultOptions = {
		container: '#filter_main'
	};
	this.filterItems = params;
	this.options = Object.assign({}, this.defaultOptions);

	const _self = this;

	this.init = () => {
		$(() => {
			this.addOption('$oFilter', $(this.getOption('container')));
			if(this.getOption('$oFilter').length > 0){

				const MainFilter = new Vue({
					data: {
						filterItems: _self.filterItems
					},
					template: `<filter-app :items="filterItems"/>`,
					components: {filterApp}
				});
				MainFilter.$mount(this.getOption('container'));

			} else {
				console.error('Не найден DOM-элемент для создания приложения');
			}
		});
	};

	this.getOption = (name) => {
		if(name === undefined){
			return null;
		}
		if(this.options.hasOwnProperty(name)){
			return this.options[name];
		}

		return null;
	};

	this.addOption = (key, val) => {
		this.options[key] = val;

		return this;
	};

	return {init: this.init, getOption: this.getOption, addOption: this.addOption};
};