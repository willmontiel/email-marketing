function DateBlock(zone, id, name, required) {
	this.zone = zone;
	this.id = id;
	this.name = name;
	this.required = required;
	this.defaultvalue = {day: '1', month: '1', year: ''};
	this.hide = false;
	this.months = [{name:'Enero', value:'01'}, {name:'Febrero', value:'02'}, {name:'Marzo', value:'03'}, {name:'Abril', value:'04'}, {name:'Mayo', value:'05'}, {name:'Junio', value:'06'}, {name:'Julio', value:'07'}, {name:'Agosto', value:'08'}, {name:'Septiembre', value:'09'}, {name:'Octubre', value:'10'}, {name:'Noviembre', value:'11'}, {name:'Diciembre', value:'12'}];
}

DateBlock.prototype.designOptionField = function() {
	this.option = $('<div class="btn btn-default btn-sm extra-padding form-options ' + this.id + '-option">\n\
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
						<div class="form-group field-content-zone ' + hide + '">\n\
							<label class="pull-left field-zone-name control-label">\n\
								' + required + this.name + ':\n\
							</label>\n\
							<div class="pull-left">\n\
								<div class="input-group date date_view_picker group-datepicker">\n\
									<div class="form-date-container">\n\
										<select class="form-control form-date-field">' + days + '</select>\n\
									</div>\n\
									<div class="form-date-container">\n\
										<select class="form-control form-date-field">' + months + '</select>\n\
									</div>\n\
									<div class="form-date-container">\n\
										<input type="text" class="form-control form-date-field">\n\
									</div>\n\
								</div>\n\
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

DateBlock.prototype.startFieldEvents = function() {
	var t = this;

	this.content.find('.edit-field').on('click', function(){
		t.zone.editField(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.name = t.option.text().trim();
		t.defaultvalue = {day: '1', month: '1', year: ''};
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
		var selected = (this.months[i].value === this.defaultvalue.month) ? 'selected' : '';
		months+= '<option value="' + this.months[i].value + '" ' + selected + '>' + this.months[i].name + '</option>';
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
	zone.designHideDateValueField(defaultvalue, days, months, this.defaultvalue.year);
	
	var edit = zone.getZone();
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