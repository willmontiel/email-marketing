function FilterMailContent() {
	this.select = '';
	this.data = [];
}

FilterMailContent.prototype = new FilterContent;

FilterMailContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-select">\n\
						  <input style="width: 100%;" type="hidden" class="select2"/>\n\
					  </div>');
	
	this.container.append(this.content);
};

FilterMailContent.prototype.createSelect = function() {
	var self = this;
	this.loader.append('Un momento por favor... <div class="sgm-loading-image" style="float: right;"></div>');
	return $.Deferred(function(dfd){
		var DataSource = self.model.getDataSource();
		DataSource.find('/getmailfilter').then(function() { 
			var ds = DataSource.getData();
			var select2 = new Select2Object();
			select2.setData(ds);
			select2.setPlaceHolder("Seleccione un correo");
			select2.setSelectObject(self.content);
			select2.createSelectWithPreview();
			self.select = select2.getSelect2Object();
			self.loader.empty();
			dfd.resolve();
		});
	});
};

FilterMailContent.prototype.getSelect = function() {
	return this.select;
};