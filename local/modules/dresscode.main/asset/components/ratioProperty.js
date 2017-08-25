/**
 * Created by dremin_s on 20.07.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
import Ratio from './RatioProperty.vue';

$(function () {
	if($('#ratio_app').length > 0){
		new Vue({
			el: '#ratio_app',
			components: {
				Ratio
			}
		});
	}

});