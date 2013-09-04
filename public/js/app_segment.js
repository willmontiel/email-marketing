App.Segment = DS.Model.extend({
	name: DS.attr('string')
});

//Definiendo rutas

App.SegmentsIndexRoute = Ember.Route.extend({
	model: function() {
		return App.Segment.find();
	}
});

//Definiendo controladores

App.SegementController = Ember.ObjectController.extend();

App.SegementsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	getModelMetadata: function() {
		return App.store.typeMapFor(App.Blockedemail);
	},
	
	refreshModel: function (obj) {
		var result = App.Blockedemail.find(obj);
		this.set('content', result);
	}
});
