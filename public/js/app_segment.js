App.Segment = DS.Model.extend({
	name: DS.attr('string'),
	description: DS.attr('string'),
	criteria: DS.attr('string'),
	customField: DS.attr('number'),
	dbase: DS.belongsTo('dbase'),
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
	name: DS.attr('string', { required: true }),
	type: DS.attr( 'string' ),
	required: DS.attr('boolean'),
	values: DS.attr('string'),
	defaultValue: DS.attr('string'),
	minValue: DS.attr('number'),
	maxValue: DS.attr('number'),
	maxLength: DS.attr('number'),
	becameError: function() {
		return alert('there was an error!');
	},
	becameInvalid: function(errors) {
		return alert("Record was invalid because: " + errors);
	},
	isSelect: function() {
		return (this.get('type') == "Select" || this.get('type') == "MultiSelect");
	}.property('type'),
			
	isText: function() {
		return (this.get('type') == "Text");
	}.property('type'),
	
	isNumerical: function() {
		return (this.get('type') == "Numerical");
	}.property('type'),
			
	isDate: function() {
		return (this.get('type') == "Date");
	}.property('type')
});


//Definiendo rutas

App.SegmentsIndexRoute = Ember.Route.extend({
	modelClass : 'segment'
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

//Definiendo controladores

App.SegmentController = Ember.ObjectController.extend();

App.SegmentsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : App.List
});

App.SegmentsNewController = Ember.ObjectController.extend({
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
	
	changeDbase: function () {
		console.log('Dbase changed to: ' + this.content.get('dbase.id') + ', ' + this.content.get('dbase.name'));
		if (this.content.get('dbase')) {
			var s = this;
			// Cargar lista de custom fields
			var cfs = this.store.find('field', {dbase: this.get('content.dbase.id')}).then(function (data) {
				var arr = s.stdfields.slice(0);
				
				console.log('Loaded!');
				console.log(data);
				
				data.forEach(function (item, index) {
					console.log('Item: ' + index);
					console.log(item.get('id') + ', ' + item.get('name'));
					arr.push({id: 'cf_' + item.get('id'), name: item.get('name')});
				});
				s.set('cfields.[]', arr);

			});
		}
	}.observes('content.dbase'),
	
	actions: {
		aConditionMore: function() {
		  this.set('isMore', true);
		},

		aConditionLess: function() {
		  this.set('isMore', false);
		}
	}
});

App.SegmentsDeleteController = Ember.ObjectController.extend({
	delete: function() {
		this.get('content').deleteRecord();
		this.get('model.transaction').commit();
		this.get("target").transitionTo("segments");
    },
	cancel: function(){
		 this.get("transaction").rollback();
		 this.get("target").transitionTo("segments");
	}
});
