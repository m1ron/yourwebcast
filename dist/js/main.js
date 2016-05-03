/*jslint nomen: true, regexp: true, unparam: true, sloppy: true, white: true, node: true */
/*global window, console, document, $, jQuery, google */

/**
 * On document ready
 */
$(document).ready(function () {

	/** Fastclick */
	FastClick.attach(document.body);

	$(window).on('resize', function () {
		var _body = $('body');
		if ($(window).width() <= 768) {
			if(_body.height() >= 450){
				_body.addClass('body-vertical');
			} else {
				_body.removeClass('body-vertical');
			}
		} else if ($(window).width() <= 999) {
			if(_body.height() >= 940){
				_body.addClass('body-vertical');
			} else {
				_body.removeClass('body-vertical');
			}
		} else {
			_body.removeClass('body-vertical');
		}
	}).trigger('resize');

});