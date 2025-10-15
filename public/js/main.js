/* ======================================
-----------------------------------------
	INSTYLR | Fashion HTML Template
	Version: 1.0
 ---------------------------------------
 =======================================*/


'use strict';


$(window).on('load', function() {
	/*------------------
		Preloder
	--------------------*/
	$(".loader").fadeOut();
	$("#preloder").delay(200).fadeOut("slow");

});

(function($) {
	/*------------------
		Background Set
	--------------------*/
	$('.set-bg').each(function() {
		var bg = $(this).data('setbg');
		$(this).css('background-image', 'url(' + bg + ')');
	});

})(jQuery);
