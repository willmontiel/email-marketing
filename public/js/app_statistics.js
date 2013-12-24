App = Ember.Application.create({
	rootElement: '#emberAppstatisticsContainer'
});

DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
});

App.Store = DS.Store.extend({});

App.set('errormessage', '');
App.set('chartData', '');


Ember.RadioButton = Ember.View.extend({
    tagName : "input",
    type : "radio",
    attributeBindings : [ "name", "type", "value", "checked:checked:" ],
    click : function() {
        this.set("selection", this.$().val())
    },
    checked : function() {
        return this.get("value") == this.get("selection");   
    }.property()
});

App.Drilldownopen = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
});

App.Drilldownclick = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
	links: DS.attr('string')
});

App.Drilldownunsubscribed = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
});

App.Drilldownbounced = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
});

App.Drilldownspam = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),
});

App.Router.map(function() {
  this.resource('drilldown', function(){
	  this.route('opens'),
	  this.route('clicks'),
	  this.route('unsubscribed')
  });
});

/*Routes*/

App.DrilldownIndexRoute = Ember.Route.extend({});

App.DrilldownOpensRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldownopen');	
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
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});


/*Controllers*/
App.DrilldownController = Ember.ObjectController.extend({});
App.DrilldownIndexController = Ember.ArrayController.extend({
	compareDbases: function() {
		
	}
});	

App.DrilldownOpensController = Ember.ArrayController.extend({
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
		App.set('scaleSelected', null)
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		App.set('detailsData', details);
	}
});

App.DrilldownClicksController = Ember.ArrayController.extend({
	selectedLink: [],
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('clicksData', statistics);
		var dataByLink = groupByLink(statistics[0].link);
		App.set('chartData', dataByLink);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		App.set('detailsData', details);
		
		var links = JSON.parse(this.get('model').content[0].get('links'));
		App.set('detailsLinks', links);
		
		this.selectedLink = [];
		App.set('linkSelected', App.get('clicksData')[0].link);
		for(var i = 0; i < links.length; i++) {
			this.selectedLink.push(links[i].link);
		}
	},
	linkSelectChange: function () {	
		var link = App.get('linkSelected');
		var dataByLink = groupByLink(link);
		App.set('chartData', dataByLink);
    }.observes('App.linkSelected')
});

App.DrilldownUnsubscribedController = Ember.ArrayController.extend({	
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		App.set('detailsData', details);
	}
});

App.scaleSelected = null;

App.chartScale = [
	"Hora", "Dia", "Mes", "Año"
];

App.TimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	chart: null,
	texto: null,
	didInsertElement:function(){

		$('#ChartContainer').append("<div id='" + this.idChart + "' class='time-graph span8'></div>");
		
		var chartData = createChartData('YYYY-MM');
		
		if(this.text == null) {
			this.text = this.textChart;
		}
		
		if(this.typeChart === 'Pie') {
			chart = createPieChart(chartData);
		}
		else if(this.typeChart === 'Bar') {
			chart = createBarChart(null, chartData, 'YYYY-MM', 'MM', this.text);
		}
		else if(this.typeChart === 'Line') {
			chart = createLineChart(null, chartData, 'YYYY-MM', 'MM', this.text);
		}
		else if(this.typeChart === 'LineStep') {
			chart = createLineStepChart(null, chartData, 'YYYY-MM', 'MM', this.text);
		}

		chart.write(this.idChart);
	},
			
	changeScale: function()	{
		var scale = App.get('scaleSelected');
		removeLastChart(chart);
		switch(scale) {
			case 'hh':
				var chartData = createChartData('YYYY-MM-DD HH:mm');
				chart = createLineStepChart(chart, chartData, 'YYYY-MM-DD JJ:NN', 'hh', this.text);
				break;
			case 'DD':
				var chartData = createChartData('YYYY-MM-DD');
				chart = createLineChart(chart, chartData, 'YYYY-MM-DD', 'DD', this.text);
				break;
			case 'MM':
			default:
				var chartData = createChartData('YYYY-MM');
				chart = createBarChart(chart, chartData, 'YYYY-MM', 'MM', this.text);
				break;
		}

		chart.validateData();
		chart.animateAgain();
		
	}.observes('App.scaleSelected', 'App.linkSelected'),		
			
});

function createChartData(format) {
	
	var totalData = App.get('chartData');
	var newData = [];
	var result = [];
	
	for(var i = 0; i < totalData.length; i++) {
		if(newData[(moment.unix(totalData[i].title)).format(format)] === undefined) {
			newData[(moment.unix(totalData[i].title)).format(format)] = 0;
		}
		newData[(moment.unix(totalData[i].title)).format(format)]+= totalData[i].value;
	}

	for (var key in newData) {
		if(newData.hasOwnProperty(key)) {
			var obj = new Object();
			obj.title = '' + key;
			obj.value = '' + newData[key];
			result.push(obj);
		}
	}
	return result;
}

function removeLastChart(chart) {
	chart.removeGraph(chart.graphs[0]);
	chart.removeValueAxis(chart.valueAxes[0]);
	chart.removeChartCursor();
	chart.removeChartScrollbar();
	chart.removeLegend();
}

function groupByLink(link) {
	var statistics = App.get('clicksData');
	var arrayObj = [];
	for(var i=0; i < statistics.length; i++) {
		if(statistics[i].link == link) {
			var obj = new Object();
			obj.title = '' + statistics[i].title;
			obj.value = statistics[i].value;
			arrayObj.push(obj);
		}
	}
	return arrayObj;
}
