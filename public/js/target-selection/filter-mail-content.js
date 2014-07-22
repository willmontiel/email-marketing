function FilterMailContent() {
	this.select = '';
}

FilterMailContent.prototype = new FilterContent;

FilterMailContent.prototype.createContent = function() {
	var content = $('<div class="sgm-filter-select">\n\
						  <input type="hidden" class="select2"/>\n\
					  </div>');
	
	this.parent.find('.sgm-filter-content-body').append(content);
};

FilterMailContent.prototype.createSelect = function() {
	var self = this;
	return $.Deferred(function(dfd){
		var DataSource = self.model.getDataSource();
		DataSource.find('/getmailfilter').then(function() { 
			var ds = DataSource.getData();
			self.initializeSelect2(ds);
			dfd.resolve();
		});
	});
};

FilterMailContent.prototype.initializeSelect2 = function(data) {
	var results = {
		more: false,
		results: data
	};
	
	this.select = this.parent.find('.select2');
	
	this.select.select2({
		data: results,
		placeholder: "Selecciona una opción"
	});
	
	return this.select;
};

FilterMailContent.prototype.getSelect = function() {
	return this.select;
};