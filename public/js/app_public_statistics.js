App = Ember.Application.create({
	rootElement: '#emberAppstatisticsContainer'
});

DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl
});

App.Store = DS.Store.extend({});

App.set('errormessage', '');
App.set('chartData', '');

Ember.RadioButton = Ember.View.extend({
	tagName : "input",
	type : "radio",
	attributeBindings : [ "name", "type", "value", "checked:checked:" ],
	click : function() {
		this.set("selection", this.$().val());
	},
	
	checked : function() {
		return this.get("value") === this.get("selection");   
	}.property()
});



App.Drilldownopen = DS.Model.extend({
	statistics: DS.attr('string')
});

App.Drilldownclick = DS.Model.extend({
	statistics: DS.attr('string'),
	links: DS.attr('string')
});

App.Drilldownunsubscribed = DS.Model.extend({
	statistics: DS.attr('string')
});

App.Drilldownbounced = DS.Model.extend({
	statistics: DS.attr('string')
});

App.Drilldownspam = DS.Model.extend({
	statistics: DS.attr('string')
});

App.Router.map(function() {
  this.resource('drilldown', function(){
	  this.route('opens'),
	  this.route('clicks'),
	  this.route('unsubscribed'),
	  this.route('spam'),
	  this.route('bounced');
  });
});

/*Routes*/

App.DrilldownIndexRoute = Ember.Route.extend({});

App.DrilldownOpensRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownopen');	
	},
	deactivate: function () {
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
	}
});

App.DrilldownClicksRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownclick');		
	},
	deactivate: function () {
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
    }
});

App.DrilldownUnsubscribedRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownunsubscribed');	
	},
	deactivate: function () {
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
	}
});

App.DrilldownSpamRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownspam');	
	},
	deactivate: function () {
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
	}
});

App.DrilldownBouncedRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownbounced');	
	},
	deactivate: function () {
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
	}
});


/*Controllers*/
App.DrilldownController = Ember.ObjectController.extend({
	init: function() {
//		console.log($('#select-options-for-compare'))
	}
});

App.DrilldownIndexController = Ember.ArrayController.extend({});	

App.DrilldownOpensController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {
	modelClass : App.Drilldownopen,
			
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
		App.set('title', 'Estadisticas de apertura');
		App.set('subtitle', 'Cantidad de aperturas');
		App.set('ref', 'Apertura(s)');
	}
});

App.DrilldownClicksController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {
	modelClass : App.Drilldownclick,
			
	selectedLink: [],
	linkSelected: null,
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
		App.set('title', 'Estadisticas de clics (únicos)');
		App.set('subtitle', 'Cantidad de clics');
		App.set('ref', 'Clic(s) únicos');
	},
	loadDataDetails: function() {
		var links = JSON.parse(this.get('model').content[0].get('links'));
		this.set('detailsLinks', links);	
	}
});

App.DrilldownUnsubscribedController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {
	modelClass : App.Drilldownunsubscribed,	
			
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	}
});

App.DrilldownSpamController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {	
	modelClass : App.Drilldownspam,
				
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	}
});

App.DrilldownBouncedController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {	
	modelClass : App.Drilldownbounced,
				
	selectedType: ['Todos', 'soft', 'hard'],
	typeSelected: null,
	bouncedFilter: null,
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
		App.set('title', 'Estadisticas de rebotes');
		App.set('subtitle', 'Vea el detalle de los rebotes suaves y duros.');
	},
	bouncedData: function () {
		var data = App.get('chartData');
		
		if (data[0].hard !== 0 || data[0].soft !== 0) {
			return true;
		}
		return false;
	}.property('this.statistics')
});


App.scaleSelected = null;

App.TimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	chart: null,
	didInsertElement:function(){
		try{
			var data = App.get('chartData');
			if (data.length !== 0 && data !== undefined && data !== null) {
				var title = App.get('title');
				var subtitle = App.get('subtitle');
				$('#ChartContainer').append("<div id='" + this.idChart + "' class='col-sm-12'></div>");
				if (this.typeChart === 'bar-drilldown') {
					createBarHighChart(this.idChart, data, title, subtitle, App.get('ref'));
				}
				else if (this.typeChart === 'pie-basic') {
					var newdata = modelDataForPie(data);
					createHighPieChart(this.idChart, newdata, title, subtitle);
				}
			}
		}
		catch(err){
			console.log(err.message);
		}
	}			
});

function modelDataForPie(rawData) {
	var data = [];
	
	if (rawData[0].hard !== 0 || rawData[0].soft !== 0) {
		var soft = new Object;
		soft.name = 'Rebotes duros';
		soft.y = rawData[0].hard;
		soft.color = '#f26522';

		var hard = new Object;
		hard.name = 'Rebotes suaves';
		hard.y = rawData[0].soft;
		hard.color = '#f7941d';

		data = [hard, soft];
	}
	
	return data;
}

function setExpandAttr(self, expand) {
	if(self.get(expand)) {
		self.set(expand, false);
	}
	else {
		self.set(expand, true);
	}
}


