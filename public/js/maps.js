//recibe container, title, navigation, data, label, valueText
function createMap(obj) {
	// Initiate the chart
	$('#' + obj.container).highcharts('Map', {
		title : {
			text : obj.title
		},

		mapNavigation: {
			enabled: obj.navigation,
			buttonOptions: {
				verticalAlign: 'bottom'
			}
		},

		colorAxis: {
			min: 1,
			max: 1000,
			type: 'logarithmic'
		},

		series : [{
			data : obj.data,
			mapData: Highcharts.maps['custom/world'],
			joinBy: ['iso-a2', 'code'],
			name: obj.label,
			states: {
				hover: {
					color: '#BADA55'
				}
			},
			tooltip: {
				valueSuffix: obj.valueText
			}
		}]
	});
}