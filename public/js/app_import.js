App = Ember.Application.create({
	rootElement: '#emberAppImportContainer'
});

App.firstline = [];
App.secondline = [];
App.thirdline = [];
App.fourthline = [];
App.fifthline = [];

//Definiendo Rutas
App.Router.map(function() {
  this.resource('contacts', function(){
	  this.route('new');
  });
});

//Adaptador
//App.Adapter = DS.RESTAdapter.reopen({
//	//namespace: MyDbaseUrl,
//});

// Store (class)
App.Store = DS.Store.extend({
	revision: 13,
//	adapter: App.Adapter.create()
	adapter: DS.FixtureAdapter.extend({
        queryFixtures: function(fixtures, query, type) {
                return fixtures.filter(function(item) {
                for(prop in query) {
                    if( item[prop] != query[prop]) {
                        return false;
                    }
                }
                return true;
            });
        }
    })
});

// Store (object)
App.store = App.Store.create();

//Inicio importacion
App.Contact = DS.Model.extend(
	myImportModel
);

App.Contact.FIXTURES = [
	{id: 1, datas:'correo@correo.com,nombrecorreo,apellidocorreo,6909090,3206235566', delimiter:',', email:'hola', name:'', lastname:'', camposeleccion:'', camposimple:'', telefono:'', nuevocampo:''},
	{id: 2, datas:'correo2@correo2.com,nombre2correo,apellido2correo,6909092,3206235562', delimiter:',', email:'', name:'', lastname:'', camposeleccion:'', camposimple:'', telefono:'', nuevocampo:''},
	{id: 3, datas:'correo3@correo3.com,nombre3correo,apellido3correo,6909093,3206235563', delimiter:',', email:'', name:'', lastname:'', camposeleccion:'', camposimple:'', telefono:'', nuevocampo:''},
	{id: 4, datas:'correo4@correo4.com,nombre4correo,apellido4correo,6909094,3206235564', delimiter:',', email:'', name:'', lastname:'', camposeleccion:'', camposimple:'', telefono:'', nuevocampo:''},
	{id: 5, datas:'correo5@correo5.com,nombre5correo,apellido5correo,6909095,3206235565', delimiter:',', email:'', name:'', lastname:'', camposeleccion:'', camposimple:'', telefono:'', nuevocampo:''}
];

//rutas
App.ContactsIndexRoute = Ember.Route.extend({
	model: function(){
		return App.Contact.find();
	}
});


//Controladores
App.ContactController = Ember.ObjectController.extend();

App.ContactsIndexController = Ember.ObjectController.extend({
	init: function() {
		
	},
	partir: function() {
		var firstline =  App.Contact.find(1);
		var data = firstline.toJSON().datas
		var datas = data.split(",");
		for(var i=0; i<datas.length; i++) {
			App.firstline[i] = datas[i];
		}
		 this.render({ controller: 'contacts' });
	}
});

App.ContactsIndexView = Ember.View.extend({
}); 

App.delimiter_opt = [
	",", ";", "/"
];    

App.delimiterView =  Ember.View.extend({
  templateName: 'select'
});

App.DelimiterView = Ember.Select.extend({
	change: function(evt) {
		var firstline =  App.Contact.find(1);
		var data = firstline.toJSON().datas
		var datas = data.split(this.get('value'));

		for(var i=0; i<datas.length; i++) {
			App.firstline[i] = datas[i];
		}
		
		var secondline =  App.Contact.find(2);
		var data = secondline.toJSON().datas
		var datas = data.split(this.get('value'));

		for(var i=0; i<datas.length; i++) {
			App.secondline[i] = datas[i];
		}
		
		var thirdline =  App.Contact.find(3);
		var data = thirdline.toJSON().datas
		var datas = data.split(this.get('value'));

		for(var i=0; i<datas.length; i++) {
			App.thirdline[i] = datas[i];
		}
		var fourthline =  App.Contact.find(4);
		var data = fourthline.toJSON().datas
		var datas = data.split(this.get('value'));

		for(var i=0; i<datas.length; i++) {
			App.fourthline[i] = datas[i];
		}
		var fifthline =  App.Contact.find(5);
		var data = fifthline.toJSON().datas
		var datas = data.split(this.get('value'));

		for(var i=0; i<datas.length; i++) {
			App.fifthline[i] = datas[i];
		}
	}
});


