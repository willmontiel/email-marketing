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
  Ember.Object.create({relation: "Termina en",    id: "ends"})
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

//Definiendo controladores

App.SegmentController = Ember.ObjectController.extend();

App.SegmentsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : App.List
});

App.SegmentsNewController = Ember.ObjectController.extend(Ember.SaveHandlerMixin, {
	
	criteria: Ember.A([{}]),		
	
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
		console.log(this.get('limitCriteria'));
	},
	
	changeDbase: function () {
		if (this.content.get('dbase')) {
			var s = this;
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
			if ( this.criteria.length > 5 ) {
				this.set('limitCriteria', true);
			} else {
				this.set('limitCriteria', false);
			}
			
		},

		aConditionLess: function(data) {
			this.criteria.removeObject(data);
			if ( this.criteria.length < 6 ) {
				this.set('limitCriteria', false);
			} else {
				this.set('limitCriteria', true);
			}
		},
				
		save : function() {
			var JsonCriteria = JSON.stringify(this.criteria);
			this.content.set('criteria', JsonCriteria);
			this.handleSavePromise(this.content.save(), 'segments', 'Se ha creado el segmento existosamente');
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
			 this.get("transaction").rollback();
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
			});
		var criteriaJSON = this.get('content.criteria');
		var objJSON = JSON.parse(criteriaJSON);
		var s = this.get('criteria');
		s.clear();
		objJSON.forEach(function (i) {
			s.pushObject(i);
		});
	}.observes('content'),
	
	actions: {
		edit: function() {
			this.handleSavePromise(this.content.save(), 'segments', 'Se ha editado el segmento existosamente');
		},
				
		cancel: function() {
			this.get('model').rollback();
			this.transitionToRoute("segments");
		}
	}
});
