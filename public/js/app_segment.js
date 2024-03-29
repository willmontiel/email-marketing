App.Segment = DS.Model.extend({
	name: DS.attr('string'),
	description: DS.attr('string'),
	criterion: DS.attr('string'),
	criteria: DS.attr('string'),
	dbase: DS.belongsTo('dbase')
});

App.criteria = [
  Ember.Object.create({criterion: "todas las", id: "all"}),
  Ember.Object.create({criterion: "cualquiera de las",    id: "any"})
];

App.relations = [
  Ember.Object.create({relation: "Es", id: "equals"}),
  Ember.Object.create({relation: "Contiene",    id: "content"}),
  Ember.Object.create({relation: "No contiene",    id: "!content"}),
  Ember.Object.create({relation: "Empieza con",    id: "begins"}),
  Ember.Object.create({relation: "Termina en",    id: "ends"}),
//  Ember.Object.create({relation: "Mayor que",    id: "greater"}),
//  Ember.Object.create({relation: "Menor que",    id: "less"})
];


App.Field = DS.Model.extend({
	dbase: DS.belongsTo('dbase'),
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	minValue: DS.attr('number'),
	maxValue: DS.attr('number'),
	maxLength: DS.attr('number')
});


//Definiendo rutas

App.SegmentsIndexRoute = Ember.Route.extend({
//	modelClass : 'segment',
	model: function(){
		return this.store.find('segment');
	}
});

App.SegmentsNewRoute = Ember.Route.extend({
	model: function(){
		return this.store.createRecord('segment');
	},
	setupController: function (controller, model) {
		this._super(controller, model);
		controller.loadDbases();
	},
	
	deactivate: function () {
		if (this.currentModel.get('isNew') && this.currentModel.get('isSaving') == false) {
			this.currentModel.rollback();
		}
	}
});

App.SegmentsEditRoute = Ember.Route.extend({
	deactivate: function () {
		this.doRollBack();
	},
	contextDidChange: function() {
		this.doRollBack();
		this._super();
    },
	doRollBack: function () {
		var model = this.get('currentModel');
		if (model && model.get('isDirty') && model.get('isSaving') == false) {
			model.rollback();
		}
	}
});

App.SegmentsDeleteRoute = Ember.Route.extend({});

App.SegmentsShowRoute = Ember.Route.extend({});

//Definiendo controladores

App.SegmentController = Ember.ObjectController.extend();

App.SegmentsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	selectedDbase: null,
	init: function () 
	{
		this.set('acl', App.contactListACL);
		var t = this;
		this.store.find('dbase').then(function(db) {
			var dbases = db.get('content');
			var values = [{id: 0, name:'Todas las Bases de Datos'}];
			for(var i = 0; i < dbases.length; i++) {
				var obj = {id: dbases[i].get('id'), name: dbases[i].get('name')};
				values.push(obj);
			}
			t.set('dbaseSelect', values);
		});
	},
	
	modelClass : App.Segment,		
	needs: ['dbase'],
	
	dbaseSelect: [],
	
	dbaseSelectChange: function () {
		var idDbase = this.get('selectedDbase');
		var resultado = this.store.find('segment', { dbase: idDbase });
		this.set('content', resultado);
    }.observes('selectedDbase'),
});

App.SegmentsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	
	criteria: Ember.A(),
	disableRemoveCriteria: true,		
	
	needs: ['dbase'],
	
	cfields: Ember.A([
		{id: 'email', name: 'Correo electronico', type: 'text'},
		{id: 'domain', name: 'Dominio del Correo', type: 'text'},
		{id: 'name', name: 'Nombre', type: 'text'},
		{id: 'lastName', name: 'Apellido', type: 'text'},
	]),
	stdfields: [
		{id: 'email', name: 'Correo electronico', type: 'text'},
		{id: 'domain', name: 'Dominio del Correo', type: 'text'},
		{id: 'name', name: 'Nombre', type: 'text'},
		{id: 'lastName', name: 'Apellido', type: 'text'},
//		{id: 'status', name: 'Estado', type: 'select', options: ['Activo', 'Inactivo', 'Des-suscrito', 'Spam', 'Rebotado']},
	],
	
	loadDbases: function () {
		this.get('controllers.dbase').set('content', this.store.find('dbase'));
	},
			
	resetCriteria: function () {
		if (this.get('content.isValid')) {
			this.set('criteria', new Ember.A([{}]));
		}
	}.observes('content'),
	
	changeDbase: function () {
		if (this.content.get('dbase')) {
			var s = this;
			this.set('dbaseSelected', true);
			// Cargar lista de custom fields
			var cfs = this.store.find('field', {dbase: this.get('content.dbase.id')}).then(function (data) {
				var arr = s.stdfields.slice(0);
				
				data.forEach(function (item, index) {
					arr.push({id: 'cf_' + item.get('id'), name: item.get('name')});
				});
				s.set('cfields.[]', arr);
			});
		}
	}.observes('content.dbase'),
	
	actions: {
		aConditionMore: function() {
			var newobj = {};
			this.criteria.pushObject(newobj);
			if ( this.criteria.length > 15 ) {
				this.set('limitCriteria', true);
			} else {
				this.set('limitCriteria', false);
			}
			if ( this.criteria.length > 1 ) {
				this.set('disableRemoveCriteria', false);
			}
		},

		aConditionLess: function(data) {
			this.criteria.removeObject(data);
			if ( this.criteria.length < 16 ) {
				this.set('limitCriteria', false);
			} else {
				this.set('limitCriteria', true);
			}

			if ( this.criteria.length < 2 ) {
				this.set('disableRemoveCriteria', true);
			}
		},
				
		save : function() {
			if (this.get('name') == null) {
				App.set('errormessage', 'El segmento debe tener un nombre, por favor verifique la infomación');
			}
			else {
				if ( this.criteria.length < 1 ) {
				App.set('errormessage', 'El segmento debe tener al menos una condición');
				} 
				else {
					App.set('errormessage', '');
					var JsonCriteria = JSON.stringify(this.criteria);
					this.content.set('criteria', JsonCriteria);
					this.handleSavePromise(this.content.save(), 'segments', 'Se ha creado el segmento existosamente');
				}
			}
		},
		
		reset : function() {
			this.set('model.dbase', null);
			this.set('dbaseSelected', false);
			this.set('criteria', new Ember.A([{}]));
			this.set('disableRemoveCriteria', true);
			this.set('limitCriteria', false);
		},
		
		cancel: function(){
			App.set('errormessage', '');
			this.transitionToRoute('segments');
		}
	}
});

App.SegmentsDeleteController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	actions : {
		delete: function() {
			this.get('model').deleteRecord();
			this.handleSavePromise(this.content.save(), 'segments', 'El Segmento ha sido eliminado con exito!');
		},
		cancel: function(){
//			 this.get("transaction").rollback();
			 this.get("target").transitionTo("segments");
		}
	}
});

App.SegmentsEditController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	criteria: Ember.A(),
			
	cfields: Ember.A([
		{id: 'email', name: 'Correo electronico', type: 'text'},
		{id: 'domain', name: 'Dominio del Correo', type: 'text'},
		{id: 'name', name: 'Nombre', type: 'text'},
		{id: 'lastName', name: 'Apellido', type: 'text'},
	]),
	stdfields: [
		{id: 'email', name: 'Correo electronico', type: 'text'},
		{id: 'domain', name: 'Dominio del Correo', type: 'text'},
		{id: 'name', name: 'Nombre', type: 'text'},
		{id: 'lastName', name: 'Apellido', type: 'text'},
//		{id: 'status', name: 'Estado', type: 'select', options: ['Activo', 'Inactivo', 'Des-suscrito', 'Spam', 'Rebotado']},
	],
			
	setCriteriaArray: function ()
	{
		var idDbase = this.get('dbase.id');
		var y = this;
		var x = this.store.find('field', {dbase: idDbase}).then(function (data) {
				var arr = y.stdfields.slice(0);
				data.forEach(function (item, index) {
					arr.push({id: 'cf_' + item.get('id'), name: item.get('name')});
				});
				y.set('cfields.[]', arr);
				var criteriaJSON = y.get('content.criteria');
				var objJSON = JSON.parse(criteriaJSON);
				var s = y.get('criteria');
				s.clear();
				objJSON.forEach(function (i) {
					s.pushObject(i);
				});
			});
	}.observes('content'),
	
	actions: {
		
		aConditionMore: function() {
			var newobj = {};
			this.criteria.pushObject(newobj);
			if ( this.criteria.length > 15 ) {
				this.set('limitCriteria', true);
			} else {
				this.set('limitCriteria', false);
			}
			if( this.criteria.length > 1) {
				this.set('defaultCriteria', false);
			}			
		},

		aConditionLess: function(data) {
			this.criteria.removeObject(data);
			if ( this.criteria.length < 16 ) {
				this.set('limitCriteria', false);
			} else {
				this.set('limitCriteria', true);
			}
			if( this.criteria.length < 2) {
				this.set('defaultCriteria', true);
			}
		},
		
		edit: function() {
			if (this.get('name') == '') {
				App.set('errormessage', 'El segmento debe tener un nombre, por favor verifique la infomación');
			}
			else {
				if ( this.criteria.length < 1 ) {
					App.set('errormessage', 'El segmento debe tener al menos una condición');
				} 
				else { 
					var JsonCriteria = JSON.stringify(this.criteria);
//					var JsonCriteria = this.criteria;
					this.content.set('criteria', JsonCriteria);
					this.handleSavePromise(this.content.save(), 'segments', 'Se ha editado el segmento existosamente');
				}
			}	
		},
				
		cancel: function() {
			App.set('errormessage', '');
			this.set('criteria', JSON.parse(this.content.get('criteria'))) ;
			this.get('model').rollback();
			this.transitionToRoute("segments");
		}
	}
});