App = Ember.Application.create({
	rootElement: '#emberApplistContainer'
});

//Adaptador

DS.RESTAdapter.reopen({
	namespace: SearchContactUrl
});

// Store (class)
App.Store = DS.Store.extend({});

/* 
 * Ember Model for to search contacts.
 */
App.set('errormessage', '');

//Definiendo Rutas
App.Router.map(function() {
    this.resource('contacts', function(){});
});

App.Contact = DS.Model.extend({
	email: DS.attr('string'),
	name: DS.attr('string'),
	lastName: DS.attr('string'),
	idDbase: DS.attr('number'),
	dbase: DS.attr('string'),
});

App.ContactRoute = Ember.Route.extend({
	renderTemplate: function() {
		this.render('contacts/index');
	}
});

App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('contact');
	}
});

App.ContactController = Ember.ObjectController.extend();

App.ContactsIndexController = Ember.ArrayController.extend({
	init: function(){
		this.set('totalrecords', false);	
	},
	
	actions: {
		searchText: '',
		search: function(){
			var text = this.get('searchText');
			if (text == null || text == '') {
			}
			else {
				var resultado = this.store.find('contact', {text:  text});
				var t = this;
				resultado.then(function(p) {
					for (var index in p.store.typeMaps) {
						var total = (p.store.typeMaps[index].metadata.total);
					}
					if (total > 50) {
						t.set('totalrecords', true);	
					}
				});
				this.set('content', resultado);
			}
		},

		reset: function(){
			this.set('searchText', "");
		},
				
		seeMore: function(){
			var resultado = this.store.find('contact', {text: this.get('searchText') });
			this.set('totalrecords', false);	
			this.set('content', resultado);
		}
	}
});		