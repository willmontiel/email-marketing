App = Ember.Application.create({
	rootElement: '#emberAppContactContainer'
});

App.set('errormessage', '');

//Definiendo Rutas
App.Router.map(function() {
  this.resource('contacts', function(){
	  this.route('new'),
	  this.route('newbatch'),
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

App.ContactsShowRoute = Ember.Route.extend({
});

App.ContactsNewRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.createRecord();
	},
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.get('transaction').rollback();
		}
	}
});

App.ContactsNewbatchRoute = Ember.Route.extend();

App.ContactsEditRoute = Ember.Route.extend({
	deactivate: function () {
		console.log('Deactivate ContactsEdit');
		this.doRollBack();
	},
	contextDidChange: function() {
        console.log('Cambio de modelo');
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

//Controladores

App.ContactController = Ember.ObjectController.extend();

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{	
	getModelMetadata: function() {
		return App.store.typeMapFor(App.Contact);
	},
	
	refreshModel: function (obj) {
		var result = App.Contact.find(obj);
		this.set('content', result);
	}
});

App.ContactsShowController = Ember.ObjectController.extend({
	deactivated: function () {
		this.set("isActive", false);		
	},
	activated: function () {
		this.set("isActive", true);
	},
	unsubscribedcontact: function () {
		this.set("isSubscribed", false);
	},
	subscribedcontact: function () {
		this.set("isSubscribed", true);
	}
});

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
				console.log('INVALID, INVALID Will Robinson!');
				this.handleFailure();
			});			
			model.on('becameError', this, function() {
				console.log('ERROR, ERROR Will Robinson!: ');
				console.log(this.get('content.error'));
				console.log(this.get('model.error'));
				console.log(this.get('content.errors'));
				console.log(this.get('model.errors'));
				this.handleFailure();
			});	
			model.on('didCreate', this, function() {
				this.get('target').transitionTo('contacts');
			});
			
			model.get('transaction').commit();
				}
	},
		
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
	},

	handleFailure: function() {
		console.log('Handling failures!!!');
		window.errormsg = this.get('content.errors');
		console.log(errormsg);
		this.set('errors', errormsg);
		App.set('errormessage', errormsg);
		this.get('transaction').rollback();
		this.set('content', App.Contact.createRecord(this.get('toJSON')));
	}
});

App.ContactsNewbatchController = Ember.ObjectController.extend();

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
		this.get('store').commit();
		this.get("target").transitionTo("contacts");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("contacts");
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








