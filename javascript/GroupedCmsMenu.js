(function($) {
	
	$.entwine('ss', function($){
		
		/**
		 * When clicking a group heading, open/close the list instead of 
		 * loading its first child
		 */
		$('.cms-menu-list > li.children > a').entwine({
			onclick: function(e) {
				this.getMenuItem().toggle();
				e.preventDefault();
			}
		});
		
	});
	
}(jQuery));