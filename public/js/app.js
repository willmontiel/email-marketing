App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

App.set('errormessage', '');

App.Router.map(function() {
  this.resource('fields', function(){
	  this.route('add'),
	  this.resource('fields.edit', { path: '/fields/:field_id'}),
	  this.resource('fields.remove', { path: '/remove/:field_id'});
  });
  
  this.resource('contacts', function(){
	  this.route('new'),
	  this.resource('contacts.show', { path: '/show/:contact_id'}),
	  this.resource('contacts.edit', { path: '/edit/:contact_id'}),
	  this.resource('contacts.delete', { path: '/delete/:contact_id'}),
	  this.route('newbatch');
  });
});

/* STORE */
// Serializador
//var serializer = DS.RESTSerializer.create();

//serializer.configure({
//    meta: 'meta',
//    pagination: 'pagination'
//});

// Adaptador
App.ApplicationAdapter = DS.RESTAdapter.extend();

App.ApplicationAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: App.AplicationSerializer
});

// Store (class)
App.Store = DS.Store.extend({});

// Store (object)
//App.store = App.Store.extend();

//**
// ** Inicio de todo lo que tenga que ver con los campos personalizados**
//**

App.Field = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	minValue: DS.attr('number'),
	maxValue: DS.attr('number'),
	maxLength: DS.attr('number'),
	becameError: function() {
		return alert('there was an error!');
	},
	becameInvalid: function(errors) {
		return alert("Record was invalid because: " + errors);
	},
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
			this.currentModel.get('transaction').rollback();
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

App.FieldsAddController = Ember.ObjectController.extend({
	actions : {
		save: function() {
			var self = this;
			self.content.save().then(function() {
				self.transitionToRoute('fields.index');
			});
		},
				
		cancel: function(){
			var self = this;
			self.get('model').rollback();
			self.transitionToRoute('fields');
		}
	}	
});

App.FieldsEditController = Ember.ObjectController.extend({
	actions: {
		edit: function() {
			var self = this;
			if (self.get('values') != undefined) { 
				self.set('values', 
				self.get('values').split('\n')
				);
			}
			self.content.save().then(function() {
				self.transitionToRoute('fields');
			});
		},
		cancel: function() {
			this.get('model').rollback();
			this.transitionToRoute('fields');
		}
	}
});

App.FieldsRemoveController = Ember.ObjectController.extend({
	actions: {
		eliminate: function() {
			var self = this;
			var field = self.get('model');
			
			//borrando registro del store
			field.deleteRecord();
			
			//haciendo persistencia en el cambio
			field.save().then(function () {
				self.transitionToRoute('fields.index');
			}),
					
			function (error) {
				field.rollback();
            };
		},
				
		cancel: function() {
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

App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('contact');
	}
});

App.ContactsShowRoute = Ember.Route.extend({
});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('contact');
	},
			
	deactivate: function () {
		if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
			this.get('currentModel.transaction').rollback();
		}
	}
});

App.ContactsNewbatchRoute = Ember.Route.extend();

App.ContactsEditRoute = Ember.Route.extend({
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && !model.get('isSaving')) {
			model.get('transaction').rollback();
		}
	}
});
//** FIN RUTAS **

//**
//** CONTROLADORES **
//**
App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend();

App.ContactsNewController = Ember.ObjectController.extend({
	errors: null,
	actions: {
		save: function() {
			var model = this.get('model');
			var self = this;
			if (model.get('isValid') && !model.get('isSaving')) {

				model.set('isActive', true);
				model.set('isSubscribed', true);

				model.on('becameInvalid', this, function() {
					this.handleFailure();
				});			
				model.on('becameError', this, function() {
					this.handleFailure();
				});	
				model.on('didCreate', this, function() {
					this.get('target').transitionTo('contacts');
				});

				self.content.save().then(function () {
					self.transitionToRoute('contacts');
				});
			}
		},

		cancel: function(){
			this.transitionToRoute("contacts");
		}
	},
	
	handleFailure: function() {
		var self = this;
		window.errormsg = self.get('content.errors');
		self.set('errors', errormsg);
		App.set('errormessage', errormsg);
		self.get('model').rollback();
		self.set('content', this.store.createRecord('contact', this.get('toJSON')));
	}
});

App.ContactsEditController = Ember.ObjectController.extend({
	actions: {
		edit: function() {
			var self = this;
			self.content.save().then(function (){
				self.transitionToRoute('contacts');
			});	
		},
		cancel: function(){
			var self = this;
			self.get('model').rollback();
			self.transitionToRoute('contacts');
		}
	}
});

App.ContactsDeleteController = Ember.ObjectController.extend({
	actions: {
		delete: function() {
			var self = this;
			var contact = self.get('model');
			contact.deleteRecord();
			
			contact.save().then(function() {
				contact.transitionToRoute('contacts');
			});
		},
				
		cancel: function(){
			this.transitionToRoute("contacts");
		}
	}
});

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{	
	searchText: '',
    search: function(){
		var resultado = this.store.find('contact', { email: this.get('searchText') });
		this.set('content', resultado);
	},
	modelClass: 'Contact'
});

App.ContactsShowController = Ember.ObjectController.extend({
//	deactivated: function () {
//		this.set("isActive", false);		
//	},
//	activated: function () {
//		this.set("isActive", true);
//	},
	unsubscribedcontact: function () {
		this.set("isSubscribed", false);
		this.get('model.transaction').commit();
	},
	subscribedcontact: function () {
		this.set("isSubscribed", true);
		this.get('model.transaction').commit();
	}
});

//Views

App.ContactsNewView = Ember.View.extend({
  didInsertElement: function() {
        jQuery("select").select2({
			placeholder: "Seleccione las Opciones"
		});
    }
});

App.ContactsEditView = Ember.View.extend({
  didInsertElement: function() {
        jQuery("select").select2({
			placeholder: "Seleccione las Opciones"
		});
    }
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