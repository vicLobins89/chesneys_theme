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

if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
	document.body.className += ' ' + 'is-mobile';
}

jQuery(document).ready(function($) {
	
	"use strict";
	
	viewport = updateViewportDimensions();
	
	// Hover links
	$('.hover-link').on('click', function(e){
		e.preventDefault();
	});
	
	// Callback
	$('a.callback').click(function(e){
		e.preventDefault();
		var productName;
		
		if( $('body').hasClass('single') ) {
			productName = $('.product_title').text();
		} else {
			productName = $(this).closest('.product').find('.woocommerce-loop-product__title').text();
		}
		
		$('input.product').val(productName);
		$('.overlay ').addClass('active');
		console.log(productName);
	});
	
	$('a.close-overlay').click(function(e){
		e.preventDefault();
		$('.overlay').removeClass('active');
	});
	
	// Brochure Request
	var pdfHref = [],
		pdfName = [];
	$('.js-brochure-input').each(function(){
		$(this).on('change', function(e){
			e.preventDefault();
			var newHref = $(this).val(),
				newName = $(this).attr('name');
			
			if( !$(this).is(':checked') ) {
				pdfHref = jQuery.grep(pdfHref, function(val) {
					return val !== newHref;
				});
				pdfName = jQuery.grep(pdfName, function(val) {
					return val !== newName;
				});
			} else {
				pdfHref.push(newHref);
				pdfName.push(newName);
			}
			$('input.brochures').val(pdfName);
			//create_zip(newHref, pdfName);
		});
	});
	
	function create_zip(files, names) {
		var zip = new JSZip(),
			content;
		
		var i;
		for( i = 0; i < files.length; i++ ) {
			zip.add(files[i], names[i]);
		}
		content = zip.generate();
		location.href="data:application/zip;base64," + content;
	}
	
	// Force select on brochure
	$('input[type="checkbox"]').on('change', function(){
		testChecked();
	});
	testChecked();
	function testChecked() {
		if( $('.js-brochure-input:checked').length > 0) {
			$('.download').prop('disabled', false);
		} else {
			$('.download').prop('disabled', true);
		}
	}
	
	$('.download:disabled').click(function(){
		console.log('click');
	});

	function downloadAll(urls, names) {
		var link = document.createElement('a');

		link.style.display = 'none';

		document.body.appendChild(link);

		for (var i = 0; i < urls.length; i++) {
			link.setAttribute('download', names[i]);
			link.setAttribute('href', urls[i]);
			link.click();
		}

		document.body.removeChild(link);
	}
	
	document.addEventListener( 'wpcf7mailsent', function( event ) {
		if ( '2607' === event.detail.contactFormId ) {
			downloadAll(pdfHref, pdfName);
		}
	}, false );
	
	
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
	$('.menu-breadcrumb a:contains("Outdoor Living")').each(function(){
		var oldUrl = $(this).attr("href");
		var newUrl = oldUrl.replace("/category/outdoor-living", "/outdoor");
		$(this).attr("href", newUrl);
	});
	$('.menu-breadcrumb a:contains("Heat Range")').each(function(){
		var oldUrl = $(this).attr("href");
		var newUrl = oldUrl.replace("/category/outdoor-living/heat-range", "/outdoor/shop");
		$(this).attr("href", newUrl);
	});
	$('.menu-breadcrumb a:contains("Gourmet Range")').each(function(){
		var oldUrl = $(this).attr("href");
		var newUrl = oldUrl.replace("/category/outdoor-living/gourmet-range", "/outdoor/shop");
		$(this).attr("href", newUrl);
	});
	
	// Search dropdown fix
	$('.postform option[value="post"]').text('News');
	
	// Menu
	$('.menu-button').on('click', function(){
		$(this).parents('.header').toggleClass('active');
	});
	
	function menuResize(){
		var menuWidth = $('.primary-nav').outerWidth(),
			menuHeight = $('.primary-nav').outerHeight(),
			subMenuHeight = $('.primary-nav > li ul.sub-menu').outerHeight(),
			headerHeight = $('.header').outerHeight();
		
		if( viewport.width < 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$('.primary-nav > li > .sub-menu, .primary-nav > li > .sub-menu > li > .sub-menu').width('auto');
			$('#nav_widget').css({
				"display": 'none'
			});
			$('#content, #primary').css('padding-top', headerHeight);
		} else {
			$('.primary-nav > li > .sub-menu, .primary-nav > li > .sub-menu > li > .sub-menu').width( menuWidth/3 );
			$('#nav_widget').css({
				"top": menuHeight,
				"padding-top": subMenuHeight
			});
			$('#content, #primary').css('padding-top', 'auto');
		}
	}
	
	$(window).on('resize load', function(){
		if( viewport.width < 768 ) {
			// Reveal cliks
			$('.reveal-copy').each(function(){
				var selected = $(this).attr('class').split(' ')[1],
					selectedHtml = $(this).html();
				
				$(this).empty();
				$('.hover-link.'+selected+'').parent().append(selectedHtml);
			});
			
			$('.hover-link').each(function(){
				var linkHeight = $(this).outerHeight(),
					textHeight = $(this).next().outerHeight();
				$(this).next().css('top', (linkHeight-textHeight));
			});
		} else {
			// Reveal clicks
			$('.hover-link').on('click', function(){
				var selected = $(this).attr('class').split(' ')[1];

				$('.hover-link').not(this).removeClass('active');
				$(this).toggleClass('active');

				$('.reveal-copy:not(.'+selected+')').removeClass('active');
				$('.reveal-copy.'+selected+'').toggleClass('active');
			});
			
			// image-column bg
			$('.image-column.column-4-8 .col-6:last-child, .image-column.column-8-4 .col-6:first-child, .woocommerce-product-gallery').each(function(){
				var imgSrc = $(this).find('img').attr('src');
				$(this).find('img').css('visibility', 'hidden');
				$(this).css({
					'background-image': 'url("'+imgSrc+'")',
					'background-repeat': 'no-repeat',
					'background-size': 'cover'
				});
			});
		}
	});
	
	$(window).on('resize load', function(){
		if( viewport.width < 768 || /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
			$('body').addClass('is-mobile');
			
			// Menu
			$('.primary-nav > li.menu-item-has-children').unbind('mouseenter mouseleave');
			
			$('.primary-nav > li').click(function(){
				$('.primary-nav > li').not(this).removeClass('active');
				$(this).addClass('active');
			});
			
			$('.primary-nav > li > ul > li').click(function(){
				$('.primary-nav > li > ul > li').not(this).removeClass('active');
				$(this).addClass('active');
			});
			
			$('.primary-nav > li > ul > li > ul > li').click(function(){
				$('.primary-nav > li > ul > li > ul > li').not(this).removeClass('active');
				$(this).addClass('active');
			});
		} else {
			$('body').removeClass('is-mobile');

			$('html').click(function(){
				$('.primary-nav > li').removeClass('active');
				$('#nav_widget').removeClass('active');
			});
			
			$('.primary-nav > li.menu-item-has-children').on('click', function(e){
				e.preventDefault();
				e.stopPropagation();
				$('.primary-nav > li.menu-item-has-children').not(this).removeClass('active');
				$(this).toggleClass('active');
				if( $(this).hasClass('active') ) {
					$('#nav_widget').addClass('active');
				} else {
					$('#nav_widget').removeClass('active');
				}
			});
			
			$('.primary-nav > li.menu-item-has-children li').on('click', function(e){
				e.stopPropagation();
			});
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