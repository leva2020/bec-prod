var $ = jQuery,
	h_screen = jQuery(window).height(),
	w_screen = jQuery(window).width();

function openSearch(){
	$(".search-block-form").addClass("show");
	setTimeout(function(){
		$(".search-block-form").addClass("active");
	},200);
	setTimeout(function(){
		$(".search-block-form").addClass("active");
		$(".search-block-form .wrapper-form").slideDown();
	},500);
}

function closeSearch(){
	$(".search-block-form .wrapper-form").slideUp();
	setTimeout(function(){
		$(".search-block-form").removeClass("active");
	},200);
	setTimeout(function(){
		$(".search-block-form").removeClass("show");
	},500);
}

jQuery(document).ready(function() {
	
	//SEARCH BLOCK
	if($(".search-block-form").length > 0){
		$("header.navbar.navbar-default .navbar-collapse #block-mainnavigation").append("<div class='search-block-form'><div class='icon-search'></div></div>");
		$("header.navbar.navbar-default .navbar-header").prepend("<div class='search-block-form mobile'><div class='icon-search'></div></div>");
	}
	
	
	//HOME
	$("header.navbar.navbar-default .search-block-form .icon-search").click(function(){
		if($(this).hasClass("open")){
			closeSearch();
			$(this).removeClass("open");
		}else{
			openSearch();
			$(this).addClass("open");
		}
	});
	if($(".section-slide").length > 0){
		$(".section-slide .owl-carousel").owlCarousel({
			loop:true,
			margin:0,
			items:1,
			nav:true,
			smartSpeed:1000
		})
	}
	if($(".section-recipes").length > 0){
		$(".section-recipes .owl-carousel").owlCarousel({
			loop:true,
			nav:true,
			autoplay:false,
			autoplayTimeout:5000,
			autoplayHoverPause:true,
			smartSpeed:1000,
			responsive : {
				0 : {
					items:1,
					margin:0
				},
				768 : {
					items:3,
					margin:10
				}
			}
		})	
	}
	if($(".section-blog").length > 0){
		if(w_screen <=768){
			$(".section-blog .owl-carousel").owlCarousel({
				center: true,
				loop:true,
				margin:0,
				items:1,
				nav:false,
				smartSpeed:1000
			});
		}
	}
	
	
	//NODE PRODUCT
	if($("body").hasClass("page-node-type-producto")){
		$("article .wrapper-product .photo-product .owl-carousel").owlCarousel({
			loop:true,
			margin:10,
			items:1,
			nav:true,
			smartSpeed:1000
		});
		if($(".owl-carousel.ingredients").length > 0){
			$("article .wrapper-product .product-ingredients .owl-carousel").owlCarousel({
				loop:true,
				margin:0,
				items:3,
				nav:false,
				dots: false,
				smartSpeed:1000
			})
		}
	}
})