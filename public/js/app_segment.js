App.Segment = DS.Model.extend({
	name: DS.attr('string'),
	description: DS.attr('string'),
	criteria: DS.attr('string'),
	customField: DS.attr('number'),
	dbase: DS.attr('number')
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
	modelClass : App.Segment
});

App.SegmentsNewRoute = Ember.Route.extend({
	model: function(){
		return App.Segment.createRecord();
	}
});

//Definiendo controladores

App.SegementController = Ember.ObjectController.extend();

App.SegementsIndexController = Ember.ArrayController.extend(Ember.MixinPagination,{
	modelClass : App.List
});

App.SegmentsNewController = Ember.ObjectController.extend({
	changed: function() {
		
	},
    pressed: function() {
      this.set('isPressed', true);
    },

    contract: function() {
      this.set('isExpanded', false);
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
		
		if (App.customField != null) {
			for(var i = 0; i < 1; i++) {
				console.log(App.customField['idCustomField']);
				console.log(App.customField['name']);
			}
		}
		App.customFields = [
  Ember.Object.create({customField: "Texto", id: "Text"}),
];
		
	}
});


		