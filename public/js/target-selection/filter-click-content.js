function FilterClickContent() {
	this.select = '';
}

FilterClickContent.prototype = new FilterContent;

FilterClickContent.prototype.createContent = function() {
	var content = $('<div class="sgm-filter-select">\n\
						  <input style="width: 100%;" type="hidden" class="select2"/>\n\
					  </div>');
	
	this.parent.find('.sgm-filter-content-body').append(content);
};

FilterClickContent.prototype.createSelect = function() {
	var self = this;
	return $.Deferred(function(dfd){
		var obj = {idMail: self.idMail};
		
		var DataSource = self.model.getDataSource();
		DataSource.setObject(obj);
		DataSource.find('/getclicksfilter').then(function() { 
			var ds = DataSource.getData();
			self.initializeSelect2(ds);
			dfd.resolve();
		});
	});
};

FilterClickContent.prototype.initializeSelect2 = function(data) {
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

FilterClickContent.prototype.getSelect = function() {
	return this.select;
};