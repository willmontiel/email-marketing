function loadIndicator(space) {   
	var text = '';
	$.getJSON(MyBaseURL + 'account/loadindicator',function(data){ 
		if (data.accountingMode === 'Envio') {
			text = 'Correos enviados: ' + data.indicator + '' + (data.subscriptionMode === 'Prepago' ? '/' + data.messageLimit : '');
		}
		else {
			text = 'Contactos usados: ' + data.indicator + (data.subscriptionMode === 'Prepago' ? '/' + data.contactLimit : '');
		}

		$("#" + space).empty();
		$("#" + space).append(text);
	});
};
