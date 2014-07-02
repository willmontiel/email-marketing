function Target () {}

Target.prototype.SearchTarget = function (criteria) {
	var url;
	
	switch (criteria) {
		case 'dbases':
			url = "{{url('mail/confirmmail')}}/";
			break;
			
		case 'lists':
			url = "{{url('mail/confirmmail')}}/";
			break;
			
		case 'segments':
			url = "{{url('mail/confirmmail')}}/";
			break;
	}
	
	$.ajax({
		url: url,
		type: "POST",			
		data: {},
		error: function(error){
			console.log('Error');
			console.log(error);
		},
		success: function(data){
			this.data = data;
		}
	});
};

Target.prototype.GetTarget = function () {
	return this.data;
};