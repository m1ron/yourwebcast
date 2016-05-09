/*jslint nomen: true, regexp: true, unparam: true, sloppy: true, white: true, node: true */
/*global window, console, document, $, jQuery, google */

/**
 * On document ready
 */
$(document).ready(function () {

	/** Fastclick */
	FastClick.attach(document.body);


	/** Detecting portrait/landscape */
	$(window).on('resize', function () {
		var _body = $('body');
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

	/** Layout */
	$('body').wrapInner('<div class="spacer"></div>').wrapInner('<div class="layout-in"></div>').wrapInner('<div class="layout"></div>');


	/** Navigation */
	$('.nav').each(function () {
		$('li').each(function () {
			$('a', this).wrapInner('<span class="title"></span>').wrapInner('<span class="inner"></span>');
		});
	});


	/** Gallery */
	$('.gallery').slick({
		slidesToShow: 1,
		slidesToScroll: 1,
		fade: true
	});

});