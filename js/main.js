$(function() {

	// looping contact items
	// http://stackoverflow.com/questions/12065273/fade-in-out-text-loop-jquery
	$(function() {
	    var quotes = $(".loop-item");
	    var quoteIndex = -1;
	    
	    function showNextQuote() {
	        ++quoteIndex;
	        quotes.eq(quoteIndex % quotes.length)
	            .show()
	            // .addClass("on")
	            .delay(2000)
	            // .removeClass("on")
	            .hide(0, showNextQuote);
	    }
	    
	    showNextQuote();
	});


	// menu
	$("#menu-button, #menu a").click(function() {
		$("#menu-button").toggleClass("on");
		$("body").toggleClass("modal-on");
	});

});