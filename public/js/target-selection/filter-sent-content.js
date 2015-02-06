function FilterSentContent() {
	this.select = '';
}

FilterSentContent.prototype = new FilterContent;

FilterSentContent.prototype.createContent = function() {
	var content = $('<div class="sgm-filter-select">\n\
						  <input type="hidden" class="select2"/>\n\
					  </div>');
	
	this.parent.find('.sgm-filter-content-body').append(content);
};

FilterSentContent.prototype.createSelect = function() {
	var self = this;
	this.loader.append('Un momento por favor... <div class="sgm-loading-image" style="float: right;"></div>');
	return $.Deferred(function(dfd){
		var DataSource = self.model.getDataSource();
		DataSource.find('/getmailfilter').then(function() { 
			var ds = DataSource.getData();
			self.initializeSelect2(ds);
			self.loader.empty();
			dfd.resolve();
		});
	});
};

FilterSentContent.prototype.initializeSelect2 = function(data) {
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

FilterSentContent.prototype.getSelect = function() {
	return this.select;
};