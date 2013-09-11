//App.currentList = this.store.find('list', currentList);

App.ContactsRoute =  Ember.Route.extend({
	model: function() {
		App.currentList = this.store.find('list', currentList);
	}
});