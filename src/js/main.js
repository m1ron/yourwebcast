/*jslint nomen: true, regexp: true, unparam: true, sloppy: true, white: true, node: true */
/*global window, console, document, $, jQuery, google */

/**
 * On document ready
 */
$(document).ready(function () {

	window._body = $('body');

	/** Fastclick */
	FastClick.attach(document.body);

	/** Layout */
	_body.wrapInner('<div class="spacer"></div>').wrapInner('<div class="layout-in"></div>').wrapInner('<div class="layout"></div>');

	/** Detecting portrait/landscape */
	$(window).on('resize', function () {
		if ($(window).width() <= 768) {
			if ($(window).width() >= $(window).height()) {
				_body.addClass('body-landscape').removeClass('body-portrait');
			} else {
				_body.addClass('body-portrait').removeClass('body-landscape');
			}
		} else {
			_body.removeClass('body-portrait body-landscape');
		}
	}).trigger('resize');


	/** Navigation */
	$('.nav').each(function () {
		function closePage(e) {
			_body.removeClass('nav-hidden').removeClass('nav-page-visible');
			$('.page-visible').removeClass('page-visible');
			e.preventDefault();
		}

		$('.header').each(function () {
			$('.logo', this).on('click', closePage);
		});
		$('.back').each(function () {
			$(this).wrapInner('<span class="title"></span>').wrapInner('<span class="inner"></span>').on('click', closePage)
		});
		$('li', this).each(function () {
			$('a', this).wrapInner('<span class="title"></span>').wrapInner('<span class="inner"></span>').on('click', function (e) {
				_body.addClass('nav-hidden').addClass('nav-page-visible');
				$('.page-visible').removeClass('page-visible');
				$($(this).attr('href')).addClass('page-visible');
				e.preventDefault();
			});
		});
	});


	/** Gallery */
	$('.gallery').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		fade: true
	});

});