App.Segment = DS.Model.extend({
	name: DS.attr('string'),
	description: DS.attr('string'),
	criteria: DS.attr('string'),
	customField: DS.attr('number'),
	dbase: DS.hasMany('dbase'),
});

App.criteria = [
  Ember.Object.create({criterion: "todas las", id: "all"}),
  Ember.Object.create({criterion: "cualquiera de las",    id: "any"})
];

App.relations = [
  Ember.Object.create({relation: "Es", id: "equals"}),
  Ember.Object.create({relation: "Contiene",    id: "content"}),
  Ember.Object.create({relation: "No contiene",    id: "!content"}),
  Ember.Object.create({relation: "Empieza con",    id: "Begins"}),
  Ember.Object.create({relation: "Termina en",    id: "Ends"})
];

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
		controller.set('dbases', this.controllerFor('dbase').find());
	}
});

//Definiendo controladores

App.SegementController = Ember.ObjectController.extend();

App.SegementsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : App.List
});

App.SegmentsNewController = Ember.ObjectController.extend({
    aConditionMore: function() {
      this.set('isMore', true);
    },

    aConditionLess: function() {
      this.set('isMore', false);
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

App.DbaseSelect = Ember.Select.extend({
	change: function(evt) {
		var idDbase;
		console.log(idDbase = this.get('value'));
		console.log(App.customField = App.customFieldsArray[idDbase]);
		
		App.customFields.length = 0;
		
		if (App.customField != null) {
			for(var i = 0; i < 1; i++) {
				console.log(App.customField['idCustomField']);
				console.log(App.customField['name']);
				console.log(App.customField['type']);
				var obj = Ember.Object.create({customField: App.customField['name'], id: App.customField['idCustomField']});
				App.customFields.push(obj);
			}
		}
		
		
	}
});

App.customFields = [
		Ember.Object.create({customField: "Email", id: "email"}),
		Ember.Object.create({customField: "Nombre", id: "nombre"}),
		Ember.Object.create({customField: "Apellido", id: "apellido"})
];	