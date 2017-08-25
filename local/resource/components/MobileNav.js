import { TimelineMax } from "gsap";

let tl = new TimelineMax();
let tl2 = new TimelineMax();

$(function () {
	const MobileNav = new Vue({
		el: '#mobileNav',
		data: {
			show: false
		},
		methods: {
			ShowMobile() {
				this.show = !this.show;
				let $wrapHtml = $('html');
				$wrapHtml.addClass('close');
				if (this.show) {
					tl.to('#mobile-nav-wr', 0.4, {ease: Power4.easeOut, x: 250});
					tl2.to('#mobile-nav-body', 0.2, {ease: Sine.easeOut, x: 250});
				}
				else {
					tl.to('#mobile-nav-wr', 0.4, {ease: Power4.easeOut, x: 0});
					$wrapHtml.removeClass('close');
					tl2.to('#mobile-nav-body', 0.2, {ease: Sine.easeOut, x: 0});
				}
			}
		}
	});
})
