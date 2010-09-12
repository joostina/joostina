/*

	----------------------------------------------------------------------------------------------------
	Accessible News Slider
	----------------------------------------------------------------------------------------------------
	
	Author:
	Brian Reindel
	
	Author URL:
	http://blog.reindel.com

	License:
	Unrestricted. This script is free for both personal and commercial use.

*/

jQuery.fn.accessNews = function( settings ) {
	settings = jQuery.extend({
        headline : "Top Stories",
        speed : "normal",
		slideBy : 2
    }, settings);
    return this.each(function() {
		jQuery.fn.accessNews.run( jQuery( this ), settings );
    });
};
jQuery.fn.accessNews.run = function( $this, settings ) {
	jQuery( ".javascript_css", $this ).css( "display", "none" );
	var ul = jQuery( "ul:eq(0)", $this );
	var li = ul.children();
	if ( li.length > settings.slideBy ) {
	  
		var $next = jQuery( "a#next");
		var $back = jQuery( "a#back");
		var liWidth = jQuery( li[0] ).width();
		var animating = false;
		ul.css( "width", ( li.length * liWidth ) );

		$next.click(function() {

			if ( !animating ) {
				animating = true;
				offsetLeft = parseInt( ul.css( "left" ) ) - ( liWidth * settings.slideBy );
                		   
				if ( offsetLeft + ul.width() > 0 ) {
					ul.animate({
						left: offsetLeft
					}, settings.speed, function() {
						if ( parseInt( ul.css( "left" ) ) + ul.width() <= (liWidth * settings.slideBy)-liWidth ) {
          					ul.animate({
        						left: 0
        					}, settings.speed, function() {animating = false;});
                    
                    
						}
						animating = false;
					});
				} else {
				    
				    ul.animate({
  						left: 0
   					}, settings.speed, function() {animating = false;});
				}
			}
			return false;
		});
		$back.click(function() {
			if ( !animating ) {
				animating = true;
				offsetRight = parseInt( ul.css( "left" ) ) + ( liWidth * settings.slideBy );
				if ( offsetRight + ul.width() <= ul.width() ) {
					ul.animate({
						left: offsetRight
					}, settings.speed, function() {
						if ( parseInt( ul.css( "left" ) ) >0 ) {
  					         ul.animate({
						          left: -((li.length-settings.slideBy) * liWidth)
	                           }, settings.speed, function() {animating = false;});
					  }
						animating = false;
					});
				} else {
                        ul.animate({
						          left: -((li.length-settings.slideBy) * liWidth)
	                           }, settings.speed, function() {animating = false;});
				}
			}
			return false;
		});
		$(".view_all").html(settings.headline + " - " + li.length + "  ( <a href=\"#\">Показать все</a> )</p>");
		jQuery( ".view_all > a, .skip_to_news > a").click(function() {
			var skip_to_news = ( jQuery( this ).html() == "Skip to News" );
			if ( jQuery( this ).html() == "Показать все" || skip_to_news ) {
				ul.css( "width", "auto" ).css( "left", "0" ).css( "height", "auto" );
				$next.css( "display", "none" );
				$back.css( "display", "none" );
				if ( !skip_to_news ) {
					jQuery( this ).html( "Свернуть" );
				}
			} else {
				if ( !skip_to_news ) {
					jQuery( this ).html( "Показать все" );
				}
				ul.css( "width", ( li.length * liWidth ) );
				$next.css( "display", "block" );
                $back.css( "display", "block" );
			}
			return false;
		});
	}
};