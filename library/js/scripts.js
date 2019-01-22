/*
 * Scripts File
 * Author: Vic Lobins
 *
*/

/*
 * Get Viewport Dimensions
*/
function updateViewportDimensions() {
	"use strict";
	var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
	return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();

jQuery(document).ready(function($) {
	
	"use strict";
	
	viewport = updateViewportDimensions();
	
	$('img.hover-zoom').parent().addClass('hover-anchor');
	
	// Menu
	$('.menu-button').on('click', function(){
		$(this).parents('.header').toggleClass('active');
	});
	
	$('#menu-main-menu > .menu-item-has-children > a').on('click', function(e){
		e.preventDefault();
		$('#menu-main-menu > .menu-item-has-children > a').not( $(this) ).removeClass('active');
		$(this).toggleClass('active');
		$('.sub-menu').not( $(this).next('.sub-menu') ).removeClass('active');
		$(this).next('.sub-menu').toggleClass('active');
	});
	
	
	// Calendar
	$('.wpsbc-legend-item-icon-3 + .wpsbc-legend-item-name').text('Changeover');
	
	var $newEl = '<div class="hint">To request a booking, please use the button below.<a href="#" class="close-overlay">Close</a></div>';
	$('.wpsbc-calendars').append($newEl);
	$('.wpsbc-calendar-wrapper').on('click', function(){
		$('.hint').toggleClass('active');
	});
	
	$('.reveal-calendar').click(function(e){
		e.preventDefault();
		$('#wpcf7-f257-p2-o1, #wpcf7-f257-p13-o1').addClass('active');
	});
	
	$('.reveal-features').click(function(e){
		e.preventDefault();
		$('.full-features').addClass('active');
	});
	
	$('.close-overlay').on('click', function(e){
		e.preventDefault();
		$('#wpcf7-f257-p2-o1, #wpcf7-f257-p13-o1, .hint, .full-features').removeClass('active');
	});
	
	$(window).on('resize load', function(){
		if( viewport.width < 768 ) {
			$('body').addClass('is-mobile');
		} else {
			$('body').removeClass('is-mobile');
		}
		
		$('.nextend-thumbnail-scroller-group > div').each(function() {
			var total = (100 / $('.nextend-thumbnail-scroller-group > div').length);
			$(this).css('width', 'calc('+total+'% - 8px)');
		});
	});
	
	$(window).on('scroll', function(){
		if( $(this).scrollTop() >= 100 ) {
			$('.header').addClass('scrolled');
		} else {
			$('.header').removeClass('scrolled');
		}
	});
});