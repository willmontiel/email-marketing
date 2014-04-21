
function FormEditor() {
	this.content = [];
	this.options = [];
	this.title = 'Formulario';
}

var formeditor = new FormEditor();

FormEditor.prototype.startEvents = function() {
	this.createZones();
	this.designDefaultFields();
	this.designCustomFields();
	this.sortableEvent();
};

FormEditor.prototype.createZones = function() {
	this.titlezone = $('<div class="form-title-name">' + this.title + '</div>');
	$('.form-full-content').append(this.titlezone);
	
	this.editorzone = $('<div class="form-content-zone"></div>');
	$('.form-full-content').append(this.editorzone);
	
	this.optionzone = $('<div class="form-fields-menu"></div>');
	$('.form-menu').append(this.optionzone);
};

FormEditor.prototype.designDefaultFields = function() {
	var email = new EmailBlock(this, 'email', 'Email');
	email.designOptionField();
	email.designField();
	
	var name = new TxtBlock(this, 'nombre', 'Nombre', 'Si');
	name.designOptionField();
	name.designField();
	
	var lastname = new TxtBlock(this, 'apellido', 'Apellido', 'Si');
	lastname.designOptionField();
	lastname.designField();
};

FormEditor.prototype.designCustomFields = function() {
	for(var i = 0; i < App.formfields.length; i++) {
		switch(App.formfields[i].type) {
			case 'Select':
				var field = new SlctBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required,  App.formfields[i].values);
				break;
			case 'MultiSelect':
				var field = new MultSlctBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required,  App.formfields[i].values);
				break;
			default:
				var field = new TxtBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required);
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
	$('.row.field-edit-zone-row').remove();
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
		if( field.id === 'email' ) {
			this.modifyEmailField(editzone);
		}

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
			else if( t.checkIfCanSave(editzone) ) {
				field.changeValues(editzone);
				$(this).parents('.row.field-edit-zone-row').remove();
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

FormEditor.prototype.modifyEmailField = function(editzone) {
	editzone.find('.field-required-option').closest('.row').remove();
	editzone.find('.field-hide-option').closest('.row').remove();
	editzone.find('.field-default-value').closest('.row').remove();
};

FormEditor.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option').length > 0 && editzone.find('.field-hide-option')[0].checked && editzone.find('.field-default-value').val() === '') {
		return false;
	}
	return true;
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
