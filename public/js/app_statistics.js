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
		var type = this.get('bouncedFilter');
		this.type = type;
		this.filter = filter;
		var obj = {type: type, filter: filter};
		this.store.find(this.modelClass, obj).then(function(info) {
			var data = info.get('content');
			t.set('detailsData', JSON.parse(data[0].get('details')));
		});		
    }.observes('typeSelected'),
			
	filterSelectChange: function () {
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
		
	}.observes('bouncedFilter')
});


App.scaleSelected = null;

App.chartScale = [
	"Hora", "Dia", "Mes", "Año"
];

App.TimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	chart: null,
	didInsertElement:function(){
		$('#ChartContainer').append("<div id='" + this.idChart + "' class='time-graph span8'></div>");
		try{
			createBarHighChart(this.idChart, App.get('chartData'));
		}
		catch(err){
			console.log(err.message);
		}
	},
			
	changeScale: function()	{
		var scale = App.get('scaleSelected');
		removeLastChart(chart);
		switch(scale) {
			case 'hh':
				var chartData = createChartData(App.get('chartData'), App.get('multValChart'), 'YYYY-MM-DD HH:mm');
				chart = createLineStepChart(chart, chartData, 'YYYY-MM-DD JJ:NN', 'hh', this.text, App.get('multValChart'));
				break;
			case 'DD':
				var chartData = createChartData(App.get('chartData'), App.get('multValChart'), 'YYYY-MM-DD');
				chart = createLineChart(chart, chartData, 'YYYY-MM-DD', 'DD', this.text, App.get('multValChart'));
				break;
			case 'MM':
			default:
				var chartData = createChartData(App.get('chartData'), App.get('multValChart'), 'YYYY-MM');
				chart = createBarChart(chart, chartData, 'YYYY-MM', 'MM', this.text, App.get('multValChart'));
				break;
		}
		chart.validateData();
		chart.animateAgain();
	}.observes('App.scaleSelected')		
			
});

function removeLastChart(chart) {
	chart.removeGraph(chart.graphs[0]);
	chart.removeValueAxis(chart.valueAxes[0]);
	chart.removeChartCursor();
	chart.removeChartScrollbar();
	chart.removeLegend();
}
