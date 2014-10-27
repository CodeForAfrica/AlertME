// Nicer Loading Messages

var Lp = {
  element: 0,
  text: {
    timeout: 0,
    cursor: 0,
    prettify: 0,
    messages: [
      'Loading',
      'Just a bit longer',
      'No really'
    ]
  },
  dots: {
    timeout: 0,
    cursor: 0,
    prettify: 0
  },

  progressbar: {
    
  }
};

Lp.text.prettify = function() {
  if (this.cursor == this.messages.length) {
    this.cursor = 0;
  };
  $('#load-pretty-text').html('- '+this.messages[this.cursor]+' -');
  this.cursor += 1;
};

Lp.dots.prettify = function() {
  if (this.cursor == 3) {
    this.cursor = 0;
  };
  var dots_html = '.';
  for (var i = 0; i < this.cursor; i++) {
    dots_html += '.';
  };
  $('#load-pretty-dots').html(dots_html);
  this.cursor += 1;
};

(function ( $ ) {

  $.fn.loadpretty = function( action ) {
    if ( action === "start") {
      // Start Prettify
      Lp.element = this;
      // Lp.text.messages[0] = this.html();

      this.html('<span id="load-pretty-text">'+
        Lp.text.messages[0]+'</span>'+
        '<span id="load-pretty-dots"></span>');

      // Lp.dots.prettify.cursor = 0;

      // Lp.dots.timeout = setInterval(function(){
      //   Lp.dots.prettify();
      // }, 1000);
      Lp.text.timeout = setInterval(function(){
        Lp.text.prettify();
      }, 5000);
        
    }

    if ( action === "stop" ) {
      // Stop Prettify
      clearInterval(Lp.text.timeout);
      clearInterval(Lp.dots.timeout);
    }

    if (action === "progressbar") {
      // Progress bar
    };
    
    return this;
  };

}( jQuery ));


