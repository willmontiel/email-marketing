function DataSourceForSelect(url, data) {
	this.url = url;
	this.data = data;
}

DataSourceForSelect.prototype.findDataSource = function() {
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

DataSourceForSelect.prototype.getDataSource = function() {
	return this.dataSource;
};