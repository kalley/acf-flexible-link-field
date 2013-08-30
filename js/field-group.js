(function($){

	/*
	*  Flexible Link toggle post type selector
	*
	*  @description:
	*  @since 3.5.2
	*  @created: 18/11/12
	*/
	$(document).ready(function() {

  	$('#acf_fields').on('click', '.field_option_flexible_link_toggle input[type="radio"]', function(){

  		// vars
  		var radio = $(this);
  		var tr = radio.closest('tr');

  		tr.nextAll('tr.field_option_flexible_link').hide().filter('.field_option_flexible_link-' + radio.val()).show();

  	});
	}).on('acf/field_form-open', function(e, field){

		$(field).find('.field_option_flexible_link_toggle input[type="radio"]:checked').each(function(){
			$(this).trigger('click');
		});

	});

})(jQuery);
