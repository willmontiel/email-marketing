App.set('errormessage', '');

App.Router.map(function() {
  this.resource('apertures', function(){
  });
});

/*Routes*/

App.AperturesIndexRoute = Ember.Route.extend({
//	model: function () {
//		return this.store.find('list');
//	}
});

/*Controllers*/
App.ApertureController = Ember.ObjectController.extend();
App.AperturesIndexController = Ember.ArrayController.extend(Ember.MixinPagination, Ember.AclMixin,{	
  
});	