/* Product List - General Functions */
(function($){

	$.fn.attachAddToActions = function(){
		$(this).hoverIntent({
			interval: 150,
			over: function(){ $(this).find(".addto").fadeIn(); },
			timeout: 500,
			out: function(){ $(this).find(".addto").fadeOut(); }
		});
	};
	
	$.fn.attachGalleryDisplay = function(){
		$(this).hoverIntent({
			interval: 150, 
			over: function(){ $(this).find(".gallery-display-overlay").fadeIn(); }, 
			timeout: 500, 
			out: function(){ $(this).find(".gallery-display-overlay").fadeOut(); }
		});
	};

})(jQuery);