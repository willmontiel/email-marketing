App = Ember.Application.create({
	rootElement: '#emberAppContactContainer'
});

App.set('errormessage', '');

//Definiendo Rutas
App.Router.map(function() {
  this.resource('contacts', function(){
	  this.route('new'),
	  this.route('newbatch'),
	  this.route('import'),
	  this.route('newimport'),
	  this.resource('contacts.show', { path: '/show/:contact_id'}),
	  this.resource('contacts.edit', { path: '/edit/:contact_id'}),
	  this.resource('contacts.delete', { path: '/delete/:contact_id'});
  });
});

//var serializer = DS.RESTSerializer.create();
//
//serializer.configure({
//    meta: 'meta',
//    pagination: 'pagination'
//});

//Adaptador
App.ApplicationAdapter = DS.RESTAdapter.extend();

App.ApplicationAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: App.ApplicationSerializer
});

// Store (class)
App.Store = DS.Store.extend();

// Store (object)
//App.store = App.Store.create();

//Inicio contactos
App.Contact = DS.Model.extend(
	myContactModel
);

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
	actions : {
		deactivate: function () {
			if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
				this.get('currentModel.transaction').rollback();
			}
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
		if (model && model.get('isDirty') && !model.get('isSaving') ) {
			model.get('transaction').rollback();
		}
	}
});

App.ContactsImportRoute = Ember.Route.extend();

App.ContactsImportView = Ember.View.extend({
	didInsertElement: function() {
		$('.easy-pie-step').easyPieChart({barColor: '#599cc7', trackColor: '#a1a1a1', scaleColor: false, lineWidth: 10, size: 50, lineCap: 'butt'});
    }
});
//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend();

Ember.SaveHandlerMixin = Ember.Mixin.create({
	handleSavePromise: function (p, troute, message) {
		var self = this;
		p.then(function () {
			self.transitionToRoute(troute);
			$.gritter.add({title: 'Operacion exitosa', text: message, sticky: false, time: 3000});
		}, function (error) {
			if (error.status == 422) {
				try {
					var obj = $.parseJSON(error.responseText);
					self.set('errors', obj.errors);
				}
				catch (e) {
				}
			}
			else {
				self.set('errors', {errormsg: error.statusText});
			}
		});
	}
});

App.ContactsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	
	actions: {
		save: function() {
			this.content.set('isActive', true);
			this.content.set('isSubscribed', true);
			this.handleSavePromise(this.content.save(), 'contacts', 'El contacto ha sido creado con exito!');
		},	
		
		cancel: function(){
			var record = this.get('model');
			if (record.get('isDirty')) {
				record.rollback();
			}
			this.transitionToRoute("contacts");
		}
	}
	,
	emailChanged: function () {
		var em = this.get('content.email');
//		if (em.regexp()) {
//			
//		}
	}.observes('content.email')
});

App.ContactsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions : {
		edit: function() {
			this.handleSavePromise(this.content.save(), 'contacts', 'El contacto fue actualizado exitosamente');
		},
		cancel: function(){
			this.get('model').rollback();
			this.transitionToRoute("contacts");
		}
	}
});

App.ContactsDeleteController = Ember.ObjectController.extend({
    actions : {
		
		delete: function() {
			var self = this;		
			self.get('model').deleteRecord();
			self.get('model').save();		
			this.transitionToRoute('contacts');
		},
				
		cancel: function(){
			 this.get("model").rollback();
			 this.transitionTo("contacts");
		}
	}
	
	
});

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{	
	searchText: '',
	modelClass : App.Contact,
    search: function(){
		var resultado = App.Contact.find({ email: this.get('searchText') });
		this.set('content', resultado);
	}
});

App.ContactsShowController = Ember.ObjectController.extend({
	actions :{
		subscribedcontact: function () {
			//this.set("isSubscribed", true);
			var self = this;
			self.content.set('isSubscribed', true);
			self.content.save();
		},
		unsubscribedcontact: function () {
			var self = this;
			self.content.set('isSubscribed', false);
			self.content.save();
		}
	}
});

App.ContactsImportController = Ember.ObjectController.extend({
	cancel: function() {
		this.get("target").transitionTo("lists");
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