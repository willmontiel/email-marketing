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
		var delim = this.get('value');
		
		App.set('options', advancedSplit(" " + delim + App.lines[0], delim));
		App.set('firstline', advancedSplit(App.lines[0], delim));
		App.set('secondline',  advancedSplit(App.lines[1], delim));
		App.set('thirdline',  advancedSplit(App.lines[2], delim));
		App.set('fourthline',  advancedSplit(App.lines[3], delim));
		App.set('fifthline', advancedSplit(App.lines[4], delim));
	}
});

function mappingColumns(arrayopt)
{
	var res = [];
	for (var i = 0; i < arrayopt.length; i++) {
		objtemp = new Object();
		objtemp.name = arrayopt[i];
		objtemp.id = i;
		res.push(objtemp);
	}
	return res;
}

function advancedSplit(cad, delim)
{
	var arreglo = cad.split(delim),
		j = 0, i,
		res = [], 
		inside = false;

	for (i = 0; i < arreglo.length; i++) {
		if (inside) {
			if (arreglo[i].charAt(arreglo[i].length-1) == '"') {
				res[res.length-1] += arreglo[i].substr(0, arreglo[i].length-1);
				inside = false;
			}
			else {
				res[res.length-1] += arreglo[i] + delim;
			}
		}
		else if (arreglo[i].charAt(0) == '"') {
			if (arreglo[i].charAt(arreglo[i].length-1) == '"') {
				res.push(arreglo[i].substr(1, arreglo[i].length-2));
			}
			else {
				inside = true;
				res.push(arreglo[i].substr(1) + delim);
			}
		}
		else {
			res.push(arreglo[i]);
		}
	}
	return res;
}


