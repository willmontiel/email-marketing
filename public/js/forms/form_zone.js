
function FormEditor(type) {
	this.type = type;
	this.content = [];
	this.options = [];
}

FormEditor.prototype.startEvents = function(obj) {
	this.createZones();
	this.designCustomFields();
	this.sortableEvent();
	
	this.header_zone();
	this.button_zone();
	this.adv_tools_zone();
	
	if(obj === null) {
		this.designDefaultFields();
	}
	else {
		this.unpersist(obj);
	}
	this.addFieldsBetweenZones();
};

FormEditor.prototype.addFieldsBetweenZones = function() {
	this.header.desingOptionZone(this.adv_tools);
};

FormEditor.prototype.createZones = function() {
	this.editorzone = $('<div class="form-content-zone form-size-one"></div>');
	$('.form-full-content').append(this.editorzone);
	
	this.optionzone = $('<div class="form-fields-menu"></div>');
	$('.form-menu').append(this.optionzone);
};

FormEditor.prototype.designDefaultFields = function() {	
	for(var i = 0; i < this.options.length; i++) {
		if( this.options[i].id === 'email' ||  this.options[i].id === 'name' ||  this.options[i].id === 'lastName' ||  this.options[i].id === 'birthDate') {
			this.options[i].designField();
		}
	}
	this.header.designHeaderZone();
	this.button.designButtonField();
	this.adv_tools.designZone();
};

FormEditor.prototype.designCustomFields = function() {
	var formtype = false;
	if(this.type === 'Updating'){
		formtype = true;
	}
	
	var email = new EmailBlock(this, 'email', 'Email', formtype);
	email.designOptionField();
	
	var name = new TxtBlock(this, 'name', 'Nombre', 'Si', false);
	name.designOptionField();
	
	var lastname = new TxtBlock(this, 'lastName', 'Apellido', 'Si', false);
	lastname.designOptionField();
	
	var birthdate = new DateBlock(this, 'birthDate', 'Fecha de nacimiento', 'Si', false);
	birthdate.designOptionField();
	
	for(var i = 0; i < App.formfields.length; i++) {
		switch(App.formfields[i].type) {
			case 'Select':
				var field = new SlctBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required,  App.formfields[i].values);
				break;
			case 'MultiSelect':
				var field = new MultSlctBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required,  App.formfields[i].values);
				break;
			case 'TextArea':
				var field = new TxtBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required, true);
				break;
			case 'Date':
				var field = new DateBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required, true);
				break;
			default:
				var field = new TxtBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required, false);
				break;
		}
		field.designOptionField();
	}
};

FormEditor.prototype.createFieldInZone = function(field) {
	this.editorzone.append(field.content);
	this.content.push(field);
};

FormEditor.prototype.createFieldInOptions = function(field) {
	this.optionzone.append(field.option);
	this.options.push(field);
};

FormEditor.prototype.editField = function(field) {	
	$('.field-edit-zone-row').remove();
	$('.accept-form-field-changes').off('click');
	
	if(field.content.find('.form-field-editing').length > 0) {
		field.content.find('.field-edit-zone-row').remove();
		$('.form-field-editing').removeClass('form-field-editing');
		this.editorzone.sortable({ disabled: false });
	}
	else {
		$('.form-field-editing').removeClass('form-field-editing');
		field.content.find('.edit-field').addClass('form-field-editing');		
	
		this.editorzone.sortable({ disabled: true });

		var editzone = field.getEditZone();
		this.editorzone.find(field.content).append(editzone);

		editzone.find('.field-hide-option').on('click', function() {
			if($(this)[0].checked) {
				editzone.find('.field-default-value').closest('.edit-row-in-zone').removeClass('hide-form-field');
				editzone.addClass('form-edit-zone-extended');
			}
			else {
				editzone.find('.field-default-value').closest('.edit-row-in-zone').addClass('hide-form-field');
				editzone.removeClass('form-edit-zone-extended');
			}
		});
		
		editzone.find('.field-required-option').on('click', function() {
			if($(this)[0].checked) {
				editzone.find('.form-opt-field-container .required-form-btn').addClass('checked');
			}
			else {
				editzone.find('.form-opt-field-container .required-form-btn').removeClass('checked');
			}
		});
		
		var t = this;
		
		editzone.find('.accept-form-field-changes').on('click', function() {
			if(editzone.find('.field-label-name').val() === '') {
				$.gritter.add({title: 'Error', text: 'El nombre del label no puede estar vacio', sticky: false, time: 2000});
			}
			else if( field.checkIfCanSave(editzone) ) {
				field.changeValues(editzone);
				$(this).parents('.field-edit-zone-row').remove();
				$(this).off('click');
				$('.form-field-editing').removeClass('form-field-editing');
				t.editorzone.sortable({ disabled: false });
			}
			else {
				$.gritter.add({title: 'Error', text: 'El valor del campo no puede estar vacio', sticky: false, time: 2000});
			}
		});
	}
};

FormEditor.prototype.editZone = function(zone) {	
	$('.field-edit-zone-row').remove();
	$('.accept-form-field-changes').off('click');
	
	if(zone.content.find('.form-field-editing').length > 0) {
		zone.content.find('.field-edit-zone-row').remove();
		$('.form-field-editing').removeClass('form-field-editing');
		this.editorzone.sortable({ disabled: false });
	}
	else {
		$('.form-field-editing').removeClass('form-field-editing');
		zone.content.find('.edit-field').addClass('form-field-editing');		
	
		this.editorzone.sortable({ disabled: true });

		var editzone = zone.getEditZone();
		zone.content.append(editzone);

		var t = this;
		
		editzone.find('.accept-form-field-changes').on('click', function() {
			zone.changeValues(editzone);
			$(this).parents('.field-edit-zone-row').remove();
			$(this).off('click');
			$('.form-field-editing').removeClass('form-field-editing');
			t.editorzone.sortable({ disabled: false });
		});
	}
};

FormEditor.prototype.deleteField = function(field) {
	this.editorzone.find('.form-field-' + field.id).remove();
	
	field.option.removeClass('field-option-disabled');
	
	field.option.on('click', function() {
		field.designField();
	});
	
	for(var i = 0; i < this.content.length; i++) {
		if(this.content[i] === field) {
			this.content.splice(i, 1);
		}
	}
};

FormEditor.prototype.sortableEvent = function() {
	var t = this;
	
	this.editorzone.sortable({
		items: 'div.form-field',
		stop: function(event, object) {
			var objrow = object.item.data('smobj');
			for(var i = 0; i < t.content.length; i++) {
				if(t.content[i] === objrow) {
					t.content.splice(i, 1);
				}
			}
			t.content.splice(object.item.index(), 0, objrow);
		}
	});
};

FormEditor.prototype.header_zone = function() {
	this.header = new ZoneHeader(this);
};

FormEditor.prototype.adv_tools_zone = function() {
	this.adv_tools = new ToolsZone(this, this.header, this.button);
};

FormEditor.prototype.button_zone = function() {
	this.button = new ButtonBlock(this);
};

FormEditor.prototype.updateStyle = function(property, value) {
	this.editorzone.css(property, value);
};

FormEditor.prototype.persist = function() {
	var obj = {
		content : [],
		header_zone : this.header.persist(),
		button_zone : this.button.persist(),
		properties_zone : this.adv_tools.persist()
	};
	
	for(var i = 0; i < this.content.length; i++) {
		obj.content.push(this.content[i].persist());
	}
	
	return obj;
};

FormEditor.prototype.unpersist = function(obj) {
	var jsonobj = JSON.parse(obj);
	
	if(jsonobj.header_zone !== undefined){
		this.header.unpersist(jsonobj.header_zone);
	}
	this.header.designHeaderZone();
	
	if(jsonobj.button_zone !== undefined){
		this.button.unpersist(jsonobj.button_zone);
	}
	this.button.designButtonField();
	
	if(jsonobj.properties_zone !== undefined){
		this.adv_tools.unpersist(jsonobj.properties_zone);
	}
	this.adv_tools.designZone();
	this.adv_tools.applyChanges();
			
	var content = jsonobj.content;
	for(var i = 0; i < content.length; i++) {
		for(var j = 0; j < this.options.length; j++) {
			if(content[i].id === this.options[j].id) {
				this.options[j].unpersist(content[i]);
				this.options[j].designField();
			}
		}
	}
};