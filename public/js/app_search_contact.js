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
	lastName: DS.attr('string')
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
	actions: {
		searchText: '',
		search: function(){
			var resultado = this.store.find('contact', {text: this.get('searchText') });
			this.set('content', resultado);
		},

		reset: function(){
			this.set('searchText', "");
		}
	}
});		