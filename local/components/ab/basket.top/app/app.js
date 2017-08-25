/**
 * Created by dremin_s on 17.03.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import EventBus from 'EventBus';

$(function () {
	new Vue({
		el: '#basket_top_app',
		data: {
			count: 0
		},
		mounted(){
			EventBus.getBus().$on('onDressAddBasket', (data) => {
				this.count = data.quantity;
			});
		}
	})
});