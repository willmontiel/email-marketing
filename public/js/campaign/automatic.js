$(function (){
	$(".switch-campaign").bootstrapSwitch({
		onColor: 'success',
		offColor: 'danger'
	});
	
	var panelContainer = new PanelContainer('#panel-container');		
	model = new Model();
	model.filtersAllowed(false);
	model.setPanelContainer(panelContainer);
	model.setSerializerObject(serializerObject);
	model.serializer();

	$('#autosend_form').submit(function() {
		var target = JSON.stringify(model.getModel());
		var target_obj = $(this).find("input[name='target']")[0];
		$(target_obj).val(target);
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