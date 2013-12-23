App = Ember.Application.create({
	rootElement: '#emberAppstatisticsContainer'
});

//Adaptador
//App.ApplicationAdapter = DS.RESTAdapter.extend();

DS.RESTAdapter.reopen({
	namespace: MyDbaseUrl,
	//plurals: {dbase: 'dbasess'}
});

//DS.RESTAdapter.configure('plurals', {
//		dbase: 'dbases'
//	});

// Store (class)
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
App.DrilldownController = Ember.ObjectController.extend();
App.DrilldownIndexController = Ember.ArrayController.extend({});	

App.DrilldownOpensController = Ember.ArrayController.extend({
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		App.set('detailsData', details);
	}
});

App.DrilldownClicksController = Ember.ArrayController.extend({	
	loadDataChart: function() {
		var statistics = JSON.parse(this.get('model').content[0].get('statistics'));
		App.set('chartData', statistics);
	},
	loadDataDetails: function() {
		var details = JSON.parse(this.get('model').content[0].get('details'));
		App.set('detailsData', details);
	}
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
	didInsertElement:function(){

		$('#ChartContainer').append("<div id='" + this.idChart + "' class='time-graph span8'></div>");
		
//		var chartData = App.get('chartData');

//		moment.lang('es');	
//		var m = moment.unix(moment().unix() - 86400);
//		console.log(m.format("MM-DD-YYYY"));		
		var chartData = createChartData('MM');
		
		if(this.typeChart === 'Pie') {
			chart = createPieChart(chartData);
		}
		else if(this.typeChart === 'Bar') {
			chart = createBarChart(null, chartData);
		}
		else if(this.typeChart === 'Line') {
			chart = createLineChart(null, chartData, "MM");
		}
		else if(this.typeChart === 'LineStep') {
			chart = createLineStepChart(null, chartData, "MM");
		}

		chart.write(this.idChart);
	},
			
	changeScale: function()	{
		var scale = App.get('scaleSelected');
		console.log(scale)
		if(scale !== null) {
			switch(scale) {
				case 'hh':
					var chartData = createChartData('MM-DD HH:mm');
					chart.removeGraph(chart.graphs[0]);
					chart = createLineStepChart(chart, chartData, 'MM-DD JJ:NN');
					break;
				case 'DD':
					chart.removeGraph(chart.graphs[0]);
					chart = createLineStepChart(chart, chartData, 'MM-DD');
					var chartData = createChartData('MM-DD');
					break;
				case 'MM':
					chart.removeGraph(chart.graphs[0]);
					chart = createBarChart(chart, chartData);
					var chartData = createChartData('MM');
					break;
			}
			
			var categoryAxis = chart.categoryAxis;
			categoryAxis.minPeriod = scale;
			chart.dataProvider = chartData;
			chart.validateData();
			chart.animateAgain();
		}
		
	}.observes('App.scaleSelected'),		
			
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
