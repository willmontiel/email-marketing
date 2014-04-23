function SlctBlock(zone, id, name, required, values) {
	this.zone = zone;
	this.id = id;
	this.name = name;
	this.required = required;
	this.defaultvalue = '';
	this.hide = false;
	this.values = values;
}

SlctBlock.prototype.designOptionField = function() {
	this.option = $('<div class="form-options ' + this.id + '-option">\n\
						' + this.name + '\n\
					</div>');
	this.zone.createFieldInOptions(this);
	
	var t = this;
	this.option.on('click', function() {
		t.name = t.option.text().trim();
		t.defaultvalue = '';
		t.hide = false;
		t.designField();
	});
};

SlctBlock.prototype.designField = function() {
	var slctoptions = '';
	var valuesarray = this.values.split(",");
	for(var i = 0; i < valuesarray.length; i++) {
		slctoptions+= '<option>' + valuesarray[i] + '</option>';
	}
	var required = (this.required === 'Si') ? '<span class="required">*</span>' : '';
	var hide = ( this.hide ) ? 'form-field-hide-selected' : '';
	this.content= $('<div class="form-field form-field-' + this.id + '">\n\
						<div class="row field-content-zone ' + hide + '">\n\
							<div class="col-md-3 field-zone-name">\n\
								' + required + this.name + '\n\
							</div>\n\
							<div class="col-md-7">\n\
								<select class="form-control field-label-select-options">\n\
									' + slctoptions + '\n\
								</select>\n\
							</div>\n\
							<div class="col-md-1 btn btn-default btn-sm edit-field">\n\
								<span class="glyphicon glyphicon-pencil"></span>\n\
							</div>\n\
							<div class="col-md-1 btn btn-default btn-sm delete-field">\n\
								<span class="glyphicon glyphicon-trash"></span>\n\
							</div>\n\
						</div>\n\
					</div>');
	this.zone.createFieldInZone(this);
	this.content.data('smobj', this);
	this.option.addClass('field-option-disabled').off('click');
	this.startFieldEvents();
};

SlctBlock.prototype.startFieldEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editField(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.zone.deleteField(t);	
	});
};

SlctBlock.prototype.changeValues = function(editzone) {
	this.name = editzone.find('.field-label-name').val();
	this.required = (editzone.find('.field-required-option')[0].checked) ? 'Si' : 'No';
	this.hide = editzone.find('.field-hide-option')[0].checked;
	this.defaultvalue = editzone.find('.field-default-value').val();
	
	this.content.find('.field-zone-name').text(this.name);
	
	if( this.required === 'Si' ) {
		var required = $('<span class="required">*</span>');
		this.content.find('.field-zone-name').prepend(required);
	}
	
	if( this.hide ) {
		this.content.find('.field-content-zone').addClass('form-field-hide-selected');
	}
	else {
		this.content.find('.field-content-zone').removeClass('form-field-hide-selected');
	}
};

SlctBlock.prototype.getEditZone = function() {
	var slctoptions = '';
	var valuesarray = this.values.split(",");
	for(var i = 0; i < valuesarray.length; i++) {
		var selected = (valuesarray[i] === this.defaultvalue) ? 'selected' : '' ;
		slctoptions+= '<option ' + selected + '>' + valuesarray[i] + '</option>';
	}
	var required = (this.required === 'Si') ? 'checked' : '';
	var hide = (this.hide) ? 'checked' : '';
	var defaultvalue = (!this.hide) ? 'hide-form-field' : '';
	var edit = $('<div class="row field-edit-zone-row">\n\
					<div class="col-md-10 col-md-offset-1 field-edit-zone">\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Label</div><div class="col-md-8"><input type="text" class="form-control field-label-name" value="' + this.name + '"></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Requerido</div><div class="col-md-8"><input type="checkbox" class="field-required-option" ' + required + '></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Oculto</div><div class="col-md-8"><input type="checkbox" class="field-hide-option" ' + hide + '></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone ' + defaultvalue + '">\n\
							<div class="col-md-4">Valor de campo</div><div class="col-md-8"><select class="form-control field-default-value">' + slctoptions + '</select></div>\n\
						</div>\n\
						<div class="row edit-button-row-in-zone">\n\
							<a class="accept-form-field-changes btn btn-default btn-sm">Aceptar</a>\n\
						</div>\n\
					</div>\n\
				</div>');
	return edit;
};

SlctBlock.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option').length > 0 && editzone.find('.field-hide-option')[0].checked && editzone.find('.field-default-value').val() === '') {
		return false;
	}
	return true;
};

SlctBlock.prototype.persist = function() {
	var obj = {
		id: this.id,
		name: this.name,
		placeholder: this.placeholder,
		required: this.required,
		defaultvalue: this.defaultvalue,
		hide: this.hide,
		values: this.values,
		type: 'Select'
	};
	
	return obj;
};

SlctBlock.prototype.unpersist = function(obj) {
	this.name = obj.name;
	this.required = obj.required;
	this.defaultvalue = obj.defaultvalue;
	this.hide = obj.hide;
	this.values = obj.values;
};