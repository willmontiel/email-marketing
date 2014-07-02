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
	model: function() {
		return myImportModel;
	}
});


//Controladores
App.ContactsIndexController = Ember.ObjectController.extend({
        showAdvancedOptions: false,
        
	emailF: function () {
		return App.secondline[this.get('content.email')];
	}.property('content.email'),
	nameF: function () {
		return App.secondline[this.get('content.name')];
	}.property('content.name'),
	lastnameF: function () {
		return App.secondline[this.get('content.lastname')];
	}.property('content.lastname'),
	birthdateF: function () {
		return App.secondline[this.get('content.birthdate')];
	}.property('content.birthdate'),
	hasheader: function () {
		if(this.get('content.header') == true) {
			return true;
		} 
		else {
			return false;
		}
	}.property('content.header'),
        
//        showAdvancedOptions: function () {
//            return this.get('showAdv');
//        }.property(),
        
	/*
	 * Funcion que asigna vacio a todos los campos cuando
	 * cambia el delimitador (las opciones)
	 */

	chgFields: function () {
		for (f in this.get('content')) {
			if (f != 'header' && f != 'delimiter') {
				this.set('content.' + f, -1);
			}
		}
	}.observes('App.options')
});


//Views

App.ContactsIndexView = Ember.View.extend({
	didInsertElement: function() {
	//	$('.easy-pie-step').easyPieChart({barColor: '#599cc7', trackColor: '#a1a1a1', scaleColor: false, lineWidth: 10, size: 50, lineCap: 'butt'});
	}
}); 

App.delimiter_opt = [
	",", ";", "/"
];    

App.dateformats = [
	Ember.Object.create({format: "Y-m-d (1969-12-31)", id: "Y-m-d"}),
	Ember.Object.create({format: "Y/m/d (1969/12/31)", id: "Y/m/d"}),
	Ember.Object.create({format: "d-m-Y (31-12-1969)", id: "d-m-Y"}),
	Ember.Object.create({format: "d/m/Y (31/12/1969)", id: "d/m/Y"}),
	Ember.Object.create({format: "m-d-Y (12-31-1969)", id: "m-d-Y"}),
	Ember.Object.create({format: "m/d/Y (12/31/1969)", id: "m/d/Y"})
];

App.importmodes = [
	Ember.Object.create({value: "Contactos Suscritos - Opcion recomendada", id: "normal"}),
	Ember.Object.create({value: "Des-suscritos - Los contactos se marcaran como des-suscritos", id: "unsubscribed"}),
	Ember.Object.create({value: "Rebotados - Las direcciones de correo se marcaran como rebotadas", id: "bounced"}),
	Ember.Object.create({value: "Inactivos - Los contactos se marcaran como inactivos", id: "inactive"})
];

App.delimiterView =  Ember.View.extend({
    templateName: 'select',
});

App.DelimiterView = Ember.Select.extend({
    change: function(evt) {
            var delim = this.get('value');
            var opt = mappingColumns(advancedSplit(App.lines[0], delim))
            App.set('options', opt);
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
	res.push({name: 'No Importar', id: -1});
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


