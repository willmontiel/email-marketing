function FilterClickContent() {
	this.select = '';
}

FilterClickContent.prototype = new FilterContent;

FilterClickContent.prototype.createContent = function() {
	this.content = $('<div class="sgm-filter-select">\n\
						  <input style="width: 100%;" type="hidden" class="select2"/>\n\
					  </div>');
	
	this.container.append(this.content);
};

FilterClickContent.prototype.createSelectForMails = function() {
	var self = this;
	this.loader.append('Un momento por favor... <div class="sgm-loading-image" style="float: right;"></div>');
	return $.Deferred(function(dfd){
		var DataSource = self.model.getDataSource();
		DataSource.find('/getclicksmailfilter').then(function() { 
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

FilterClickContent.prototype.createSelect = function() {
	var self = this;
	this.loader.append('Un momento por favor... <div class="sgm-loading-image" style="float: right;"></div>');
	
	return $.Deferred(function(dfd){
		var obj = {idMail: self.idMail};	
		var DataSource = self.model.getDataSource();
		DataSource.setObject(obj);
		DataSource.find('/getclicksfilter').then(function() { 
			var ds = DataSource.getData();
			var select2 = new Select2Object();
			select2.setData(ds);
			select2.setPlaceHolder("Seleccione un link");
			select2.setSelectObject(self.content);
			select2.createBasicSelect();
			self.select = select2.getSelect2Object();
			self.loader.empty();
			dfd.resolve();
		});
	});
};

FilterClickContent.prototype.getSelect = function() {
	return this.select;
};