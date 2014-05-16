{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1_2Url() ~ '/mail/private/' ~ mail.idMail }}';
	</script>
	{{ javascript_include('js/mixin_pagination_statistics.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('js/app_charts.js') }}
	
	{{ javascript_include('highcharts/highcharts.js')}}
	{{ javascript_include('highcharts/modules/exporting.js')}}
	{{ javascript_include('highcharts/modules/drilldown.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		function autoScroll() {
			event.preventDefault();
			
			var n = $(document).height();
			$('html, body').animate({ scrollTop: 2000 }, 'slow');
		}
	</script>
	<script>
		var chartData = [];
		App.mails = [];
		
		{%for cmail in compareMail %}
			var cmail = new Object();
			cmail.id = {{ cmail.id }};
			cmail.name = '{{ cmail.name }}';
			App.mails.push(cmail);
		{%endfor%}
		
		{#
		var cData = [
			{%for data in summaryChartData %}
				['{{ data['title'] }}',   {{ data['value'] }}],
			{%endfor%}
		];
		
		console.log(cData)
		$(function () {
			var container = $('#container');
			createHighPieChart(container, cData);
		});
		#}
		function compareMails() {
			if(App.mailCompare !== undefined) {
				window.location = "{{url('statistic/comparemails')}}/{{mail.idMail}}/" + App.mailCompare;
			}
		}
		
		function expandMailPreview() {
			if ($("#mail-preview").is(":visible")) {
				$('#mail-preview').hide("slow");
			}
			else {
				$('#mail-preview').show("slow");
			}
		}
	</script>
{% endblock %}
{% block content %}
	{#
		<div id="container"></div>
	#}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">

			{#   Botones de navegacion   #}
			{{ partial("mail/partials/small_buttons_nav_partial") }}

			{#   parcial encabezado pag   #}
			{{ partial("statistic/partials/header_partial") }}
			
			<div class="space"></div>
				<div class="row">
					<div class="col-md-7">
						{{ '{{view Ember.Select
							class="form-control"
							id="select-options-for-compare"
							contentBinding="App.mails"
							optionValuePath="content.id"
							optionLabelPath="content.name"
							valueBinding="App.mailCompare"}}'
						}}
					</div>
					<div class="col-md-5">
						<button class="btn btn-blue" onclick="compareMails()">Comparar</button>
					</div>
				</div>
			<div class="space"></div>
			
			{#   parcial vista en miniatura del correo y datos del mismo   #}
			{{ partial("statistic/partials/preview_email_partial") }}
				
			
			{#   parcial estadisticas generales   #}
			{{ partial("statistic/partials/general_stats_partial") }}
			
			{#   parcial estadisticas redes sociales   #}
			{{ partial("statistic/partials/social_media_stats_partial") }}
{#
			<div class="row">
				<div class="col-md-7">
					{{ '{{view Ember.Select
						class="form-control"
						id="select-options-for-compare"
						contentBinding="App.mails"
						optionValuePath="content.id"
						optionLabelPath="content.name"
						valueBinding="App.mailCompare"}}'
					}}
				</div>
				<div class="col-md-5">
					<button class="btn btn-blue" onclick="compareMails()">Comparar</button>
				</div>
			</div>
#}				
			
			{{ partial("statistic/partials/partial_statistics_nav") }}
			{{ "{{outlet}}" }}
		</script>
		
		{{ partial("statistic/partials/partial_ember_details") }}
		{#	 Partial para gráfica de estadisticas	#}
		{{ partial("statistic/partials/partial_graph") }}
	</div>
{% endblock %}
