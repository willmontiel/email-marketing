function FilterOpenContent() {
	this.ds = [];
}

FilterOpenContent.prototype = new FilterContent;

FilterOpenContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-select">\n\
						  <input type="hidden" class="select2"/>\n\
					  </div>');
};

FilterOpenContent.prototype.getContent = function() {
	var self = this;
	var DataSource = this.model.getDataSource();
	
	var select;
	DataSource.find('/getopenfilter').then(function() { 
		self.ds = DataSource.getData();
		select = self.initializeSelect2(self.ds);
	});
	
	return select;
};

FilterOpenContent.prototype.initializeSelect2 = function(data) {
	var select = this.content.find('.select2');
	
	select.select2({
		data: data,
		placeholder: "Selecciona una opción"
	});
	return select;
};



