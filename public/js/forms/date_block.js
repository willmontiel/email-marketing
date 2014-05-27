function DateBlock(zone, id, name, required) {
	this.zone = zone;
	this.id = id;
	this.name = name;
	this.required = required;
	this.defaultvalue = {day: '1', month: 'Enero', year: ''};
	this.hide = false;
	this.months = [{name:'Enero', value:'01'}, {name:'Febrero', value:'02'}, {name:'Marzo', value:'03'}, {name:'Abril', value:'04'}, {name:'Mayo', value:'05'}, {name:'Junio', value:'06'}, {name:'Julio', value:'07'}, {name:'Agosto', value:'08'}, {name:'Septiembre', value:'09'}, {name:'Octubre', value:'10'}, {name:'Noviembre', value:'11'}, {name:'Diciembre', value:'12'}];
}

DateBlock.prototype.designOptionField = function() {
	this.option = $('<div class="form-options ' + this.id + '-option">\n\
						' + this.name + '\n\
					</div>');
	this.zone.createFieldInOptions(this);
	
	var t = this;
	this.option.on('click', function() {
		t.designField();
	});
};

DateBlock.prototype.designField = function() {
	var days = '';
	var months = '';
	for (var i = 1; i <= 31; i++) {
		if(i < 10) {
			days+= '<option>0' + i + '</option>';
		}
		else {
			days+= '<option>' + i + '</option>';
		}
	}
	for (var i = 0; i < this.months.length; i++) {
		months+= '<option value="' + this.months[i].value + '">' + this.months[i].name + '</option>';
	}
	var required = (this.required === 'Si') ? '<span class="required">*</span>' : '';
	var hide = ( this.hide ) ? 'form-field-hide-selected' : '';
	this.content= $('<div class="form-field form-field-' + this.id + '">\n\
						<form class="field-content-zone form-inline mbottom-15' + hide + '" role="form">\n\
							<label class="col-md-3 col-sm-2 col-xs-3 field-zone-name width-la">\n\
								' + required + this.name + '\n\
							</label>\n\
							<div class="form-group">\n\
								<label class="sr-only"></label>\n\
								<div class="col-md-12 col-sm-12 col-xs-2">\n\
									<select class="form-control select-day-number-form">' + days + '</select>\n\
								</div>\n\
							</div>\n\
							<div class="form-group">\n\
								<label class="sr-only"></label>\n\
								<div class="col-md-12 col-sm-12 col-xs-4">\n\
									<select class="form-control select-month-number-form">' + months + '</select>\n\
								</div>\n\
							</div>\n\
							<div class="form-group">\n\
								<label class="sr-only"></label>\n\
								<div class="col-md-8 col-sm-9 col-xs-2">\n\
									<input type="text" class="form-control select-year-number-form">\n\
								</div>\n\
							</div>\n\
							<div class="form-group">\n\
								<div class="btn-group margin-top-left">\n\
									<a class="btn btn-default btn-sm edit-field">\n\
										<span class="glyphicon glyphicon-pencil"></span>\n\
									</a>\n\
									<a class="btn btn-default btn-sm delete-field">\n\
										<span class="glyphicon glyphicon-trash"></span>\n\
									</a>\n\
								</div>\n\
							</div>\n\
						</form>\n\
					</div>');
	this.zone.createFieldInZone(this);
	this.content.data('smobj', this);
	this.option.addClass('field-option-disabled').off('click');
	this.startFieldEvents();
};

DateBlock.prototype.startFieldEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editField(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.name = t.option.text().trim();
		t.defaultvalue = {day: '1', month: 'Enero', year: ''};
		t.hide = false;
		t.zone.deleteField(t);	
	});
};

DateBlock.prototype.changeValues = function(editzone) {
	this.name = editzone.find('.field-label-name').val();
	this.required = (editzone.find('.field-required-option')[0].checked) ? 'Si' : 'No';
	this.hide = editzone.find('.field-hide-option')[0].checked;
	this.defaultvalue.day = editzone.find('.select-day-number-form').val();
	this.defaultvalue.month = editzone.find('.select-month-number-form').val();
	this.defaultvalue.year = editzone.find('.select-year-number-form').val();
	
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

DateBlock.prototype.getEditZone = function() {
	var days = '';
	var months = '';
	for (var i = 1; i <= 31; i++) {
		var selected = (i == this.defaultvalue.day) ? 'selected' : '';
		if(i < 10) {
			days+= '<option ' + selected + '>0' + i + '</option>';
		}
		else {
			days+= '<option ' + selected + '>' + i + '</option>';
		}
	}
	for (var i = 0; i < this.months.length; i++) {
		var selected = (this.months[i] === this.defaultvalue.month) ? 'selected' : '';
		months+= '<option value="' + this.months[i].value + '" ' + selected + '>' + this.months[i].name + '</option>';
	}
	var required = (this.required === 'Si') ? 'checked' : '';
	var hide = (this.hide) ? 'checked' : '';
	var defaultvalue = (!this.hide) ? 'hide-form-field' : '';
	var edit = $('<div class="row field-edit-zone-row">\n\
					<div class="col-md-10 col-md-offset-1 field-edit-zone">\n\
						<div class="form-group edit-row-in-zone">\n\
							<div class="col-md-4">Label</div><div class="col-md-8"><input type="text" class="form-control field-label-name" value="' + this.name + '"></div>\n\
						</div>\n\
						<div class="form-group edit-row-in-zone">\n\
							<div class="col-md-4">Requerido</div><div class="col-md-8"><input type="checkbox" class="field-required-option" ' + required + '></div>\n\
						</div>\n\
						<div class="form-group edit-row-in-zone">\n\
							<div class="col-md-4">Oculto</div><div class="col-md-8"><input type="checkbox" class="field-hide-option" ' + hide + '></div>\n\
						</div>\n\
						<div class="form-group edit-row-in-zone ' + defaultvalue + '">\n\
							<div class="col-md-4">Valor de campo</div><div class="col-md-8">\n\
								<div class="row field-default-value">\n\
									<div class="col-md-3 col-without-padding">\n\
										<select class="form-control select-day-number-form">' + days + '</select>\n\
									</div>\n\
									<div class="col-md-5 col-without-padding">\n\
										<select class="form-control select-month-number-form">' + months + '</select>\n\
									</div>\n\
									<div class="col-md-3 col-without-padding">\n\
										<input type="text" class="form-control select-year-number-form" value="' + this.defaultvalue.year + '">\n\
									</div>\n\
								</div>\n\
							</div>\n\
						</div>\n\
						<div class="pull-right edit-button-row-in-zone">\n\
							<a class="accept-form-field-changes btn btn-default btn-guardar extra-padding btn-sm">Aceptar</a>\n\
						</div>\n\
					</div>\n\
				</div><div class="clearfix"></div>');
	return edit;
};

DateBlock.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option')[0].checked && editzone.find('.select-year-number-form').val() === '') {
		return false;
	}
	return true;
};

DateBlock.prototype.persist = function() {
	
	var obj = {
		id: this.id,
		name: this.name,
		required: this.required,
		defaultday: this.defaultvalue.day,
		defaultmonth: this.defaultvalue.month,
		defaultyear: this.defaultvalue.year,
		hide: this.hide,
		type: 'Date'
	};
	
	return obj;
};

DateBlock.prototype.unpersist = function(obj) {
	this.name = obj.name;
	this.required = obj.required;
	this.defaultvalue = {day: obj.defaultday, month: obj.defaultmonth, year: obj.defaultyear};
	this.hide = obj.hide;
};