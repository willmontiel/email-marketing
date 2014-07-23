function FilterMailContent() {
	this.select = '';
	this.data = [];
}

FilterMailContent.prototype = new FilterContent;

FilterMailContent.prototype.createContent = function() {
	var content = $('<div class="sgm-filter-select">\n\
						  <input style="width: 100%;" type="hidden" class="select2"/>\n\
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


function format(mail) {
//    if (!state.id) return state.text; // optgroup
	var img = '<div class="select2-content">\
				  <div class="select2-content-image">\n\
					  <img src="' + urlBase + 'mail/thumbnail/' + mail.id + '/40x40"/>\n\
				  </div>\n\
				  <div class="select2-content-text">\n\
					  <p>' + mail.text + '</p>\n\
			          <p>' + mail.subject + '</p>\n\
			          <p>' + mail.date + '</p>\
				  </div>\n\
               </div>';
    return img;
}

FilterMailContent.prototype.initializeSelect2 = function(data) {
	this.select = this.parent.find('.select2');
	
	this.select.select2({
		placeholder: "Selecciona una opción",
		data: data,
		formatResult: format,
		formatSelection: format,
		escapeMarkup: function(m) { return m; }
	});
	
	return this.select;
};

FilterMailContent.prototype.getSelect = function() {
	return this.select;
};