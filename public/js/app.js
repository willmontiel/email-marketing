App = Ember.Application.create({
	rootElement: '#emberAppContainer'
});

App.Router.map(function() {
  this.resource('campos', function(){
	  this.resource('adicionar'),
	  this.resource('editar');
  });
  
});

App.IndexRoute = Ember.Route.extend({
  model: function() {
    return ['red', 'yellow', 'blue'];
  }
});