function DataSourceForSelect(url) {
	this.url = url;
}

DataSourceForSelect.prototype.findDataSource = function() {
	var self = this;
	this.dataSource = '';
	
	return $.Deferred(function(dfd){
        $.ajax({
			url: self.url,
			type: "POST",			
			data: {},
			error: function(error){
				console.log('Error: ' + error);
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