//Se crea la vista EmberXEditableTextView y se carga el X-editable para la edición de campos de texto y 
//se conecta con el template/partial xeditable
App.EmberXEditableTextView = Em.View.extend({
  templateName: 'xeditable',
  title: 'Editar informacion',
  didInsertElement: function() {
	var self = this;
    return this.$('.x-editable').editable({ 
		type: 'text', 
		title: self.title, 
		success: function (resp, newValue) { 
			//console.log('Este es el nuevo valor que debe ir a Ember: ' + newValue);
			self.set('value', newValue);
			self.get('controller').set(self.field, self.value);
			App.set('isEditable', true);
		} 
	});
  }
});

//Se crea la vista EmberXEditableTextAreaView carga el X-editable para la edición de campos textarea y se 
//conecta con el template/partial xeditable
App.EmberXEditableTextAreaView = Em.View.extend({
  templateName: 'xeditable',
  title: 'Editar informacion',
  didInsertElement: function() {
	var self = this;
    return this.$('.x-editable').editable({ 
		type: 'textarea', 
		title: self.title, 
		rows: 10,
		success: function (resp, newValue) { 
			//console.log('Este es el nuevo valor que debe ir a Ember: ' + newValue);
			self.set('value', newValue);
			self.get('controller').set(self.field, self.value);
			App.set('isEditable', true);
		} 
	});
  }
});

//Se crea la vista EmberXEditableSelectView carga el X-editable para la edición de campos select y se 
//conecta con el template/partial xeditable_select
App.EmberXEditableSelectView = Em.View.extend({
  templateName: 'xeditable_select',
  title: 'Editar informacion',
  didInsertElement: function() {
	var self = this;
    return this.$('.x-editable').editable({ 
		type: 'select',
		value: self.value,
		source: self.source,
		title: self.title,
		success: function (resp, newValue) { 
			//console.log('Este es el nuevo valor que debe ir a Ember: ' + newValue);
			self.set('value', newValue);
			self.get('controller').set(self.field, self.value);
			App.set('isEditable', true);
		} 
	});
  }
});

//Se crea la vista EmberXEditableMultiSelectView carga el X-editable para la edición de campos multiselect/checklist y se 
//conecta con el template/partial xeditable_select
App.EmberXEditableMultiSelectView = Em.View.extend({
  templateName: 'xeditable_select',
  title: 'Editar informacion',
  didInsertElement: function() {
	var self = this;
    return this.$('.x-editable').editable({ 
		type: 'checklist', 
		value: self.value,
		source: self.source,
		title: self.title,
		success: function (resp, newValue) {
			self.set('value', newValue);
			self.get('controller').set(self.field, self.value);
			App.set('isEditable', true);
		} 
	});
  }
});

//Se crea la vista EmberXEditableDateView carga el X-editable para la edición de campos date y se 
//conecta con el template/partial xeditable
App.EmberXEditableDateView = Em.View.extend({
  templateName: 'xeditable',
  title: 'Editar informacion',
  didInsertElement: function() {
	var self = this;
    return this.$('.x-editable').editable({ 
		type: 'date', 
		title: self.title,
		format: 'dd/mm/yyyy',    
        viewformat: 'dd/mm/yyyy',
		datepicker: {weekStart: 1},
		success: function (resp, newValue) { 
			console.log('In ' + newValue);
//			var newDate = moment(newValue).format('yyyy/mm/dd');
//			console.log('Out ' + newDate);
			self.set('value', newValue);
			self.get('controller').set(self.field, self.value);
			App.set('isEditable', true);
		} 
	});
  }
});
