App.set('errormessage', '');

App.Dbasestatistic = DS.Model.extend({
	statistics: DS.attr('string'),
	details: DS.attr('string')
});

App.Contactliststatistic = DS.Model.extend({
	statistics: DS.attr('string'),
	details: DS.attr('string')
});

App.Router.map(function() {
  this.resource('dbasestatistic', function(){
	  this.route('opens'),
	  this.route('clicks')
  });
  
  this.resource('contactliststatistic', function(){
	  this.route('opens'),
	  this.route('clicks')
  });
});

/*Routes*/

App.DbasestatisticIndexRoute = Ember.Route.extend({});

App.DbasestatisticOpensRoute = Ember.Route.extend({
	model: function () {
		//return this.store.find('dbasestatistic');	
		console.log(this.store.find('dbasestatistic'))
	},
//	setupController: function(controller, model) {
//		controller.set('model', model);
//		controller.loadDataChart();
//	}
});

App.DbasestatisticClicksRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('dbasestatistic');		
	},
	setupController: function(controller, model) {
		controller.set('model', model);
		controller.loadDataChart();
	}
});

/*Contact list statistic routes*/

App.ContactliststatisticIndexRoute = Ember.Route.extend({});

App.ContactliststatisticOpensRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('contactliststatistic');	
		//console.log(this.store.find('contactliststatistic'))
	},
//	setupController: function(controller, model) {
//		controller.set('model', model);
//		controller.loadDataChart();
//	}
});
/*Controllers*/
App.DbasestatisticController = Ember.ObjectController.extend();
App.DbasestatisticController = Ember.ArrayController.extend({});	

App.DbasestatisticOpensController = Ember.ArrayController.extend({
	loadDataChart: function() {
		this.store.find('openstatistic').then(function(statistic) {
			var values = getContent(statistic);
			App.set('chartData', values);
		});
	}
});

App.DbasestatisticClicksController = Ember.ArrayController.extend({	
	loadDataChart: function() {
		this.store.find('clickstatistic').then(function(statistic) {
		   var values = getContent(statistic);
			App.set('chartData', values);
		});
	}
});

/*Contact list statistic controllers*/
App.ContactliststatisticController = Ember.ObjectController.extend();

App.ContactliststatisticOpensController = Ember.ArrayController.extend({
	loadDataChart: function() {
		this.store.find('openstatistic').then(function(statistic) {
			var values = getContent(statistic);
			App.set('chartData', values);
		});
	}
});


function getContent(statistic)
{
	var values =[];
	var all = statistic.get('content');
	for(var i = 0; i < all.length; i++) {
		var obj = {title: all[i].get('title'), value: all[i].get('value')};
		values.push(obj);
	}
	return values;
}

App.DrilldownTimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	drawChart:function(){
		$('#ChartContainer').append("<div id='" + this.idChart + "' class='time-graph span8'></div>");	

		var chartData = App.get('chartData');

		if(this.typeChart === 'Pie') {
			chart = createPieChart(chartData);
		}
		else if(this.typeChart === 'Bar') {
			chart = createBarChart(chartData);
		}

		chart.write(this.idChart);

  }.observes('App.chartData')
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