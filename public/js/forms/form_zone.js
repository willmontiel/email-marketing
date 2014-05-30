
function FormEditor() {
	this.content = [];
	this.options = [];
	this.title = 'Nombre del formulario';
	this.button = 'Aceptar';
}

FormEditor.prototype.startEvents = function(obj) {
	this.createZones();
	this.designCustomFields();
	this.sortableEvent();
	this.title_xeditable();
	this.button_xeditable();
	if(obj === null) {
		this.designDefaultFields();
	}
	else {
		this.unpersist(obj);
	}
};

FormEditor.prototype.createZones = function() {
	this.titlezone = $('<div class="container-form-title-name"><h4 class="sectiontitle"><a href="#" class="editable editable-click" id="form-title-name" data-type="text" data-pk="1" data-original-title="Título del formulario">' + this.title + '</a></h4></div>');
	$('.form-full-content').append(this.titlezone);
	
	this.editorzone = $('<div class="form-content-zone"></div>');
	$('.form-full-content').append(this.editorzone);
	
	this.optionzone = $('<div class="form-fields-menu"></div>');
	$('.form-menu').append(this.optionzone);
	
	this.buttonzone = $('<label class="col-md-3 col-sm-2 col-xs-3 field-zone-name control-label">Botón:</label><div class="col-md-7 col-sm-8 col-xs-7"><div class="container-form-button-name btn btn-guardar extra-padding btn-sm"><a href="#" class="editable editable-click" id="form-button-name" data-type="text" data-pk="1" data-original-title="Nombre del Boton">' + this.button + '</a></div></div>');
	$('.form-full-button').append(this.buttonzone);
};

FormEditor.prototype.designDefaultFields = function() {	
	for(var i = 0; i < this.options.length; i++) {
		if( this.options[i].id === 'email' ||  this.options[i].id === 'name' ||  this.options[i].id === 'lastName' ||  this.options[i].id === 'birthDate') {
			this.options[i].designField();
		}
	}
};

FormEditor.prototype.designCustomFields = function() {
	var email = new EmailBlock(this, 'email', 'Email', false);
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
			}
			else {
				editzone.find('.field-default-value').closest('.edit-row-in-zone').addClass('hide-form-field');
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

FormEditor.prototype.title_xeditable = function() {
	var t = this;
	this.titlezone.find('#form-title-name').editable({ 
		type: 'text', 
		success: function (resp, newValue) { 
			t.title = newValue;
		} 
	});
};

FormEditor.prototype.button_xeditable = function() {
	var t = this;
	this.buttonzone.find('#form-button-name').editable({ 
		type: 'text', 
		success: function (resp, newValue) { 
			t.button = newValue;
		} 
	});
};

FormEditor.prototype.modifyTitleAndButton = function() {
	$('#form-title-name').text(this.title);
	$('#form-button-name').text(this.button);
};

FormEditor.prototype.persist = function() {
	var obj = {title: this.title, button: this.button, content : []};
	
	for(var i = 0; i < this.content.length; i++) {
		obj.content.push(this.content[i].persist());
	}
	return obj;
};

FormEditor.prototype.unpersist = function(obj) {
	var jsonobj = JSON.parse(obj);
	var content = jsonobj.content;
	this.title = jsonobj.title;
	this.button = jsonobj.button;
	this.modifyTitleAndButton();
	for(var i = 0; i < content.length; i++) {
		for(var j = 0; j < this.options.length; j++) {
			if(content[i].id === this.options[j].id) {
				this.options[j].unpersist(content[i]);
				this.options[j].designField();
			}
		}
	}
};