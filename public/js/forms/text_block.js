function TxtBlock(zone, id, name, required, area) {
	this.zone = zone;
	this.id = id;
	this.name = name;
	this.placeholder = 'Escribe ' + name.toLowerCase();
	this.required = required;
	this.defaultvalue = '';
	this.hide = false;
	this.area = area;
}

TxtBlock.prototype.designOptionField = function() {
	this.option = $('<div class="btn btn-default btn-sm extra-padding form-options ' + this.id + '-option">\n\
						' + this.name + '\n\
					</div>');
	this.zone.createFieldInOptions(this);
	
	var t = this;
	this.option.on('click', function() {
		t.designField();
	});
};

TxtBlock.prototype.designField = function() {
	var input = '<input type="text" class="form-control field-label-placeholder" placeholder="' + this.placeholder + '">';
	if ( this.area ) {
		input = '<textarea class="form-control field-label-placeholder" placeholder="' + this.placeholder + '"></textarea>';
	}
	var required = (this.required === 'Si') ? '<span class="required">*</span>' : '';
	var hide = ( this.hide ) ? 'form-field-hide-selected' : '';
	this.content= $('<div class="form-field form-field-' + this.id + '">\n\
						<div class="form-group field-content-zone ' + hide + '">\n\
							<label class="col-md-3 col-sm-2 col-xs-3 field-zone-name control-label">\n\
								' + required + this.name + ':\n\
							</label>\n\
							<div class="col-md-7 col-sm-8 col-xs-7">\n\
								' + input + '\n\
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

TxtBlock.prototype.startFieldEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editField(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.name = t.option.text().trim();
		t.placeholder = 'Escribe ' + t.name.toLowerCase();
		t.defaultvalue = '';
		t.hide = false;
		t.zone.deleteField(t);	
	});
};

TxtBlock.prototype.changeValues = function(editzone) {
	this.name = editzone.find('.field-label-name').val();
	this.placeholder = editzone.find('.field-placeholder').val();
	this.required = (editzone.find('.field-required-option')[0].checked) ? 'Si' : 'No';
	this.hide = editzone.find('.field-hide-option')[0].checked;
	this.defaultvalue = editzone.find('.field-default-value').val();
	
	this.content.find('.field-zone-name').text(this.name);
	this.content.find('.field-label-placeholder').attr('placeholder', this.placeholder);
	
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

TxtBlock.prototype.getEditZone = function() {
	var input = '<input type="text" class="form-control field-default-value" value="' + this.defaultvalue + '" placeholder="Valor campo oculto">';
	if ( this.area ) {
		input = '<textarea class="form-control field-default-value" placeholder="Valor campo oculto">' + this.defaultvalue + '</textarea>';
	}
	var required = (this.required === 'Si') ? 'checked' : '';
	var hide = (this.hide) ? 'checked' : '';
	var defaultvalue = (!this.hide) ? 'hide-form-field' : '';
	var extendedzone = (this.hide) ? 'form-edit-zone-extended' : '';
	
	var zone = new ZoneCreator();
	zone.designFieldEditZone(extendedzone);
	zone.designSaveBtn();
	zone.designNameField(this.name);
	zone.designPlaceholderField(this.placeholder);
	zone.designRequiredField(required);
	zone.designHideOptField(hide);
	zone.designHideTxtValueField(defaultvalue, input);
	
	var edit = zone.getZone();
	
	return edit;
};

TxtBlock.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option').length > 0 && editzone.find('.field-hide-option')[0].checked && editzone.find('.field-default-value').val() === '') {
		return false;
	}
	return true;
};

TxtBlock.prototype.persist = function() {
	var type = (this.area) ? 'TextArea' : 'Text';
	var obj = {
		id: this.id,
		name: this.name,
		placeholder: this.placeholder,
		required: this.required,
		defaultvalue: this.defaultvalue,
		hide: this.hide,
		area: this.area,
		type: type
	};
	
	return obj;
};

TxtBlock.prototype.unpersist = function(obj) {
	this.name = obj.name;
	this.placeholder = obj.placeholder;
	this.required = obj.required;
	this.defaultvalue = obj.defaultvalue;
	this.hide = obj.hide;
	this.area = obj.area;
};