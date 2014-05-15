/* HighCharts*/

function createHighPieChart(container, chartData, text, subtitle) {
	if (chartData.length !== 0 && chartData !== undefined && chartData !== null) {
		
		if (text === undefined || text === null) {
			text = 'Estadisticas';
		}
		
		$('#' + container).highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false,
			},	
			title: {
				text: text
			},
			subtitle: {
				text: subtitle
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f} %</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
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
}


function createBarHighChart(chartname, chartData, title, text, ref) {
	//if (chartData.length !== 0) {
//		var colors = Highcharts.getOptions().colors;
		
		var chart = $('#' + chartname).highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: title
            },
            subtitle: {
                text: 'Para ver mas detalle haga clic en la barra que desee.'
            },
            xAxis: {
                categories: chartData.categories
            },
            yAxis: {
                title: {
                    text: text
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
                        color: '#848484',
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
                        s = this.x +': <b>' + this.y + ' ' + ref + ' </b><br/>';
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
				color: color
			}, false);
			c.redraw();
        }
	//}
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