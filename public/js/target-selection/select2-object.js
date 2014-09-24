function Select2Object() {
	this.data = [];
}

Select2Object.prototype.setSelectObject = function(select) {
	this.select = select;
};

Select2Object.prototype.setPlaceHolder = function(placeholder) {
	this.placeholder = placeholder;
};

Select2Object.prototype.setData = function(data) {
	this.data = data;
};


function formatDataForSelet2(mail) {
//    if (!state.id) return state.text; // optgroup
	var img = '<div class="select2-content">\
					<p><img style="display:block; float:left;" src="' + urlBase + 'mail/thumbnail/' + mail.id + '/50x50"/>\n\
					  <strong>' + mail.text + '</strong><br/>\n\
			          <small><strong>Asunto: </strong>' + mail.subject + '</small><br/>\n\
			          <small><strong>Fecha: </strong>' + mail.date + '</small>\
					</p>\n\
               </div>';
    return img;
}

function formatValueSelected(mail) {
	return '<strong>' + mail.text + '</strong> (' + mail.date + ')';
}

Select2Object.prototype.createSelectWithPreview = function() {
	var self = this;
	
	this.select.select2({
		placeholder: self.placeholder,
		data: self.data,
		formatResult: formatDataForSelet2,
		formatSelection: formatValueSelected,
		escapeMarkup: function(m) { return m; }
	});
};

Select2Object.prototype.createBasicSelect = function() {
	var self = this;
	
	this.select.select2({
		data: self.data,
		placeholder: self.placeholder
	});
};

Select2Object.prototype.createOptionGroupSelect = function() {
	var self = this;
	
	var results = {
		more: false,
		results: self.data
	};
	
	this.select.select2({
		data: results,
		placeholder: self.placeholder
	});
};

Select2Object.prototype.getSelect2Object = function() {
	return this.select;
};