function createBlock(data, type){
	var block;
	var url;
	switch (type) {
		case 'header':
			url = blockHeaderUrl;
			block = {
				fromName: data.fromName.value,
				fromEmail: data.fromEmail.value,
				replyTo: data.replyTo.value,
				subject: data.subject.value
			};
			break;
			
		case 'target':
			url = blockTargetUrl;
			var j = data.segSelect;
			for (var i=0; i<j.length; i++) {
			}
			
			block = {
				dbases: data.dbSelect.value,
				contactlists: data.listSelect.value,
				segments: data.segSelect.value,
				sendByMail: data.sendByMail.value,
				sendByOpen: data.sendOpen.value,
				sendByClick: data.sendClick.value,
				excludeContact: data.sendExclude.value
			};
			break;
	}
	
//	saveBlock(block, url);
}

function saveBlock(data, url) {
	$.ajax({
		type: "POST",
		url: url,
		data: data,
		error: function(msg){
			$.gritter.add({title: 'Ha ocurrido un error', text: msg, sticky: false, time: 10000});
		},	
		success: function(msg){
			$.gritter.add({title: 'Se ha guardado exitosamente', text: msg, sticky: false, time: 10000});
		}
	});
}

function discardChanges(data) {
	
}