App.Segment = DS.Model.extend({
	name: DS.attr('string'),
	description: DS.attr('string'),
	idDbase: DS.attr('number'),
	
	isDbaseSelected: function() {
		return true;
	}.property('criteria')
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
	getModelMetadata: function() {
		return App.store.typeMapFor(App.Blockedemail);
	},
	
	refreshModel: function (obj) {
		var result = App.Blockedemail.find(obj);
		this.set('content', result);
	}
});

App.SegmentsNewController = Ember.ObjectController.extend({
    pressed: function() {
      this.set('isPressed', true);
    },

    contract: function() {
      this.set('isExpanded', false);
    }
});
