/* HighCharts*/

function createHighPieChart(container, chartData) {
	container.highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false,
		},	
		title: {
			text: ''
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				dataLabels: {
					enabled: false,
					format: '<b>{point.name}</b>: {point.percentage:.1f} %',
					style: {
						color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
					}
				},
				showInLegend: true,
			}

		},
		series: [{
			type: 'pie',
			name: 'Porcentaje',
			data: chartData
		}]
	});
}


function createBarHighChart(chartname, chartData) {
	if (chartData.length !== 0) {
		var chartData = chartData[0];
		console.log(chartData);
		console.log(chartData.categories);
		var colors = Highcharts.getOptions().colors;
		
		var chart = $('#' + chartname).highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Estadisticas de apertura'
            },
            subtitle: {
                text: 'Para ver mas detalle haga clic en la barra que desee.'
            },
            xAxis: {
                categories: chartData.categories
            },
            yAxis: {
                title: {
                    text: 'Cantidad de aperturas'
                }
            },
            plotOptions: {
                column: {
                    cursor: 'pointer',
                    point: {
                        events: {
                            click: function() {
                                var drilldown = this.drilldown;
                                if (drilldown) { // drill down
                                    setChart(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                } 
								else { // restore
                                    setChart(chartData.name, chartData.categories, chartData.data);
                                }
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        color: colors[0],
                        style: {
                            fontWeight: 'bold'
                        },
                        formatter: function() {
                            return this.y;
                        }
                    }
                }
            },
            tooltip: {
                formatter: function() {
                    var point = this.point,
                        s = this.x +': <b>' + this.y + ' apertura(s)</b><br/>';
                    if (point.drilldown) {
                        s += 'Clic para ver por días';
                    } else {
                        s += 'Clic para volver a semanas';
                    }
                    return s;
                }
            },
            series: [{
				name: chartData.name,
				data: chartData.data
			}],
            exporting: {
                enabled: false
            }
        })
        $(this).highcharts(); // return chart
		
		
		
		function setChart(name, categories, data, color) {
			var c = chart.highcharts();
			c.xAxis[0].setCategories(categories, false);
			c.series[0].remove(false);
			c.addSeries({
				name: name,
				data: data,
				color: color || 'orange'
			}, false);
			c.redraw();
        }
	}
}

function createBarGroupHighChart(chartname, chartData) {
	
    $('#' + chartname).highcharts({
        data: {
            table: document.getElementById('datatable')
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Detalle de rebotes, spam y desuscritos'
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Total'
            }
        },
        tooltip: {
            formatter: function() {
                return '<b>'+ this.series.name +'</b><br/>'+
                    this.point.y +' '+ this.point.name.toLowerCase();
            }
        }
    });
}


/* AmCharts*/

function createPieChart(chartData) {
	var chart = new AmCharts.AmPieChart();
	chart.dataProvider = chartData;
	chart.titleField = "title";
	chart.valueField = "value";

	chart.colors = ["#8CC079", "#953B39", "#8C8689"];
	
	chart.sequencedAnimation = true;
	chart.startEffect = "easeOutSine";
	chart.innerRadius = "40%";
	chart.startDuration = 1;
	chart.labelRadius = -35;
	chart.labelText = "[[percents]]%";
	chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
	// this makes the chart 3D
	chart.depth3D = 0;
	chart.angle = 0;
	chart.marginLeft = -80;
	
	// LEGEND
	var legend = new AmCharts.AmLegend();
	legend.align = "center";
	legend.markerType = "circle";
	legend.switchType = "v";
	legend.labelText = "[[title]]";
	legend.markerLabelGap = 7;
	legend.valueText = "";
	chart.addLegend(legend);
	
	return chart;
}




function createBarChart(chart, chartData, dateFormat, minPeriod, text, multVal) {
	if(chart === undefined || chart === null) {
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
	if(multVal !== undefined && multVal !== null && !chartData[0].hasOwnProperty('value')) {
		for (var index in chartData[0]) {
			if(multVal[0].value[index] !== undefined) {
				var graph = new AmCharts.AmGraph();
				graph.valueField = index;
				graph.type = "column";
				graph.title = text + ' ' + multVal[0].value[index];
				graph.lineColor = "#000000";
				graph.fillColors = '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length === 6) ?  lor : co(lor); })('');
				graph.fillAlphas = 0.7;
				graph.balloonText = "<span style='font-size:13px;'>" + text + ' ' + multVal[0].value[index] + " en [[category]]:<b>[[value]]</b></span>";
				chart.addGraph(graph);
			}
		}
	}
	else {
		var graph = new AmCharts.AmGraph();
		graph.valueField = "value";
		graph.type = "column";
		graph.title = text;
		graph.lineColor = "#000000";
		graph.fillColors = '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length === 6) ?  lor : co(lor); })('');
		graph.fillAlphas = 0.7;
		graph.balloonText = "<span style='font-size:13px;'>" + text + " en [[category]]:<b>[[value]]</b></span>";
		chart.addGraph(graph);
	}

	// LEGEND
	var legend = new AmCharts.AmLegend();
	legend.useGraphSettings = true;
	chart.addLegend(legend);
	
	return chart;
}





function createLineChart(chart, chartData, dateFormat, minPeriod, text, multVal) {
	if(chart === undefined || chart === null) {
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
	if(multVal !== undefined && multVal !== null && !chartData[0].hasOwnProperty('value')) {
		for (var index in chartData[0]) {
			if(multVal[0].value[index] !== undefined) {
				var graph = new AmCharts.AmGraph();
				graph.valueField = index;
				graph.type = "line";
				graph.bullet = "round";
				graph.bulletColor = "#FFFFFF";
				graph.useLineColorForBulletBorder = true;
				graph.bulletBorderAlpha = 1;
				graph.bulletBorderThickness = 2;
				graph.bulletSize = 7;
				graph.title = text + ' ' + multVal[0].value[index];
				graph.lineThickness = 2;
				graph.lineColor = '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length === 6) ?  lor : co(lor); })('');
				graph.balloonText = "<span style='font-size:13px;'>" + text + ' ' + multVal[0].value[index] + " de [[category]]:<b>[[value]]</b></span>";
				chart.addGraph(graph);
			}
		}
	}
	else {
		var graph = new AmCharts.AmGraph();
		graph.type = "line";
		graph.bullet = "round";
		graph.bulletColor = "#FFFFFF";
		graph.useLineColorForBulletBorder = true;
		graph.bulletBorderAlpha = 1;
		graph.bulletBorderThickness = 2;
		graph.bulletSize = 7;
		graph.title = text;
		graph.balloonText = "<span style='font-size:13px;'>" + text + " en [[category]]:<b>[[value]]</b></span>";
		graph.valueField = "value";
		graph.lineThickness = 2;
		graph.lineColor = '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length === 6) ?  lor : co(lor); })('');
		chart.addGraph(graph);
	}

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

function createLineStepChart(chart, chartData, dateFormat, minPeriod, text, multVal){
	if(chart === undefined || chart === null) {
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
	if(multVal !== undefined && multVal !== null && !chartData[0].hasOwnProperty('value')) {
		for (var index in chartData[0]) {
			if(multVal[0].value[index] !== undefined) {
				var graph = new AmCharts.AmGraph();
				graph.valueField = index;
				graph.type = "step";
				graph.title = text + ' ' + multVal[0].value[index];
				graph.lineColor = '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length === 6) ?  lor : co(lor); })('');
				graph.balloonText = "<span style='font-size:13px;'>" + text + ' ' + multVal[0].value[index] + " en [[category]]:<b>[[value]]</b></span>";
				chart.addGraph(graph);
			}
		}
	}
	else {
		var graph = new AmCharts.AmGraph();
		graph.type = "step"; // this line makes step graph
		graph.valueField = "value";
		graph.lineColor = '#' + (function co(lor){   return (lor += [0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f'][Math.floor(Math.random()*16)]) && (lor.length === 6) ?  lor : co(lor); })('');
		graph.title = text;
		graph.balloonText = "<span style='font-size:13px;'>" + text + " en [[category]]:<b>[[value]]</b></span>";
		chart.addGraph(graph);
	}

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
	
	return chart;
}