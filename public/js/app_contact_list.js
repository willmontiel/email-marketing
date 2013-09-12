App.ContactsRoute =  Ember.Route.extend({
	setupController: function (controller, model) {
		controller.set('model', model);
		controller.inicializar();
	}
});

App.ContactsController = Ember.Controller.extend({
	lista: null,
	inicializar: function () {
		this.set('lista', this.store.find('list', currentList));
	}
});