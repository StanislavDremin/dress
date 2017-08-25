/**
 * Created by dremin_s on 17.07.2017.
 */
/** @var o React */
/** @var o ReactDOM */
/** @var o is */
/** @var o $ */
"use strict";
export default () => {
	if (navigator.userAgent.toLowerCase().indexOf('chrome') > -1) {
		let args = ['\n %c Made with ❤️ by AbraXabra %c https://abraxabra.ru/ %c %c 🐳 \n\n', 'border: 1px solid #000;color: #000; background: #fff001; padding:5px 0;', 'color: #fff; background: #1c1c1c; padding:5px 0;border: 1px solid #000;', 'background: #fff; padding:5px 0;', 'color: #b0976d; background: #fff; padding:5px 0;'];
		window.console.log.apply(console, args);
	} else if (window.console) {
		window.console.log('Made with love ❤️ AbraXabra - https://abraxabra.ru/  ❤️');
	}
}
