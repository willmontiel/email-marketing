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

//App.ContactsShowRoute = Ember.Route.extend({
//});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('contact');
	},
			
	actions: {
		save: function() {
			this.modelFor('contact').save();
		}
	}
	,
	
			
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

App.ContactsNewController = Ember.ObjectController.extend({
	save: function() {
		var that = this;
		if (this.get('model.isValid') && !this.get('model.isSaving')) {
			this.set('model.isActive', true);
			this.set('model.isSubscribed', true);
			
			this.get('content').one('didCreate', function() {
				that.transitionToRoute('contacts');
			});
			
			this.get('model.transaction').commit();
		}
	},
		
	cancel: function(){
		this.get("target").transitionTo("contacts");
	}
});

App.ContactsEditController = Ember.ObjectController.extend({
	edit: function() {
			this.get("model.transaction").commit();
			this.get("target").transitionTo("contacts");
	},
	cancel: function(){
		 this.get("model.transaction").rollback();
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
		 this.get("model.transaction").rollback();
		 this.get("target").transitionTo("contacts");
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
	unsubscribedcontact: function () {
		this.set("isSubscribed", false);
		this.get('model').one('becameInvalid', function() {
			alert('Fallo la actualizacion');
		});
		this.get('model.transaction').commit();
	},
	subscribedcontact: function () {
		var id = this.get('model').get('id');
		this.set("isSubscribed", true);
		this.get('model').one('becameInvalid', this, function() {
			//alert('Fallo la actualizacion');
			this.set('isSubscribed', false);
			this.get('model.transaction').commit();
		});
		this.get('model.transaction').commit();
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