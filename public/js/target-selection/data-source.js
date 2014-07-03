function DataSourceForSelect(url) {
	this.url = url;
}

DataSourceForSelect.prototype.findDataSource = function() {
	console.log('Processing...');
	var self = this;
	this.dataSource = '';
	
	return $.Deferred(function(dfd){
        $.ajax({
			url: self.url,
			type: "POST",			
			data: {},
			error: function(error){
				console.log('Error');
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