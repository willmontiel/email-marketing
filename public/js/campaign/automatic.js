$(function (){
	$(".switch-campaign").bootstrapSwitch({
		onColor: 'success',
		offColor: 'danger'
	});
	
	$("input[name='target']").on('click', function(){
		$('.target_active').removeClass('target_active');
		var target = $('#' + $(this)[0].value);
		target.addClass('target_active');
		$("select[name='target_selected[]']").attr('name', '');
		target.find('select').attr('name', 'target_selected[]');
	});

	$('#select-field').on('change', function(){
		var values = $(this).val().split('/');
		$('#from_email').val(values[1]);
		$('#from_name').val(values[0]);
	});

	$('#meta-tag').on('click', function() {
		if($(this).is(':checked')) {
			$("input[name='subject']").prop('disabled', true);
		}
		else {
			$("input[name='subject']").prop('disabled', false);
		}
	});
});

function newSender() {
	$('#select-from').hide();
	$('#new-from').show();
	$('#from_email').val('');
	$('#from_name').val('');
	$('#select-field').val('');
}

function senderList() {
	$('#new-from').hide();
	$('#select-from').show();
}