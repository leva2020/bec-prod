var $ = jQuery,
	h_screen = jQuery(window).height(),
	w_screen = jQuery(window).width();

jQuery(document).ready(function() {
	
	if($("section.image-carousel").length > 0){
		$("section.image-carousel .owl-carousel").owlCarousel({
			loop:true,
			margin:0,
			dots: false,
			nav:true,
			smartSpeed:1000,
			navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
			responsive : {
				0 : {
					items:1
				},
				480 : {
					items:2
				},
				769 : {
					items:3
				},
				1200 : {
					items:4
				}
			}
		});
	}
	
	if($(".main-slider").length > 0){
		$(".main-slider").owlCarousel({
			loop:true,
			items:1,
			margin:0,
			dots: false,
			nav:true,
			smartSpeed:1000,
			navText: ["<i class='fa fa-angle-left fa-3x'></i>","<i class='fa fa-angle-right fa-3x'></i>"]
		});
	}

})