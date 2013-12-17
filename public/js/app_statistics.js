App.set('errormessage', '');

App.Openstatistic = DS.Model.extend({
	title: DS.attr('string'),
	value: DS.attr('string')
});

App.Opendetaillist = DS.Model.extend({
	email: DS.attr('string'),
	date: DS.attr('string'),
	os: DS.attr('string')
});

App.Router.map(function() {
  this.resource('drilldown', function(){
	  this.route('opens');
  });
});

/*Routes*/

App.DrilldownIndexRoute = Ember.Route.extend({
  
});

App.DrilldownOpensRoute = Ember.Route.extend({
	model: function () {
		return this.store.find('opendetaillist');		
	}
});

/*Controllers*/
App.DrilldownController = Ember.ObjectController.extend();
App.DrilldownIndexController = Ember.ArrayController.extend({	
  
});	
App.DrilldownOpensController = Ember.ArrayController.extend({	
	init: function() {
		this.store.find('openstatistic').then(function(statistic) {
			var values =[];
			var all = statistic.get('content');
			for(var i = 0; i < all.length; i++) {
				var obj = {title: all[i].get('title'), value: all[i].get('value')};
				values.push(obj);
			}
			App.set('chartData', values);
		});
	}
});

App.DrilldownIndexController = Ember.ArrayController.extend({	
  
});

App.DrilldownTimeGraphView = Ember.View.extend({
	templateName:"timeGraph",
	drawChart:function(){
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