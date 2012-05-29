(function($) {
	$("#MainMenu li.children").hover(
		function() { $(this).addClass("active").find("ul").show(); },
		function() { $(this).removeClass("active").find("ul").hide(); }
	);

	// ie hack to set css width explicitly on sub menus
	// so that the sub menu items can expand to that width
	$('#MainMenu li.children ul').each(function(){
		$(this).css('width', $(this).width());
	});
})(jQuery);