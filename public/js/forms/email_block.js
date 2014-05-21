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
	this.option = $('<div class="form-options ' + this.id + '-option">\n\
						' + this.name + '\n\
					</div>');
	this.zone.createFieldInOptions(this);
};

EmailBlock.prototype.designField = function() {
	var required = (this.required === 'Si') ? '<span class="required">*</span>' : '';
	var hide = ( this.hide ) ? 'form-field-hide-selected' : '';
	this.content= $('<div class="form-field form-field-' + this.id + '">\n\
						<div class="form-group field-content-zone ' + hide + '">\n\
							<label class="col-md-3 col-sm-2 col-xs-3 field-zone-name">\n\
								' + required + this.name + '\n\
							</label>\n\
							<div class="col-md-7 col-sm-8 col-xs-7">\n\
								<input type="text" class="form-control field-label-placeholder" placeholder="' + this.placeholder + '">\n\
							</div>\n\
							<div class="btn-group">\n\
								<div class="btn btn-default btn-sm edit-field">\n\
									<span class="glyphicon glyphicon-pencil"></span>\n\
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
	var edit = $('<div class="field-edit-zone-row">\n\
					<div class="col-md-10 col-md-offset-1 field-edit-zone">\n\
						<div class="form-group edit-row-in-zone">\n\
							<label class="col-md-4">Label</label><div class="col-md-8"><input type="text" class="form-control field-label-name" value="' + this.name + '"></div>\n\
						</div>\n\
						<div class="form-group edit-row-in-zone">\n\
							<label class="col-md-4">Placeholder</label><div class="col-md-8"><input type="text" class="form-control field-placeholder" value="' + this.placeholder + '"></div>\n\
						</div>\n\
						<div class="pull-right edit-button-row-in-zone">\n\
							<a class="accept-form-field-changes btn btn-default btn-guardar extra-padding btn-sm">Aceptar</a>\n\
						</div>\n\
					</div>\n\
				</div>\n\
				<div class="clearfix"></div>');
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