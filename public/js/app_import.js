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

App.ApplicationAdapter = DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl
});

// Store (class)
App.Store = DS.Store.extend();

// Store (object)
//App.store = App.Store.create();

//Inicio importacion
App.Contact = DS.Model.extend(
	myImportModel
);


//Rutas
App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
//		return this.store.find('contact');
		return myImportModel;
	}
});


//Controladores
App.ContactsIndexController = Ember.ObjectController.extend();


//Views

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


