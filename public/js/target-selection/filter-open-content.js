function FilterOpenContent() {
	this.ds = [];
}

FilterOpenContent.prototype = new FilterContent;

FilterOpenContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-select">\n\
						  <input type="hidden" class="select2"/>\n\
					  </div>');
};

FilterOpenContent.prototype.initialize = function() {
	var self = this;
	var DataSource = this.model.getDataSource();

	DataSource.find('/getopenfilter').then(function() { 
		self.ds = DataSource.getData();
		self.initializeSelect2(self.ds);
	});
};

FilterOpenContent.prototype.initializeSelect2 = function(data) {
	var self = this;
	
	var select = this.content.find('.select2');
	
	select.select2({
		data: data,
		placeholder: "Selecciona una opción"
	});
	
	select.on("change", function(e) { 
		e.preventDefault();
		self.content.find('.sgm-add-filter-content').append('<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>');
		self.selectedValue = e.val;
	});
};



