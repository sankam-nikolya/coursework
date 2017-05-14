;(function( $ ) {
	
	"use strict";

$(document).ready(function() {
	$('.bg-image').addClass("slide-bottom");
	$('.wrapper').addClass('slide-bottom-m');
	setTimeout(function() {
		$('.header').addClass('fade-in');
	},550);

	var wrapper = $(".upload-img"),
        inp = wrapper.find('input');
    var file_api = (window.File && window.FileReader && window.FileList && window.Blob ) ? true : false;

    inp.change(function() {
    	console.log(true);
        var file_name;
        if(file_api && inp[0].files[0])
            file_name = inp[0].files[0].name;
        else
            file_name = inp[0].val().replace("C:\\fakepath\\", '');

        if(!file_name.length)
            return;

        wrapper.find('span').text(file_name);
    }).change();
});

})(jQuery);