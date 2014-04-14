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
//**
//** RUTAS **
//**

//App.ContactsIndexRoute = Ember.Route.extend({
//	model: function(){
//		return this.store.find('contact');
//	}
//});

//App.ContactsShowRoute = Ember.Route.extend({
//});

//App.ContactsEditRoute = Ember.Route.extend({
//	deactivate: function () {
//		this.doRollBack();
//	},
//	contextDidChange: function() {
//		this.doRollBack();
//		this._super();
//    },
//	doRollBack: function () {
//		var model = this.get('currentModel');
//		if (model && model.get('isDirty') && !model.get('isSaving')) {
//			model.get('transaction').rollback();
//		}
//	}
//});
//** FIN RUTAS **

//**
//** CONTROLADORES **
//**
//App.ContactController = Ember.ObjectController.extend();

//App.ContactsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
//	actions: {
//		edit: function() {
//			var filter = /^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
//			if (filter.test(this.get('email'))) {
//				App.set('errormessage', '');
//				this.handleSavePromise(this.content.save(), 'contacts', 'Se ha editado el campo existosamente');
//			}
//			App.set('errormessage', 'La dirección de correo electrónico ingresada no es válida, por favor verifique los datos');
//		},
//		cancel: function(){
//			App.set('errormessage', '');
//			this.get('model').rollback();
//			this.transitionToRoute('contacts');
//		}
//	}
//});

//App.ContactsDeleteController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
//	actions: {
//		delete: function() {
//			var contact = this.get('model');
//			contact.deleteRecord();
//			
//			this.handleSavePromise(contact.save(), 'contacts', 'Se ha eliminado el contacto exitosamente');
//		},
//				
//		cancel: function(){
//			this.get("model").rollback();
//			this.transitionToRoute("contacts");
//		}
//	}
//});

//App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinSearchReferencePagination, Ember.AclMixin, Ember.SaveHandlerMixin,{
//	historyMail: function(){
//		var mailHistory = JSON.parse(this.content.get('mailHistory'));
//		this.set('history', mailHistory);
//	}.observes(this.content),
//	
//	init: function () {
//		this.set('acl', App.contactACL);
//	},
//	searchCriteria: '',
//    search: function(){
//		this.criteria = this.get('searchCriteria');
//
//		var resultado = this.store.find('contact', { searchCriteria: this.criteria });
//		this.set('content', resultado);
//	},
//	
//	reset: function(){
//		this.criteria = '';
//		var resultado = this.store.find('contact', { searchCriteria: null });
//		this.set('content', resultado);
//	},
//			
//	expand: function (contact) {
//		if(contact.get('isExpanded')) {
//			contact.set('isExpanded', false);
//		}
//		else {
//			contact.set('isExpanded', true);
//		}
//		$('.username').editable();
//	},
//	
//	subscribedcontact: function (contact) {
//		contact.set('isSubscribed', true);
//		contact.save();
//	},
//	unsubscribedcontact: function (contact) {
//		contact.set('isSubscribed', false);
//		contact.save();
//	},
//			
//	edit: function(contact) {
//		var filter = /^(([A-Za-z0-9]+_+)|([A-Za-z0-9]+\-+)|([A-Za-z0-9]+\.+)|([A-Za-z0-9]+\++))*[A-Za-z0-9]+@((\w+\-+)|(\w+\.))*\w{1,63}\.[a-zA-Z]{2,6}$/;
//		if (filter.test(contact.get('email'))) {
//			App.set('errormessage', '');
//			this.handleSavePromise(contact.save(), 'contacts', 'El contacto fue actualizado exitosamente');
//		}
//		else {
//			App.set('errormessage', 'La dirección de correo electrónico ingresada no es valida por favor verifique la información')
//		}
//	},
//			
//	discard: function(contact) {
//		
//	},
//
//	modelClass: App.Contact
//});

//App.ContactsShowController = Ember.ObjectController.extend({
//	historyMail: function(){
//		var mailHistory = JSON.parse(this.content.get('mailHistory'));
//		this.set('history', mailHistory);
//	}.observes(this.content),
//	actions :{
//		subscribedcontact: function () {
//			//this.set("isSubscribed", true);
//			var self = this;
//			self.content.set('isSubscribed', true);
//			self.content.save();
//		},
//		unsubscribedcontact: function () {
//			var self = this;
//			self.content.set('isSubscribed', false);
//			self.content.save();
//		}
//	}
//});


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
