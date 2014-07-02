function DataSourceForSelect(url) {
	this.data = '';
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
}

DataSourceForSelect.prototype.getDataSource = function () {
	return this.data;
};