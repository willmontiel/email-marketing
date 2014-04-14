App.Form = DS.Model.extend({});

App.FormsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('form');
	}
});

App.ContactsEditController = Ember.ObjectController.extend({});
