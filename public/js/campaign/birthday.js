$(function (){
	$(".switch-campaign").bootstrapSwitch({
		onColor: 'success',
		offColor: 'danger'
	});
	
	var panelContainer = new PanelContainer('#panel-container');		
	model = new Model();
	model.setPanelContainer(panelContainer);
	model.setSerializerObject(serializerObject);
	model.serializer();

	$('#birthday_form').submit(function() {
		var target = JSON.stringify(model.getModel());
		var target_obj = $(this).find("input[name='target']")[0];
		$(target_obj).val(target);
		
		var status = [];
		status.push(check_data_status('name', 'input', 'un nombre'));
		status.push(check_data_status('target', 'input', 'los destinatarios'));
		status.push(check_data_status('hour', 'select', 'una hora'));
		status.push(check_data_status('minute', 'select', 'una hora'));
		status.push(check_data_status('meridian', 'select', 'una hora'));
		status.push(check_data_status('subject', 'input', 'un asunto'));
		status.push(check_data_status('from_email', 'input', 'un remitente'));
		status.push(check_data_status('from_name', 'input', 'un remitente'));
		
		var response = true;
		for(var i = 0; i < status.length; i++) {
			if(status[i]) {
				response = false;
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: status[i], sticky: false, time: 2000});
			}
		}
		
		if(response && content == null){
			response = false;
			$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'Aún no ha creado ningún tipo de contenido, por favor verifique la información', sticky: false, time: 2000});
		}
		
		return response;
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
	
	$('.create_content').on('click', function() {
		
		var target = JSON.stringify(model.getModel());
		var target_obj = $('#birthday_form').find("input[name='target']")[0];
		$(target_obj).val(target);
			
		var status = [];
		status.push(check_data_status('name', 'input', 'un nombre'));
		status.push(check_data_status('target', 'input', 'los destinatarios'));
		status.push(check_data_status('hour', 'select', 'una hora'));
		status.push(check_data_status('minute', 'select', 'una hora'));
		status.push(check_data_status('meridian', 'select', 'una hora'));
		status.push(check_data_status('subject', 'input', 'un asunto'));
		status.push(check_data_status('from_email', 'input', 'un remitente'));
		status.push(check_data_status('from_name', 'input', 'un remitente'));
		
		var create_content = true;
		for(var i = 0; i < status.length; i++) {
			if(status[i]) {
				create_content = false;
				$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: status[i], sticky: false, time: 2000});
			}
		}
		if(create_content) {
			var content = $('#birthday_form').serialize();
			var super_th = $(this);
			$.ajax({
				url: urlBase + 'campaign/birthday/' + idAutoresponder + '/onlyId',
				type: "POST",			
				data: content,
				error: function(msg){
					var obj = $.parseJSON(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.status, sticky: false, time: 10000});
					return false;
				},
				success: function(msg) {
					$(location).attr('href', super_th[0].href + '/' + msg.status);
				}
			});
		}
		return false;
	});
});

function check_data_status(name, type, msg)
{
	if($(type + "[name='" + name + "']").val().trim().length === 0) {
		var text = 'No ha ingresado ' + msg + ' para la autorespuesta, por favor verifique la información';
		return text;
	}
	
	return false;
}

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