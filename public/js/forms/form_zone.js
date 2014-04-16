
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
	var email = new TxtBlock(this, 'email', 'Email', 'Si');
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
				break;
			case 'MultiSelect':
				break;
			default:
				var field = new TxtBlock(this, App.formfields[i].id, App.formfields[i].name, App.formfields[i].required);
//		Mientras no esten creados todas las opciones de campos SI SE utiliza esta forma
				field.designOptionField();
				break;
		}
//		Mientras no esten creados todas las opciones de campos NO SE utiliza esta forma
//		field.designOptionField();
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

		var editzone = this.getEditZone(field);
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
			if( t.checkIfCanSave(editzone) ) {
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

FormEditor.prototype.getEditZone = function(field) {
	var required = (field.required === 'Si') ? 'checked' : '';
	var hide = (field.hide) ? 'checked' : '';
	var defaultvalue = (!field.hide) ? 'hide-form-field' : '';
	var edit = $('<div class="row field-edit-zone-row">\n\
					<div class="col-md-8 col-md-offset-2 field-edit-zone">\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Label</div><div class="col-md-8"><input type="text" class="form-control field-label-name" value="' + field.name + '"></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Placeholder</div><div class="col-md-8"><input type="text" class="form-control field-placeholder" value="' + field.placeholder + '"></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Requerido</div><div class="col-md-8"><input type="checkbox" class="field-required-option" ' + required + '></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone">\n\
							<div class="col-md-4">Oculto</div><div class="col-md-8"><input type="checkbox" class="field-hide-option" ' + hide + '></div>\n\
						</div>\n\
						<div class="row edit-row-in-zone ' + defaultvalue + '">\n\
							<div class="col-md-4">Valor de campo</div><div class="col-md-8"><input type="text" class="form-control field-default-value" value="' + field.defaultvalue + '" placeholder="Valor campo oculto"></div>\n\
						</div>\n\
						<div class="row edit-button-row-in-zone">\n\
							<a class="accept-form-field-changes btn btn-default btn-sm">Aceptar</a>\n\
						</div>\n\
					</div>\n\
				</div>');
	return edit;
};

FormEditor.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option')[0].checked && editzone.find('.field-default-value').val() === '') {
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
