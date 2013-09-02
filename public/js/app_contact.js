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

var serializer = DS.RESTSerializer.create();

serializer.configure({
    meta: 'meta',
    pagination: 'pagination'
});

//Adaptador
App.Adapter = DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
	serializer: serializer
});

// Store (class)
App.Store = DS.Store.extend({
	revision: 13,
	adapter: App.Adapter.create()
//	adapter: DS.FixtureAdapter.extend({
//        queryFixtures: function(fixtures, query, type) {
//            console.log(query);
//            console.log(type);
//            return fixtures.filter(function(item) {
//                for(prop in query) {
//                    if( item[prop] != query[prop]) {
//                        return false;
//                    }
//                }
//                return true;
//            });
//        }
//    })
});

// Store (object)
App.store = App.Store.create();

//Inicio contactos
App.Contact = DS.Model.extend(
	myContactModel
);

//App.Contact.FIXTURES = [
//  { id: 1, email: 'puertorro@hotmail.es', name: 'Fenicio', lastName: 'Cuantindioy', activatedOn: 12345678, bouncedOn: 0, status: true, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 1225455524, ipActive: 13542532, ipSubscribed: 0, isBounced: false, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 2, email: 'lachicacandente@hotmail.es', name: 'Lola', lastName: 'Lolita', activatedOn: 12345678, status: true, bouncedOn: 15544512, subscribedOn: 123456, unsubscribedOn: 15171518, spamOn: 0, ipActive: 561151515, ipSubscribed: 14822852, isBounced: true, isActive: true, isSpam: false, isSubscribed: false },
//  { id: 3, email: 'superbigman@yahoo.es', name: 'Disney Alberto', lastName: 'Mosquera', activatedOn: 0, status: false,bouncedOn: 0, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 0, ipActive: 0, ipSubscribed: 0, isBounced: false, isActive: false, isSpam: false, isSubscribed: false },
//  { id: 5, email: 'yatusabe@live.com', name: 'Maicol Yovany', lastName: 'Icasa', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 6, email: 'elcoco@gmail.com', name: 'linux', lastName: 'bin', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 7, email: 'labebe@live.com', name: 'mac', lastName: 'var', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 8, email: 'ajam@live.com', name: 'Ubuntu', lastName: 'www', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 9, email: 'jj@jj.com', name: 'windows', lastName: 'ext', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 10, email: 'jojojo@live.com', name: 'fedora', lastName: 'dll', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true },
//  { id: 11, email: 'lol@live.com', name: 'kubuntu', lastName: 'query', activatedOn: 12345678, status: true, bouncedOn:123456, subscribedOn: 123456, unsubscribedOn: 0, spamOn: 123567, ipActive: 1528228, ipSubscribed: 0, isBounced: true, isActive: true, isSpam: true, isSubscribed: true }
//];

//Rutas

App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.find();
	}
});

//App.ContactsShowRoute = Ember.Route.extend({
//});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.createRecord();
	}
	,
	deactivate: function () {
		if (this.get('currentModel.isNew') && !this.get('currentModel.isSaving')) {
			this.get('currentModel.transaction').rollback();
			console.log('Rollback new Contact!');
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

//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsNewbatchController = Ember.ObjectController.extend();

App.ContactsNewController = Ember.ObjectController.extend({
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
//					App.set('errormessage', 'El Email no es valido');
//					this.get("target").transitionTo("contacts.new");
//				}
//			}
//			else{
//				App.set('errormessage', 'El Email ya existe en esta Lista');
//				this.get("target").transitionTo("contacts.new");
//			}
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
