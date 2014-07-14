function DataSource(obj) {
	this.obj = obj;
}

DataSource.prototype.getUrl = function() {
	var url; 
	switch (this.obj[0].serialization.criteria) {
		case 'dbases':
			url = "/getdbases";
			break;
			
		case 'contactlists':
			url = "/getcontactlists";
			break;
			
		case 'segments':
			url = "/getsegments";
			break;
	}
	
	return url;
};

DataSource.prototype.find = function(urlPart) {
	if (urlPart === 'list') {
		var urlPart = this.getUrl();
	}
	
	this.url = urlBase + 'api' + urlPart;
	
	var self = this;
	this.dataSource = '';
	
	return $.Deferred(function(dfd){
        $.ajax({
			url: self.url,
			type: "POST",			
			data: {data: self.data},
			error: function(error){
				console.log('Error: ');
				console.log(error);
			},
			success: function(data){
				self.dataSource = data;
				dfd.resolve();
			}
		});
    });   
};

DataSource.prototype.getData = function() {
	return this.dataSource;
};