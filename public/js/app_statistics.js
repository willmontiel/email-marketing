App.set('errormessage', '');

App.Router.map(function() {
  this.resource('drilldown', function(){
	  this.route('apertures');
  });
});

/*Routes*/

App.DrilldownIndexRoute = Ember.Route.extend({
//	model: function () {
//		return this.store.find('list');
//	}
});

App.DrilldownAperturesRoute = Ember.Route.extend({
//	model: function () {
//		return this.store.find('list');
//	}
});

/*Controllers*/
App.DrilldownController = Ember.ObjectController.extend();
App.DrilldownIndexController = Ember.ArrayController.extend(Ember.MixinPagination, Ember.AclMixin,{	
  
});	
App.DrilldownAperturesController = Ember.ArrayController.extend(Ember.MixinPagination, Ember.AclMixin,{	
  
});