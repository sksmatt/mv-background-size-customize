/**
 * Customize Background-Size JS
 *
 * @package  Customize Background-Size
 * @author   Matt Varone | @sksmatt | mattvarone.com
 */
(function( wp, $ ){
    var api = wp.customize, body;

	api('mv_background_size', function (setting){
		setting.method = 'postMessage';
		setting.bind( function (to){
			$('iframe[name="customize-target"]').contents().find('body').css('background-size',to);
		});
	});
})( wp, jQuery );