jQuery(document).ready(function() {
	jQuery("#xencarousel1").jCarouselLite({
		btnNext: ".next",
		btnPrev: ".prev",
		auto: 5000,
		mouseWheel: "true",
		speed: 600,
		easing: "easeInSine",
		circular: "true",
		visible: 1,
		scroll: 1
	});
});