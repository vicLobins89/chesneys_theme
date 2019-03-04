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
	
	var value = [];
	$('.js-brochure-input').each(function() {
		$(this).on('change', function(){
			if( !$(this).is(':checked') ) {
				value.pop($(this).val());
			} else {
				value.push($(this).val());
			}
			$('input.brochures').val(value);
			console.log(value);
		});
	});
	
	// Reveal clicks
	$('.for-you').click(function(e){
		e.preventDefault();
		$('.hover-link').not(this).removeClass('active');
		$(this).toggleClass('active');
		$('.reveal-copy:not(.for-you)').removeClass('active');
		$('.reveal-copy.for-you').toggleClass('active');
	});
	$('.for-client').click(function(e){
		e.preventDefault();
		$('.hover-link').not(this).removeClass('active');
		$(this).toggleClass('active');
		$('.reveal-copy:not(.for-client)').removeClass('active');
		$('.reveal-copy.for-client').toggleClass('active');
	});
	$('.for-development').click(function(e){
		e.preventDefault();
		$('.hover-link').not(this).removeClass('active');
		$(this).toggleClass('active');
		$('.reveal-copy:not(.for-development)').removeClass('active');
		$('.reveal-copy.for-development').toggleClass('active');
	});
	
	// Video Gallery
	$('.play').on('click', function(){
		var mainVid = $('.main-window').html(),
			currentVid = $(this).next().html();
		
		$('.main-window').html(currentVid);
        $(this).next().html(mainVid);
	});
	
	// QTY
	$('.qty-btn').on('click', function(e){
		e.preventDefault();
		var value = $(this).siblings('.quantity').find('input').val();
		
		if( $(this).hasClass('up') ) {
			value = parseInt(value) + 1;
		} else {
			value = parseInt(value) - 1;
		}
		
		$(this).siblings('.quantity').find('input').val(value);
		$('button[name=update_cart]').removeAttr("disabled");
	});
	
	// Add image-link to caption
	$('a.image-link').each(function(){
		var url = $(this).attr("href");
		$(this).next('.wp-caption-text').attr('onclick','window.location="'+url+'";');
	});
	
	// Fix breadcrumb link
	$('.menu-breadcrumb a:contains("Fireplaces")').each(function(){
		var oldUrl = $(this).attr("href");
		var newUrl = oldUrl.replace("/category/fireplaces", "/fireplaces");
		$(this).attr("href", newUrl);
	});
	$('.menu-breadcrumb a:contains("Stoves")').each(function(){
		var oldUrl = $(this).attr("href");
		var newUrl = oldUrl.replace("/category/stoves", "/stoves");
		$(this).attr("href", newUrl);
	});
	
	// Menu
	$('.menu-button').on('click', function(){
		$(this).parents('.header').toggleClass('active');
	});
	
	$('.primary-nav > li.menu-item-has-children').hover(function(){
		$('.primary-nav > li.menu-item-has-children').not(this).removeClass('active');
		$(this).addClass('active');
		$('#nav_widget').addClass('active');
	});
	
	$('#content, #main, .socket, .logo, .primary-nav > li:not(.menu-item-has-children)').hover(function(){
		$('.primary-nav > li').removeClass('active');
		$('#nav_widget').removeClass('active');
	});
	
	function menuResize(){
		var menuWidth = $('.primary-nav').outerWidth(),
			menuHeight = $('.primary-nav').outerHeight(),
			subMenuHeight = $('.primary-nav > li ul.sub-menu').outerHeight();
		$('.primary-nav > li > .sub-menu, .primary-nav > li > .sub-menu > li > .sub-menu').width( menuWidth/3 );
		$('#nav_widget').css({
			"top": menuHeight,
			"padding-top": subMenuHeight
		});
	}
	menuResize();
	
	$(window).on('resize load', function(){
		if( viewport.width < 768 ) {
			$('body').addClass('is-mobile');
		} else {
			$('body').removeClass('is-mobile');
		}
		menuResize();
	});
	
	$(window).on('scroll', function(){
		if( $(this).scrollTop() >= 100 ) {
			$('.header').addClass('scrolled');
		} else {
			$('.header').removeClass('scrolled');
		}
	});
});