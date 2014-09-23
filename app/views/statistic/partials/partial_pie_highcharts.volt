{{ javascript_include('vendors/highcharts/highcharts.js')}}
{{ javascript_include('vendors/highcharts/modules/exporting.js')}}
{#{{ javascript_include('vendors/highcharts/themes/sand-signika.js')}}#}

<script>
	function createCharts(container, data, labels, indicators) {
		x = "m";
		$(function () {
			$('#' + container).highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
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
							enabled: labels,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
							}
						},
						showInLegend: indicators
					}

				},
				series: [{
					type: 'pie',
					name: 'Porcentaje',
					data: data
				}]
			});
		});
	}
</script>