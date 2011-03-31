(function($) {
	$("#MainMenu li.children").hover(
		function() { $(this).addClass("active").find("ul").show(); },
		function() { $(this).removeClass("active").find("ul").hide(); }
	);
})(jQuery);