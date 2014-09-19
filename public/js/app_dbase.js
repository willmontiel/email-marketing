App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

// Adaptador
App.ApplicationAdapter = DS.RESTAdapter.extend();

App.ApplicationAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: App.AplicationSerializer
});

// Store (class)
App.Store = DS.Store.extend({});

//Rutas

App.Router.map(function() {
  this.resource('fields', function(){
	  this.route('add'),
	  this.resource('fields.edit', { path: '/fields/:field_id'}),
	  this.resource('fields.remove', { path: '/remove/:field_id'});
  });
  
  this.resource('contacts', function(){
//	  this.resource('contacts.show', { path: '/show/:contact_id'}),
//	  this.resource('contacts.edit', { path: '/edit/:contact_id'}),
	  this.resource('contacts.delete', { path: '/delete/:contact_id'});
  });
  this.resource('forms', function(){
	  this.route('setup');
	  this.route('updating');
	  this.route('index');
	  this.route('new', { path: '/editor/:form_id'});
	  this.route('edit', { path: '/edit/:form_id'});
	  this.route('editupdate', { path: '/edit/update/:form_id'});
	  this.route('remove', { path: '/remove/:form_id'});
	  this.route('code', { path: '/code/:form_id'});
	  this.route('link', { path: '/link/:form_id'});
  });
});

App.Field = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	minValue: DS.attr('number'),
	maxValue: DS.attr('number'),
	maxLength: DS.attr('number'),
	isSelect: function() {
		return (this.get('type') == "Select" || this.get('type') == "MultiSelect");
	}.property('type'),
			
	isText: function() {
		return (this.get('type') == "Text");
	}.property('type'),
	
	isNumerical: function() {
		return (this.get('type') == "Numerical");
	}.property('type'),
			
	isDate: function() {
		return (this.get('type') == "Date");
	}.property('type')
});

//**
// ** RUTAS **
//**

App.FieldsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('field');
	}
});

App.FieldsAddRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('field');
	},

	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.rollback();
		}
	}
});

App.FieldsEditRoute = Ember.Route.extend({
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && model.get('isSaving') == false) {
			model.get('transaction').rollback();
		}
	}
});

//** FIN RUTAS **

//**
// ** CONTROLADORES **
//**
App.FieldController = Ember.ObjectController.extend();

App.FieldsIndexController = Ember.ArrayController.extend(Ember.AclMixin, {
	init: function () 
	{
		this.set('acl', App.customFieldACL);
	}
});

App.FieldsAddController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	actions : {
		save: function() {
			var self = this;
			if (self.get('values') != undefined) { 
				self.set('values', 
				self.get('values').split('\n')
				);
			}
			if (this.get('name') == "") {
				App.set('errormessage', 'El campo personalizado debe tener un nombre');
			}
			else {
				this.handleSavePromise(this.content.save(), 'fields.index', 'Se ha creado el campo personalizado');
			}
		},
				
		cancel: function(){
			this.transitionToRoute("fields");
		}
	}	
});

App.FieldsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	setOptions: function ()
	{
		var values = this.get('values');
		var valuesInLine = values.replace(/,/g, '\n');
		this.set('values', valuesInLine);
		
	}.observes('content'),
	
	actions: {
		edit: function() {
			var self = this;
			if (self.get('values') != undefined) { 
				self.set('values', 
				self.get('values').split('\n')
				);
			}
			this.handleSavePromise(this.content.save(), 'fields', 'Se ha editado el campo personalizado exitosamente');
		},
		cancel: function() {
			this.get('model').rollback();
			this.transitionToRoute('fields');
		}
	}
});

App.FieldsRemoveController = Ember.ObjectController.extend(Ember.SaveHandlerMixin,{
	actions: {
		eliminate: function() {
			var field = this.get('model');
			//borrando registro del store
			field.deleteRecord();
			
			//haciendo persistencia en el cambio
			this.handleSavePromise(field.save(), 'fields.index', 'Se ha eliminado el campo personalizado exitosamente'),
					
			function (error) {
				field.rollback();
            };
		},
				
		cancel: function() {
			this.get("model").rollback();
			this.transitionToRoute('fields.index');
		}
	}
});
//** FIN CONTROLADORES **

Ember.TextField.reopen({
	attributeBindings: ["required"]	
});
App.types = [
  Ember.Object.create({type: "Texto", id: "Text"}),
  Ember.Object.create({type: "Fecha",    id: "Date"}),
  Ember.Object.create({type: "Numerico",    id: "Numerical"}),
  Ember.Object.create({type: "Area de texto",    id: "TextArea"}),
  Ember.Object.create({type: "Selección",    id: "Select"}),
  Ember.Object.create({type: "Selección Multiple",    id: "MultiSelect"})
];

//**
//** Fin de todo lo que tenga que ver con los campos **
//********************************************************************************************************************

//********************************************************************************************************************
//** Inicio contactos **
//**

App.Contact = DS.Model.extend(
	myContactModel
);

App.List = DS.Model.extend({
    name: DS.attr('string'),
	lists: DS.hasMany('contact')
});

App.DatePickerField = Em.View.extend({
  templateName: 'datepicker',
  didInsertElement: function() {
    var onChangeDate, self;
    self = this;
    onChangeDate = function(ev) {
      return self.set("value", moment.utc(ev.date).format("YYYY-MM-DD"));
    };
    return this.$('.datepicker').datepicker({
      separator: "-"
    }).on("changeDate", onChangeDate);
  }
});

App.TimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	didInsertElement:function(){
		try{
			createCharts('ChartContainer', App.data, true, false);
		}
		catch(err){
			
		}
	}			
});
