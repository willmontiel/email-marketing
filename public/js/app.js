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


//Inicio de todo lo que tenga que ver con los campos
App.Field = DS.Model.extend({
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	minValue: DS.attr('number'),
	maxValue: DS.attr('number'),
	maxLength: DS.attr('number'),
//	changedRequired: function() {
//	}.observes("required")
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

App.FieldsAddController = Ember.ObjectController.extend({
	actions : {
		save: function() {
			var self = this;
			self.content.save().then(function() {
				self.transitionToRoute('fields.index');
			});
		}
	}	
});


App.FieldsEditController = Ember.ObjectController.extend({
	edit: function() {
	exist = App.Field.find().filterProperty('name', this.get('name'));
		if(exist.get("length") === 1 && this.get('name').toLowerCase() !== 'email' && this.get('name').toLowerCase() !== 'nombre' && this.get('name').toLowerCase() !== 'apellido'){
			if (this.get('values') != undefined) { 
				this.set('values', 
				this.get('values').split('\n')
				);
			}

			this.get("model.transaction").commit();
			App.set('errormessage', '');
			this.get("target").transitionTo("fields.index");
		} else {
			App.set('errormessage', 'El campo ya existe!');
			this.get("target").transitionTo("fields.edit");
		}
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 App.set('errormessage', '');
		 this.get("target").transitionTo("fields");
	}
});

App.FieldController = Ember.ObjectController.extend();

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

//App.Field.FIXTURES = [
//  { id: 1, name: 'Email', type: 'Text', required: true, values: '', defaultValue: 'Ninguno' },
//  { id: 2, name: 'Nombre', type: 'Text', required: true, values: '', defaultValue: '' },
//  { id: 3, name: 'Apellido', type: 'Text', required: false, values: '', defaultValue: '' }
//];

App.FieldsIndexRoute = Ember.Route.extend({
	model: function(){
	 return this.store.find('field');
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

App.FieldsAddRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('field');
	},
			
	actions: {
		save: function(){
			this.modelFor('field').save();
		}
	}
			
//	deactivate: function () {
//		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
//			this.currentModel.get('transaction').rollback();
//		}
//	}
});

App.FieldsRemoveController = Ember.ObjectController.extend({
	actions: {
		eliminate: function() {
			var self = this;
			var field = self.get('model');
			field.deleteRecord();
			
			field.save().then(function () {
				self.transitionToRoute('fields.index');
			});
		}
	}
});

// App.FieldsController = Ember.ArrayController.extend();
App.FieldsIndexController = Ember.ArrayController.extend();
//Fin de todo lo que tenga que ver con los campos



//Inicio contactos
App.Contact = DS.Model.extend(
	myContactModel
);


App.List = DS.Model.extend({
    name: DS.attr('string'),
	lists: DS.hasMany('contact')
});

//Rutas

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
	
	actions: {
		save: function() {
			this.modelFor('contact').save();
		}
	}
//	deactivate: function () {
//		if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
//			this.get('currentModel.transaction').rollback();
//		}
//	}
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

//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend();

App.ContactsNewController = Ember.ObjectController.extend({
	errors: null,
	
	save: function() {
		
//		 exist = App.Contact.find().filterProperty('email', this.get('email'));
//			if(exist.get("length") === 1){
//				var filter=/^[A-Za-z][A-Za-z0-9_]*@[A-Za-z0-9_]+\.[A-Za-z0-9_.]+[A-za-z]$/;
//				if(filter.test(this.get('email'))){
//					this.get('model').set('isActive', true);
//					this.get('model').set('isSubscribed', true);
//					this.get("model.transaction").commit();
//					App.set('errormessage', '');
//					this.get("target").transitionTo("contacts");
//				}
//				else{
//					App.set('errormessage', 'El email no es correcto');
//					this.get("target").transitionTo("contacts.new");
//				}
//			}
//			else{
//				App.set('errormessage', 'El email ya existe');
//				this.get("target").transitionTo("contacts.new");
//			}
		var model = this.get('model');
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
			
			model.get('transaction').commit();
				}
	},
		
	cancel: function(){
		this.get("target").transitionTo("contacts");
	},

	handleFailure: function() {
		window.errormsg = this.get('content.errors');
		this.set('errors', errormsg);
		App.set('errormessage', errormsg);
		this.get('transaction').rollback();
		this.set('content', App.Contact.createRecord(this.get('toJSON')));
	}
});

App.ContactsEditController = Ember.ObjectController.extend({
	edit: function() {
			this.get("model.transaction").commit();
			this.get("target").transitionTo("contacts");
		
	},
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
	}
});

App.ContactsDeleteController = Ember.ObjectController.extend({
    delete: function() {
		this.get('content').deleteRecord();
		this.get('model.transaction').commit();
		this.get("target").transitionTo("contacts");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
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