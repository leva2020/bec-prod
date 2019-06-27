var $ = jQuery,
	h_screen = jQuery(window).height(),
	w_screen = jQuery(window).width();

jQuery(document).ready(function() {

	$(".navigation a.expand").click(function(){
		$(this).toggleClass("active");
		$(".navigation .reveal").slideToggle();
	});

	$(".navigation .reveal nav.main ul li i").click(function(){
		$(this).parent().toggleClass("open");
	});
	
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

	if(($(window).height() + 100) < $(document).height()){
        $('#top-link-block').addClass('show').affix({
            offset: {top:100}
        });
	}

});


// LIGTHBOX
$(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
	event.preventDefault();
    $(this).ekkoLightbox();
});

// MATCH HEIGHT OVERLAY TO IMAGE HEIGHT
$(window).bind("load resize scroll",function(e){
	
	var img1 = $(".page_component.gallery img");
	$(".page_component.gallery .overlayicon").css({width:img1.width(), height:img1.height()});
	
	var img2 = $(".promo_pods .videopod img");
	$(".promo_pods .videopod .overlayicon").css({width:img2.width(), height:img2.height()});
	
	var img3 = $(".promo_pods .imagepod img");
	$(".promo_pods .imagepod .overlayicon").css({width:img3.width(), height:img3.height()});
	
	var img4 = $(".video-component .image.video img");
	$(".video-component .image.video .overlayicon").css({width:img4.width(), height:img4.height()});
	
	var img5 = $(".text-with-image_video .image.video img");
	$(".text-with-image_video .image.video .overlayicon").css({width:img5.width(), height:img5.height()});
	
	var img6 = $(".image_carousel .gallery img");
	$(".image_carousel .gallery .overlayicon").css({width:img6.width(), height:img6.height()});
	
	// VALIGN
	$('.banner .item:nth-child(1) .valign').valign();
	$('.banner .item:nth-child(2) .valign').valign();
	$('.banner .item:nth-child(3) .valign').valign();
	$('.banner .item:nth-child(4) .valign').valign();
	$('.banner .item:nth-child(5) .valign').valign();
	$('.banner .item:nth-child(6) .valign').valign();
	$('.banner .item:nth-child(7) .valign').valign();
	$('.banner .item:nth-child(8) .valign').valign();
	$('.banner .item:nth-child(9) .valign').valign();
	$('.banner .item:nth-child(10) .valign').valign();
	$('.banner .item:nth-child(11) .valign').valign();
	$('.banner .item:nth-child(12) .valign').valign();
});
