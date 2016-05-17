/*jslint nomen: true, regexp: true, unparam: true, sloppy: true, white: true, node: true */
/*global window, console, document, $, jQuery, google */

/**
 * On document ready
 */
$(document).ready(function () {

	window._body = $('body');
	window._fullscreen = false;

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
		initScrolls();
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
				initScrolls();
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

	/** Fullscreen trigger */
	$('.js-fullscreen').each(function () {
		if (document.fullscreenEnabled || document.mozFullScreenEnabled || document.webkitFullscreenEnabled) {
			$(this).on('click', toggleFullScreen);
		} else {
			$(this).closest('li').hide();
		}
	});

	/*** Ajax popup ***/
	$('.js-popup').magnificPopup({
	});
});


/**
 * Init scrolls on website resize
 */
function initScrolls() {
	/** Scrolls init */
	$('.scroll.fullheight:visible').each(function () {
		var _this = $(this);
		$('p:last:not(.last-child), .entry:last:not(.last-child)', _this).addClass('last-child');
		if ($(window).width() >= 990) {
			var _col = _this.closest('.col').height();
			_this.siblings('*').each(function () {
				_col = _col - $(this).outerHeight(true);
			});
			if (!$(this).parent().hasClass('col')) {
				_this.parent().siblings('*').each(function () {
					_col = _col - $(this).outerHeight(true);
				});
			}
			_this.height(_col);
		} else {
			_this.height('auto');
		}
		if (_this.hasClass('ps-container')) {
			_this.perfectScrollbar('update');
		} else {
			_this.perfectScrollbar({
				suppressScrollX: true
			});
			$("<div/>").addClass("shadow").insertAfter(_this);
		}
	});
}


/**
 * Toggle fullscreen mode
 */
function toggleFullScreen(e) {
	/** Enter fullscreen **/
	function enter() {
		if (document.documentElement.requestFullscreen) {
			document.documentElement.requestFullscreen();
		} else if (document.documentElement.mozRequestFullScreen) {
			document.documentElement.mozRequestFullScreen();
		} else if (document.documentElement.webkitRequestFullscreen) {
			document.documentElement.webkitRequestFullscreen();
		} else if (document.documentElement.msRequestFullscreen) {
			document.documentElement.msRequestFullscreen();
		}
		_fullscreen = true;
	}

	/** Exit fullscreen **/
	function exit() {
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if (document.webkitExitFullscreen) {
			document.webkitExitFullscreen();
		}
		_fullscreen = false;
	}

	if (!_fullscreen) {
		enter();
	} else {
		exit();
	}
	e.preventDefault();
}


/**
 * Magnific Popup default settings
 */
$.extend(true, $.magnificPopup.defaults, {
	tClose: 'Close (Esc)',
	tLoading: '',
	closeMarkup: '<span title="%title%" class="mfp-close"><span class="mfp-in"></span></span>',
	ajax: {tError: '<a href="%url%">Content</a> not found'},
	settings: {cache: false},
	mainClass: 'mfp-zoom-in',
	midClick: true,
	removalDelay: 300,
	autoFocusLast: false,
	preload: false
});