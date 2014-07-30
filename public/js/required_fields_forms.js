$(function() {
	$('form').on('submit', function(e) {
		var required = $('.field-element-form-required');
		for (var i = 0 ; i < required.length; i++) {
			if($(required[i]).val().length === 0) {
				e.preventDefault();
			}
		}
	});
});


