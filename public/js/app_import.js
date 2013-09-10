App = Ember.Application.create({
	rootElement: '#emberAppImportContainer'
});


//Definiendo Rutas
App.Router.map(function() {
  this.resource('contacts', function(){
	  this.route('new');
  });
});

//Adaptador
App.Adapter = DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl
});

// Store (class)
App.Store = DS.Store.extend({
	revision: 13,
	adapter: App.Adapter.create()
//	adapter: DS.FixtureAdapter.extend({
//        queryFixtures: function(fixtures, query, type) {
//                return fixtures.filter(function(item) {
//                for(prop in query) {
//                    if( item[prop] != query[prop]) {
//                        return false;
//                    }
//                }
//                return true;
//            });
//        }
//    })
});

// Store (object)
App.store = App.Store.create();

//Inicio importacion
App.Contact = DS.Model.extend(
	myImportModel
);


//rutas
App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.find();
	}
});


//Controladores
App.ContactController = Ember.ObjectController.extend();

App.ContactsIndexController = Ember.ObjectController.extend({

});

App.ContactsIndexView = Ember.View.extend({
	didInsertElement: function() {
		$('.easy-pie-step').easyPieChart({barColor: '#599cc7', trackColor: '#a1a1a1', scaleColor: false, lineWidth: 10, size: 50, lineCap: 'butt'});
    }
}); 

App.delimiter_opt = [
	",", ";", "/"
];    

App.delimiterView =  Ember.View.extend({
  templateName: 'select'
});

App.DelimiterView = Ember.Select.extend({
	change: function(evt) {
		
		var datas = App.originalF.split(this.get('value'));
		App.set("options", datas);
		App.options.unshift(" ");
		
		var datas = App.originalF.split(this.get('value'));
		App.set("firstline", datas);
		
		var datas = App.originalS.split(this.get('value'));
		App.set("secondline", datas);

		var datas = App.originalT.split(this.get('value'));
		App.set("thirdline", datas);
		
		var datas = App.originalFo.split(this.get('value'));
		App.set("fourthline", datas);
		
		var datas = App.originalFi.split(this.get('value'));
		App.set("fifthline", datas);
		
	}
});


