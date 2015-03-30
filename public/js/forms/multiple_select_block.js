function MultSlctBlock(zone, id, name, required, values) {
	this.zone = zone;
	this.id = id;
	this.name = name;
	this.required = required;
	this.defaultvalue = '';
	this.hide = false;
	this.values = values;
}

MultSlctBlock.prototype.designOptionField = function() {
	this.option = $('<div class="btn btn-default btn-sm extra-padding form-options ' + this.id + '-option">\n\
						' + this.name + '\n\
					</div>');
	this.zone.createFieldInOptions(this);
	
	var t = this;
	this.option.on('click', function() {
		t.designField();
	});
};

MultSlctBlock.prototype.designField = function() {
	var slctoptions = '';
	var valuesarray = this.values.split(",");
	for(var i = 0; i < valuesarray.length; i++) {
		slctoptions+= '<option>' + valuesarray[i] + '</option>';
	}
	var required = (this.required === 'Si') ? '<span class="required">*</span>' : '';
	var hide = ( this.hide ) ? 'form-field-hide-selected' : '';
	this.content= $('<div class="form-field form-field-' + this.id + '">\n\
						<div class="form-group field-content-zone ' + hide + '">\n\
							<label class="pull-left field-zone-name control-label">\n\
								' + required + this.name + ':\n\
							</label>\n\
							<div class="pull-left input-form-zone">\n\
								<select class="form-control field-label-select-options" multiple="true">\n\
									' + slctoptions + '\n\
								</select>\n\
							</div>\n\
							<div class="form-btns-opt">\n\
								<div class="form-tool edit-field">\n\
									<span class="glyphicon glyphicon-pencil"></span>\n\
								</div>\n\
								<div class="form-tool move-field">\n\
									<span class="glyphicon glyphicon-move"></span>\n\
								</div>\n\
								<div class="form-tool delete-field">\n\
									<span class="glyphicon glyphicon-trash"></span>\n\
								</div>\n\
							</div>\n\
						</div>\n\
					</div>');
	this.zone.createFieldInZone(this);
	this.content.data('smobj', this);
	this.option.addClass('field-option-disabled').off('click');
	this.startFieldEvents();
};

MultSlctBlock.prototype.startFieldEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editField(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.name = t.option.text().trim();
		t.defaultvalue = '';
		t.hide = false;
		t.zone.deleteField(t);	
	});
};

MultSlctBlock.prototype.changeValues = function(editzone) {
	this.name = editzone.find('.field-label-name').val();
	this.required = (editzone.find('.field-required-option')[0].checked) ? 'Si' : 'No';
	this.hide = editzone.find('.field-hide-option')[0].checked;
	this.defaultvalue = (editzone.find('.field-default-value').val() != null) ? editzone.find('.field-default-value').val().join() : '';
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

MultSlctBlock.prototype.getEditZone = function() {
	var slctoptions = '';
	var valuesarray = this.values.split(",");
	for(var i = 0; i < valuesarray.length; i++) {
		var selected = (this.defaultvalue.split(",").indexOf(valuesarray[i]) >= 0 ) ? 'selected' : '';
		slctoptions+= '<option ' + selected + '>' + valuesarray[i] + '</option>';
	}
	var required = (this.required === 'Si') ? 'checked' : '';
	var hide = (this.hide) ? 'checked' : '';
	var defaultvalue = (!this.hide) ? 'hide-form-field' : '';
	var extendedzone = (this.hide) ? 'form-edit-zone-extended' : '';

	var zone = new ZoneCreator();
	zone.designFieldEditZone(extendedzone);
	zone.designSaveBtn();
	zone.designNameField(this.name);
	zone.designRequiredField(required);
	zone.designHideOptField(hide);
	zone.designHideSelectValueField(defaultvalue, slctoptions, 'multiple="true"');
	
	var edit = zone.getZone();
	
	return edit;
};

MultSlctBlock.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option').length > 0 && editzone.find('.field-hide-option')[0].checked && editzone.find('.field-default-value').val() === '') {
		return false;
	}
	return true;
};

MultSlctBlock.prototype.persist = function() {
	var obj = {
		id: this.id,
		name: this.name,
		placeholder: this.placeholder,
		required: this.required,
		defaultvalue: this.defaultvalue,
		hide: this.hide,
		values: this.values,
		type: 'MultiSelect'
	};
	
	return obj;
};

MultSlctBlock.prototype.unpersist = function(obj) {
	this.name = obj.name;
	this.required = obj.required;
	this.defaultvalue = obj.defaultvalue;
	this.hide = obj.hide;
	this.values = obj.values;
};