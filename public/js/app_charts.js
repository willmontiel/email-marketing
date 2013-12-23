function createBarChart(chart, chartData, dateFormat, minPeriod) {
	if(chart == undefined || chart == null) {
		chart = new AmCharts.AmSerialChart();
	}
	chart.dataProvider = chartData;
	chart.categoryField = "title";
	chart.startDuration = 1;
	chart.dataDateFormat = dateFormat;
	
	var categoryAxis = chart.categoryAxis;
	categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
	categoryAxis.minPeriod = minPeriod; // our data is daily, so we set minPeriod to DD
	categoryAxis.axisColor = "#DADADA";
	
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

function createLineChart(chart, chartData, dateFormat, minPeriod) {
	if(chart == undefined || chart == null) {
		chart = new AmCharts.AmSerialChart();
	}
	chart.pathToImages = "../../amcharts/images/";
	chart.dataProvider = chartData;
	chart.categoryField = "title";
	chart.dataDateFormat = dateFormat;

	// AXES
	// category
	var categoryAxis = chart.categoryAxis;
	categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
	categoryAxis.minPeriod = minPeriod; // our data is daily, so we set minPeriod to DD
	categoryAxis.dashLength = 1;
	categoryAxis.gridAlpha = 0.15;
	categoryAxis.axisColor = "#DADADA";

	// value
	var valueAxis = new AmCharts.ValueAxis();
	valueAxis.axisColor = "#DADADA";
	valueAxis.dashLength = 1;
	chart.addValueAxis(valueAxis);
	
	// GRAPH
	var graph = new AmCharts.AmGraph();
	graph.type = "line";
	graph.bullet = "round";
	graph.bulletColor = "#FFFFFF";
	graph.useLineColorForBulletBorder = true;
	graph.bulletBorderAlpha = 1;
	graph.bulletBorderThickness = 2;
	graph.bulletSize = 7;
	graph.title = "Value";
	graph.valueField = "value";
	graph.lineThickness = 2;
	graph.lineColor = "#00BBCC";
	chart.addGraph(graph);

	// CURSOR
	var chartCursor = new AmCharts.ChartCursor();
	chartCursor.cursorPosition = "mouse";
	chart.addChartCursor(chartCursor);

	// SCROLLBAR
	var chartScrollbar = new AmCharts.ChartScrollbar();
	chartScrollbar.scrollbarHeight = 30;
	chart.addChartScrollbar(chartScrollbar);
	
	// LEGEND
	var legend = new AmCharts.AmLegend();
	legend.useGraphSettings = true;
	chart.addLegend(legend);
	
	return chart;
}

function createLineStepChart(chart, chartData, dateFormat, minPeriod){
	if(chart == undefined || chart == null) {
		chart = new AmCharts.AmSerialChart();
	}
	chart.pathToImages = "../../amcharts/images/";
	chart.marginRight = 10;
	chart.dataProvider = chartData;
	chart.categoryField = "title";
	chart.dataDateFormat = dateFormat;

	// AXES
	// Category
	var categoryAxis = chart.categoryAxis;
	categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
	categoryAxis.minPeriod = minPeriod; // our data is yearly, so we set minPeriod to YYYY
	categoryAxis.minorGridEnabled = true;
	categoryAxis.minorGridAlpha = 0.15;

	// VALUE
	var valueAxis = new AmCharts.ValueAxis();
	valueAxis.gridAlpha = 0;
	valueAxis.axisAlpha = 0;
	valueAxis.fillColor = "#000000";
	valueAxis.fillAlpha = 0.05;
	valueAxis.inside = true;
	chart.addValueAxis(valueAxis);

	// GRAPH                 
	var graph = new AmCharts.AmGraph();
	graph.type = "step"; // this line makes step graph
	graph.valueField = "value";
	graph.lineColor = "#000000";
	graph.balloonText = "[[category]]<br><b><span style='font-size:14px;'>[[value]] C</span></b>";
	chart.addGraph(graph);

	// CURSOR
	var chartCursor = new AmCharts.ChartCursor();
	chartCursor.cursorAlpha = 0;
	chartCursor.cursorPosition = "mouse";
	chartCursor.categoryBalloonDateFormat = "YYYY";
	chart.addChartCursor(chartCursor);

	// SCROLLBAR
	var chartScrollbar = new AmCharts.ChartScrollbar();
	chart.addChartScrollbar(chartScrollbar);
	
	// LEGEND
	var legend = new AmCharts.AmLegend();
	legend.useGraphSettings = true;
	chart.addLegend(legend);
	
	return chart
}