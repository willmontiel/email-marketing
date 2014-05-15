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
	details: DS.attr('string'),
	statistics: DS.attr('string')
});

App.Drilldownclick = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
	links: DS.attr('string'),
	multvalchart: DS.attr('string')
});

App.Drilldownunsubscribed = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string')
});

App.Drilldownbounced = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
	multvalchart: DS.attr('string')
});

App.Drilldownspam = DS.Model.extend({
	details: DS.attr('string'),
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
		App.set('scaleSelected', null);
		App.set('multValChart', null);
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});

App.DrilldownClicksRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownclick');		
	},
	deactivate: function () {
		App.set('scaleSelected', null);
		App.set('multValChart', null);
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
		App.set('scaleSelected', null);
		App.set('multValChart', null);
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});

App.DrilldownSpamRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownspam');	
	},
	deactivate: function () {
		App.set('scaleSelected', null);
		App.set('multValChart', null);
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});

App.DrilldownBouncedRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownbounced');	
	},
	deactivate: function () {
		App.set('scaleSelected', null);
		App.set('multValChart', null);
		App.set('chartData', null);
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
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
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		this.set('detailsData', details);
	}
});

App.DrilldownClicksController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {
	modelClass : App.Drilldownclick,
			
	selectedLink: [],
	linkSelected: null,
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		var info = JSON.parse(this.get('model').content[0].get('multvalchart'));
		info = info.length > 0 ? info : null;
		App.set('chartData', statistics);
		App.set('title', 'Estadisticas de clics (únicos)');
		App.set('subtitle', 'Cantidad de clics');
		App.set('ref', 'Clic(s) únicos');
		App.set('multValChart', info);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		this.set('allDetailsData', details);
		this.set('detailsData', details);
		var links = JSON.parse(this.get('model').content[0].get('links'));
		this.set('detailsLinks', links);
		
		this.selectedLink = ['Todos'];
		for(var i = 0; i < links.length; i++) {
			this.selectedLink.push(links[i].link);
		}
	},
	linkSelectChange: function () {	
		var t = this;
		var link = this.get('linkSelected');
		this.filter = link;
		var obj = {filter: link};
		this.store.find(this.modelClass, obj).then(function(info) {
			var data = info.get('content');
			t.set('detailsData', JSON.parse(data[0].get('details')));
		});
    }.observes('linkSelected')
});

App.DrilldownUnsubscribedController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {
	modelClass : App.Drilldownunsubscribed,	
			
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		this.set('detailsData', details);
	}
});

App.DrilldownSpamController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {	
	modelClass : App.Drilldownspam,
				
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		this.set('detailsData', details);
	}
});

App.DrilldownBouncedController = Ember.ArrayController.extend(Ember.MixinPaginationStatistics, {	
	modelClass : App.Drilldownbounced,
				
	selectedType: ['Todos', 'soft', 'hard'],
	typeSelected: null,
	bouncedFilter: null,
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		var info = JSON.parse(this.get('model').content[0].get('multvalchart'));
		App.set('chartData', statistics);
		App.set('title', 'Estadisticas de rebotes');
		App.set('subtitle', 'Vea el detalle de los rebotes suaves y duros.');
		App.set('multValChart', info);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		this.set('allDetailsData', details);
		this.set('detailsData', details);
		this.set('bouncedFilter', null);
		this.set('typeSelected', null);
	},
	typeSelectChange: function () {	
		var t = this;
		var filter = (this.get('typeSelected') !== undefined) ? this.get('typeSelected') : 'Todos';
		var type = (this.get('bouncedFilter') !== null) ? this.get('bouncedFilter') : 'type';
		this.type = type;
		this.filter = filter;
		var obj = {type: type, filter: filter};
		this.store.find(this.modelClass, obj).then(function(info) {
			var data = info.get('content');
			t.set('detailsData', JSON.parse(data[0].get('details')));
		});		
    }.observes('typeSelected'),
			
	filterSelectChange: function () {
		var t = this;
		var bouncedFilter = this.get('bouncedFilter');
		var filters = App.get('multValChart');
		var objArray = [];
		objArray.push('Todos');
		switch (bouncedFilter) {
			case 'category':
				for(var i = 0; i < filters[0].category.length; i++) {
					objArray.push(filters[0].category[i]);
				}
				break;
			case 'domain':
				for(var i = 0; i < filters[0].domain.length; i++) {
					objArray.push(filters[0].domain[i]);
				}
				break;
			case 'type':
			default:
				objArray.push('soft', 'hard');
				break;
		}
		this.set('selectedType', objArray);
		
		this.set('typeSelected', 'Todos');
		var obj = {type: bouncedFilter, filter: 'Todos'};
		this.store.find(this.modelClass, obj).then(function(info) {
			var data = info.get('content');
			t.set('detailsData', JSON.parse(data[0].get('details')));
		});	
		
	}.observes('bouncedFilter')
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

function removeLastChart(chart) {
	chart.removeGraph(chart.graphs[0]);
	chart.removeValueAxis(chart.valueAxes[0]);
	chart.removeChartCursor();
	chart.removeChartScrollbar();
	chart.removeLegend();
}

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