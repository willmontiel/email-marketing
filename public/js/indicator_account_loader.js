function loadIndicator(space) {   
	var text = '';
	$.getJSON(MyBaseURL + 'account/loadindicator',function(data){ 
		if (data.accountingMode === 'Envio') {
			text = 'Correos enviados: ' + data.indicator + '' + (data.subscriptionMode === 'Prepago' ? '/' + data.messageLimit : '');
		}
		else {
			text = 'Contactos disponibles: ' + data.indicator + (data.subscriptionMode === 'Prepago' ? '/' + data.contactLimitmessageLimit : '');
		}

		$("#" + space).empty();
		$("#" + space).append(text);
	});
};
