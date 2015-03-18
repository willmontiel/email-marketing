function EmailBlock(zone, id, name) {
	this.zone = zone;
	this.id = id;
	this.name = name;
	this.placeholder = 'Escribe ' + name.toLowerCase();
	this.required = 'Si';
	this.defaultvalue = '';
	this.hide = false;
}

EmailBlock.prototype.designOptionField = function() {
	this.option = $('<div class="btn btn-default btn-sm extra-padding form-options ' + this.id + '-option">\n\
						' + this.name + '\n\
					</div>');
	this.zone.createFieldInOptions(this);
};

EmailBlock.prototype.designField = function() {
	var required = (this.required === 'Si') ? '<span class="required">*</span>' : '';
	var hide = ( this.hide ) ? 'form-field-hide-selected' : '';
	this.content= $('<div class="form-field form-field-' + this.id + '">\n\
						<div class="form-group field-content-zone ' + hide + '">\n\
							<label class="col-md-3 col-sm-2 col-xs-3 field-zone-name control-label">\n\
								' + required + this.name + ':\n\
							</label>\n\
							<div class="col-md-7 col-sm-8 col-xs-7">\n\
								<input type="text" class="form-control field-label-placeholder" placeholder="' + this.placeholder + '">\n\
							</div>\n\
							<div class="form-btns-opt">\n\
								<div class="form-tool edit-field">\n\
									<span class="glyphicon glyphicon-pencil"></span>\n\
								</div>\n\
								<div class="form-tool move-field">\n\
									<span class="glyphicon glyphicon-move"></span>\n\
								</div>\n\
							</div>\n\
						</div>\n\
					</div>');
	this.zone.createFieldInZone(this);
	this.content.data('smobj', this);
	this.option.addClass('field-option-disabled').off('click');
	this.startFieldEvents();
};

EmailBlock.prototype.startFieldEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editField(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.name = t.option.text().trim();
		t.placeholder = 'Escribe ' + t.name.toLowerCase();
		t.zone.deleteField(t);	
	});
};

EmailBlock.prototype.changeValues = function(editzone) {
	this.name = editzone.find('.field-label-name').val();
	this.placeholder = editzone.find('.field-placeholder').val();
		
	this.content.find('.field-zone-name').text(this.name);
	this.content.find('.field-label-placeholder').attr('placeholder', this.placeholder);
};

EmailBlock.prototype.getEditZone = function() {
	
	var extendedzone = (this.hide) ? 'form-edit-zone-extended' : '';
		
	var zone = new ZoneCreator();
	zone.designFieldEditZone(extendedzone);
	zone.designSaveBtn();
	zone.designNameField(this.name);
	zone.designPlaceholderField(this.placeholder);
	
	var edit = zone.getZone();
	
	return edit;
};

EmailBlock.prototype.checkIfCanSave = function(editzone) {
	return true;
};

EmailBlock.prototype.persist = function() {
	var obj = {
		id: this.id,
		name: this.name,
		placeholder: this.placeholder,
		required: this.required,
		defaultvalue: this.defaultvalue,
		hide: this.hide,
		type: 'Email'
	};
	
	return obj;
};

EmailBlock.prototype.unpersist = function(obj) {
	this.name = obj.name;
	this.placeholder = obj.placeholder;
	this.required = obj.required;
	this.defaultvalue = obj.defaultvalue;
	this.hide = obj.hide;
};