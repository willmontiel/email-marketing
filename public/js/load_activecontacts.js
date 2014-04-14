function loadNow() {   
		$.getJSON(MyBaseURL + 'account/loadcontactsinfo',function(data){ 
			if (data.accountingMode === 'Contacto') {
				$("#contactsInfo").empty();
				$('#contactsInfo').append(data.activeContacts +'/'+data.contactLimit);
			}
			else {
				$("#contactsInfo").empty();
				$('#contactsInfo').append(data.activeContacts);
			}
		});
};
		
$(function() {
	loadNow();
	var autoRefresh = setInterval(loadNow, 6000000);
});