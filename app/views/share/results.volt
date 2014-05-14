{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1_2Url() ~ '/mail/' ~ mail.idMail }}';
	</script>
	{{ javascript_include('js/mixin_pagination_statistics.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('highcharts/highcharts.js')}}
	{{ javascript_include('highcharts/modules/exporting.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		function autoScroll() {
			$('html, body').animate({scrollTop: '615px'}, 'slow');
		}
	</script>
	<script>
		$(function () {
			$('#container').highcharts({
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
					data: [
						{%for data in summaryChartData %}
							['{{ data['title'] }}',   {{ data['value'] }}],
						{%endfor%}
					]
				}]
			});
		});
	</script>
{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">
			{{ partial("statistic/partials/header_partial") }}
			{{ partial("statistic/partials/preview_email_partial") }}
			
			{#
				<div id="container" style="width: 300px; height: 250px;"></div>
			#}
			{% if type == 'summary' %}
				{{ partial("statistic/partials/partial_general_resume_stats") }}
			{% else %}
				{{ partial("statistic/partials/general_stats_partial") }}
			{% endif %}
				
			{{ partial("statistic/partials/social_media_stats_partial") }}
			{{ "{{outlet}}" }}
		</script>
		
		{{ partial("statistic/partials/partial_ember_details") }}
		{{ partial("statistic/partials/partial_graph") }}
	</div>
{% endblock %}
