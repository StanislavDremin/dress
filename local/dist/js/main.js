$(function () {
	$(".lazy_item").show().lazyload({
		effect : "fadeIn"
	});

	// slider-top index
	$('.fade').slick({
		dots: false,
		infinite: true,
		speed: 500,
		fade: true,
		cssEase: 'linear'
	});


	$('.responsive').each(function () {
		let cntProducts = $(this).find('.news-shops__items').length;
		let cntBrands =  $(this).find('.brands__items').length;
		if(cntProducts > 2 || cntBrands > 2){
			let limit = cntProducts >= 5 ? 5 : cntProducts;

			if(cntBrands > 0){
				limit = 4;
			}

			var prevArrow = '<div class="slick_left_arr"><svg \n' +
				' xmlns="http://www.w3.org/2000/svg"\n' +
				' xmlns:xlink="http://www.w3.org/1999/xlink"\n' +
				' width="35px" height="25px">\n' +
				'<path fill-rule="evenodd"  fill="rgb(0, 0, 0)"\n' +
				' d="M11.581,0.855 C12.046,0.373 12.821,0.373 13.303,0.855 C13.768,1.320 13.768,2.094 13.303,2.558 L4.576,11.285 L33.372,11.285 C34.043,11.286 34.577,11.820 34.577,12.491 C34.577,13.162 34.043,13.713 33.372,13.713 L4.576,13.713 L13.303,22.423 C13.768,22.905 13.768,23.680 13.303,24.145 C12.821,24.626 12.045,24.626 11.581,24.145 L0.789,13.352 C0.308,12.887 0.308,12.113 0.789,11.648 L11.581,0.855 Z"/>\n' +
				'</svg></div>';
			var nextArrow = '<div class="slick_right_arr"><svg \n' +
				' xmlns="http://www.w3.org/2000/svg"\n' +
				' xmlns:xlink="http://www.w3.org/1999/xlink"\n' +
				' width="35px" height="25px">\n' +
				'<path fill-rule="evenodd"  fill="rgb(0, 0, 0)"\n' +
				' d="M23.417,0.855 C22.952,0.373 22.177,0.373 21.696,0.855 C21.231,1.320 21.231,2.094 21.696,2.558 L30.423,11.285 L1.627,11.285 C0.955,11.286 0.421,11.820 0.421,12.491 C0.421,13.162 0.955,13.713 1.627,13.713 L30.423,13.713 L21.696,22.423 C21.231,22.905 21.231,23.680 21.696,24.145 C22.177,24.626 22.953,24.626 23.417,24.145 L34.210,13.352 C34.691,12.887 34.691,12.113 34.210,11.648 L23.417,0.855 Z"/>\n' +
				'</svg></div>';

			$(this).slick({
				dots: false,
				infinite: true,
				speed: 300,
				slidesToShow: limit,
				slidesToScroll: 3,
				prevArrow: prevArrow,
				nextArrow: nextArrow,
				responsive: [
					{
						breakpoint: 1200,
						settings: {
							slidesToShow: 4,
							slidesToScroll: 3,
							infinite: true,
							dots: true,
							arrows: true,
							mobileFirst: true
						}
					},
					{
						breakpoint: 968,
						settings: {
							slidesToShow: 3,
							slidesToScroll: 2,
							infinite: true,
							dots: true,
							arrows: false,
							mobileFirst: true
						}
					},
					{
						breakpoint: 550,
						settings: {
							slidesToShow: 2,
							slidesToScroll: 2,
							infinite: true,
							dots: true,
							arrows: false,
							mobileFirst: true
						}
					}
				]
			});
		}
	});

});