App.EmberXEditBaseView = Em.View.extend({
  title: 'Editar informacion',
  selobj: null,
  didInsertElement: function() {
	var self = this;
  	this.selobj = this.$('.x-editable').editable({ 
		type: 'text', 
		title: self.title, 
		success: function (resp, newValue) { 
			//console.log('Este es el nuevo valor que debe ir a Ember: ' + newValue);
			self.set('value', newValue);
			self.get('controller').set(self.field, self.value);
			App.set('isEditable', true);
		} 
	});
	return this.selobj;
  },
  onValueChange: function () {
//	  console.log('Valor cambio!');
//	  console.log('Valor : [' + this.get('value') + ']');
	  this.selobj.editable('setValue', this.get('value'));
  }.observes('value')
});
//
//
////Se crea la vista EmberXEditableTextView y se carga el X-editable para la edición de campos de texto y 
//se conecta con el template/partial xeditable
App.EmberXEditableTextView = App.EmberXEditBaseView.extend({
	templateName: 'xeditable',
});

//Se crea la vista EmberXEditableTextAreaView carga el X-editable para la edición de campos textarea y se 
//conecta con el template/partial xeditable
App.EmberXEditableTextAreaView = App.EmberXEditBaseView.extend({
  templateName: 'xeditable',
  didInsertElement: function() {
	var self = this;
	this.selobj = this.$('.x-editable').editable({ 
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
	return this.selobj;
  }
});

//Se crea la vista EmberXEditableSelectView carga el X-editable para la edición de campos select y se 
//conecta con el template/partial xeditable_select
App.EmberXEditableSelectView = App.EmberXEditBaseView.extend({
	templateName: 'xeditable_select',
	didInsertElement: function() {
	  var self = this;
	  this.selobj = this.$('.x-editable').editable({ 
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
	  return this.selobj;
	}
});

//Se crea la vista EmberXEditableMultiSelectView carga el X-editable para la edición de campos multiselect/checklist y se 
//conecta con el template/partial xeditable_select
App.EmberXEditableMultiSelectView = Em.View.extend({
	templateName: 'xeditable_select',
	title: 'Editar informacion',
	didInsertElement: function() {
	  var self = this;
	  this.selobj = this.$('.x-editable').editable({ 
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
	  return this.selobj;
	},
	onChangeValue: function () {
		if(typeof this.get('value') === "string") {
			this.selobj.editable('setValue', this.get('value').split(','));
		}
	}.observes('this.value')
});

//Se crea la vista EmberXEditableDateView carga el X-editable para la edición de campos date y se 
//conecta con el template/partial xeditable
App.EmberXEditableDateView = Em.View.extend({
  templateName: 'xeditable',
  title: 'Editar informacion',
  selobj: null,
	didInsertElement: function() {
		var self = this;
		this.selobj = this.$('.x-editable').editable({ 
			type: 'date', 
			title: self.title,
			format: 'yyyy/mm/dd',    
			viewformat: 'yyyy/mm/dd',
			datepicker: {weekStart: 1},
			success: function (resp, newValue) { 
				var mes_tmp = "0" + (newValue.getUTCMonth()+1);
				mes_tmp = mes_tmp.substring(mes_tmp.length-2);

				var fecha_total = newValue.getUTCFullYear() + '-' + mes_tmp + '-' + newValue.getUTCDate();

				self.set('value', fecha_total);

				self.get('controller').set(self.field, self.value);
				App.set('isEditable', true);
			} 
		});
		return this.selobj;
	},
	onChangeValue: function () {
		var fecha = new Date(this.get('value'));
		this.selobj.editable('setValue', fecha);
	}.observes('this.value')
});
