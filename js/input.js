(function($) {
  /*
   *  acf/setup_fields
   *
   *  This event is triggered when ACF adds any new elements to the DOM.
   *
   *  @type	function
   *  @since	1.0.0
   *  @date	01/01/12
   *
   *  @param	event		e: an event object. This can be ignored
   *  @param	Element		postbox: An element which contains the new HTML
   *
   *  @return	N/A
   */
  var rURL = /^(?:https?\:\/\/|(\/))/;
  var waitTimer;

  $(document).on('acf/setup_fields', function(e, postbox) {
    $(postbox).find('.flexible_link-select').each(function() {
      var select = $(this).on('change', function() {
        $.getJSON('/api/get_post/?id=' + select.val() + '&post_type=any').done(function(data) {
          select.prevAll('.flexible_link').val(data.post.url);
        });
      });
    });
    $(postbox).find('.flexible_link').on('change', function() {
      var val = this.value;

      clearTimeout(waitTimer);
      waitTimer = null;

      if ( ! val ) return;

      // Normalize all urls to absolute
      if ( ! val.match(rURL) ) {
        val = 'http://' + val;
      }
      var a = document.createElement('a');
      a.href = val;

      this.value = a.href;
    }).on('keyup', function() {
      var input = $(this);
      clearTimeout(waitTimer);
      waitTimer = setTimeout(function() {
        input.triggerHandler('change');
      }, 1e3);
    });
  });
})(jQuery);