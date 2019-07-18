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
    
    $('.pdf-sheet').click(function(e){
        e.preventDefault();
        
        var doc = new jsPDF();

        doc.text('Hello world!', 10, 10);
        doc.save('a4.pdf');
    });
    
    // Shows drawings on US
    if( $('body').hasClass('us-site') ) {
        $('.drawings-link a').parent().detach().appendTo('.product-details');
    }
    
    //Add class to tags
    var tagName = $('input.woof_checkbox_term');
    tagName.each(function(){
       $(this).parent().addClass( 'tag-'+$(this).attr('name') ); 
    });
	
	// Geo close button
	$('#geo_popup .close-geo').click(function(e){
		e.preventDefault();
		$('#geo_popup').remove();
	});
	
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
		});
	});
	
	// Force select on brochure
	function testChecked() {
		if( $('.js-brochure-input:checked').length > 0) {
			$('.download').prop('disabled', false);
			$('.submit-overlay').removeClass('active');
			$('.submit-overlay .error').remove();
		} else {
			$('.download').prop('disabled', true);
			$('.submit-overlay').addClass('active');
		}
	}
	
	$('input[type="checkbox"]').on('change', function(){
		testChecked();
	});
	testChecked();
	
	$('.submit-overlay.active .click').on('click', function(){
		$('.submit-overlay').append('<div class="error">Please select at least one brochure to download</div>');
	});
	
	
	function downloadAll(urls, names) {
		var link = document.createElement('a');

		link.style.display = 'none';

		document.body.appendChild(link);

		for (var i = 0; i < urls.length; i++) {
			link.setAttribute('href', urls[i]);
			link.setAttribute('download', names[i]);
			link.click();
		}

		document.body.removeChild(link);
	}
	
	function create_zip(files, names) {
		var zip = new JSZip(),
			brochures = zip.folder("brochures"),
			count = 0;
		
		$.each(files, function(index, value) {
			$.ajax({
				url: value,
				type: "GET",
				contentType: "application/pdf",
				mimeType:'text/plain; charset=x-user-defined'
			}).done(function(data){
				count += 1;
				brochures.file(names[index]+'.pdf', data, { binary: true });
				if( count === files.length ) {
					zip.generateAsync({type:"blob"}).then(function(content) {
						saveAs(content, "brochures.zip");
					});
				}
			}); 
		});
	}
	
	
	var ua = window.navigator.userAgent;
	var msie = ua.indexOf("MSIE ");
	document.addEventListener( 'wpcf7mailsent', function( event ) {
		if ( '2607' === event.detail.contactFormId ) {
			if( (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) ) {
				downloadAll(pdfHref, pdfName);
				create_zip(pdfHref, pdfName);
			} else {
				create_zip(pdfHref, pdfName);
			}
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
            
            if( $('body').hasClass('uk-site') ) {
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
            } else {
                $('.primary-nav > li.menu-item-has-children').on('hover', function(){
                    $('.primary-nav > li.menu-item-has-children').not(this).removeClass('active');
                    $(this).toggleClass('active');
                    if( $(this).hasClass('active') ) {
                        $('#nav_widget').addClass('active');
                    } else {
                        $('#nav_widget').removeClass('active');
                    }
                });
            }
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
	
	$(window).on('load', function(){
		// Filter classes
		$('li .woof_childs_list_li:has(ul)').addClass('parent_li');
	});
	
	var waitForEl = function(selector, callback) {
		if (jQuery(selector).length) {
			callback();
		} else {
			setTimeout(function() {
			  waitForEl(selector, callback);
			}, 100);
		}
	};

	waitForEl('.has-searched', function() {
		if(window.location.hash) {
			var hash = window.location.hash.substring(1);
			
			if( hash === 'outdoor-living' ) {
				$('#filter__services .filter__toggler').trigger('click');
				$('input[value="Outdoor_Living"]').trigger('click');
				$('#applyFilterOptions').trigger('click');
			}
		}
	});
});
