App.Contact = DS.Model.extend({
	email: DS.attr('string'),
	name: DS.attr('string'),
	lastname: DS.attr('string')
});

//Definiendo rutas

App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('contact');
	}
});

//Definiendo controladores

App.ContactsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : App.Contact
});

// -------- // -------- // -------- // -------- // -------- // -------- //

App.Statistic = DS.Model.extend({
	type: DS.attr('string'),
	amount: DS.attr('string'),
});

//Definiendo rutas

App.StatisticsIndexRoute = Ember.Route.extend({
	model: function(){
		return this.store.find('contact');
	}
});

//Definiendo controladores

App.StatisticsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : App.Contact
});