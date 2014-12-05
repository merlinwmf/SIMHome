jQuery(function($) {
	$( "#carousel1" ).rcarousel({
		orientation: "vertical",
		width: 450,
		height: 150,
		auto: {
			enabled: true,
			interval: 3000,
			direction: "next"
		}
	});

	$( "#carousel2" ).rcarousel({
		// orientation: "horizontal",
		visible: 2,
		step: 1,
		width: 190,
		height: 200,
		margin: 10,
		auto: {
			enabled: true,
			interval: 2000,
			direction: "next"
		}
	});
});