App.Form = DS.Model.extend({});

App.FormsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('form');
	}
});

App.FormsIndexController = Ember.ObjectController.extend({});

App.FormsNewRoute = Ember.Route.extend({});

App.FormsNewController = Ember.ObjectController.extend({
	init : function() {
		$(function(){
			formeditor.startEvents();
		});
	}
});