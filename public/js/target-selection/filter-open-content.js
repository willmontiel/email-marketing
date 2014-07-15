function FilterOpenContent() {
	this.select = '';
}

FilterOpenContent.prototype = new FilterContent;

FilterOpenContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-select">\n\
						  <input type="hidden" class="select2"/>\n\
					  </div>');
};

FilterOpenContent.prototype.createSelect = function() {
	var self = this;
	var DataSource = this.model.getDataSource();
	
	return $.Deferred(function(dfd){
		DataSource.find('/getopenfilter').then(function() { 
			var ds = DataSource.getData();
			self.select = self.initializeSelect2(ds);
			dfd.resolve();
		});
	});
};

FilterOpenContent.prototype.initializeSelect2 = function(data) {
	var select = this.content.find('.select2');
	
	select.select2({
		data: data,
		placeholder: "Selecciona una opción"
	});
	
	return select;
};

FilterOpenContent.prototype.getSelect = function() {
	return this.select;
};