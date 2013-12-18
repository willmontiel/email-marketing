App.set('errormessage', '');
App.set('chartData', '');

App.Drilldowndbase = DS.Model.extend({
	details: DS.attr('string'),
	statistics: DS.attr('string'),

});

App.Contactliststatistic = DS.Model.extend({
	statistics: DS.attr('string'),
	details: DS.attr('string')
});

App.Router.map(function() {
  this.resource('drilldowndbase', function(){
	  this.route('opens'),
	  this.route('clicks'),
	  this.route('unsubscribed')
  });
});

/*Routes*/

App.DrilldowndbaseIndexRoute = Ember.Route.extend({});

App.DrilldowndbaseOpensRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldowndbase');	
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});

App.DrilldowndbaseClicksRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldowndbase');		
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});

App.DrilldowndbaseUnsubscribedRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('drilldowndbase');	
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
		controller.loadDataDetails();
	}
});


/*Controllers*/
App.DrilldowndbaseController = Ember.ObjectController.extend();
App.DrilldowndbaseIndexController = Ember.ArrayController.extend({});	

App.DrilldowndbaseOpensController = Ember.ArrayController.extend({
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics')).opens;
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details')).opens;
		App.set('detailsData', details);
	}
});

App.DrilldowndbaseClicksController = Ember.ArrayController.extend({	
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics')).clicks;
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details')).clicks;
		App.set('detailsData', details);
	}
});

App.DrilldowndbaseUnsubscribedController = Ember.ArrayController.extend({	
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics')).unsubscribed;
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details')).unsubscribed;
		App.set('detailsData', details);
	}
});

App.TimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	didInsertElement:function(){
		$('#ChartContainer').append("<div id='" + this.idChart + "' class='time-graph span8'></div>");	

		var chartData = App.get('chartData');

		var chart;

		if(this.typeChart === 'Pie') {
			chart = createPieChart(chartData);
		}
		else if(this.typeChart === 'Bar') {
			chart = createBarChart(chartData);
		}

		chart.write(this.idChart);
	}
});

function createBarChart(chartData) {
	var chart = new AmCharts.AmSerialChart();
	chart.dataProvider = chartData;
	chart.categoryField = "title";
	chart.startDuration = 1;

	var graph = new AmCharts.AmGraph();
	graph.valueField = "value";
	graph.type = "column";
	graph.title = "Aperturas de correo 2013";
	graph.lineColor = "#000000";
	graph.fillColors = "#6eb056";
	graph.fillAlphas = 0.7;
	graph.balloonText = "<span style='font-size:13px;'>Aperturas de correo 2013 en [[category]]:<b>[[value]]</b></span>";
	chart.addGraph(graph);

	// LEGEND
	var legend = new AmCharts.AmLegend();
	legend.useGraphSettings = true;
	chart.addLegend(legend);
	
	return chart;
}

function createPieChart(chartData) {
	var chart = new AmCharts.AmPieChart();
	chart.dataProvider = chartData;
	chart.titleField = "title";
	chart.valueField = "value";

	chart.sequencedAnimation = true;
	chart.startEffect = "easeOutSine";
	chart.innerRadius = "40%";
	chart.startDuration = 1;
	chart.labelRadius = 2;
	chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
	// this makes the chart 3D
	chart.depth3D = 10;
	chart.angle = 15;
	
	return chart;
}
