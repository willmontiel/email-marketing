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
	
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	
	{{ javascript_include('highcharts/highcharts.js')}}
	{{ javascript_include('highcharts/modules/exporting.js')}}
	{{ javascript_include('highcharts/modules/drilldown.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		function autoScroll() {
			$('html, body').animate({scrollTop: '615px'}, 'slow');
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
			
		{%for data in summaryChartData %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			chartData.push(data);
		{%endfor%}
		
		AmCharts.ready(function () {
			var chart = createPieChart(chartData);
			try{
				if($('#summaryChart')[0] === undefined) {
					setTimeout(function(){chart.write('summaryChart');},1000);
				}
				else {
					chart.write('summaryChart');
				}
			}catch(err){
				console.log(err.message);
			}
		});
		
		var cData = [
			{%for data in summaryChartData %}
				['{{ data['title'] }}',   {{ data['value'] }}],
			{%endfor%}
		];
		
		$(function () {
			var container = $('#container');
			var highchart = createHighPieChart(container, cData);
		});
		
		function compareMails() {
			if(App.mailCompare !== undefined) {
				window.location = "{{url('statistic/comparemails')}}/{{mail.idMail}}/" + App.mailCompare;
			}
		}
		
		
	</script>
{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">
			<div class="wrap">
				<div class="col-md-5">
					<h4 class="sectiontitle numbers-contacts">{{mail.name}}</h4>
				</div>
				<div class="col-md-7">
					<div class="col-md-6">
						<p><span class="blue big-number">{{statisticsData.total|numberf}} </span>correos enviados</p>
					</div>
					<div class="col-md-6">
						<br><p class="text-right">Enviado el: {{date('Y-m-d', mail.finishedon)}}</p>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			{#   parcial vista en miniatura del correo y datos del mismo   #}
			{{ partial("statistic/partials/preview_email_partial") }}

			{#   parcial estadisticas generales   #}
			{{ partial("statistic/partials/general_stats_partial") }}

			{#   parcial estadisticas redes sociales   #}
			{{ partial("statistic/partials/social_media_stats_partial") }}

			<div class="clearfix"></div>
			<div class="space"></div>

			<div class="text-right">
				<button class="btn btn-sm btn-add extra-padding">Compartir resumen de estadísticas</button>
			</div>

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
			<div class="col-md-6">
				<div class="box">
					<div id="summaryChart" style="width: 640px; height: 400px;"></div>
				</div>
			</div>
#}				
			{{ "{{outlet}}" }}

		</script>
		{{ partial("statistic/mailpartial") }}
		<script type="text/x-handlebars" data-template-name="timeGraph">
{#
		<div class="row">
			<div class="pull-right scaleChart">
				<div class="pull-left">
					Agrupar por: &nbsp;
				</div>
				<div class="pull-right">
					<label for="scaleHour">
						{{'{{view Ember.RadioButton id="scaleHour" name="scale" selectionBinding="App.scaleSelected" value="hh"}}'}}
						Hora &nbsp;
					</label>
				</div>
				<div class="pull-right">
					<label for="scaleDay">
						{{'{{view Ember.RadioButton id="scaleDay" name="scale" selectionBinding="App.scaleSelected" value="DD"}}'}}
						Dia &nbsp;
					</label>
				</div>
				<div class="pull-right">
					<label for="scaleMonth">
						{{'{{view Ember.RadioButton id="scaleMonth" name="scale" selectionBinding="App.scaleSelected" value="MM" checked="checked"}}'}}
						Mes &nbsp;
					</label>
				</div>
			</div>
		</div>
#}	
		<div id="ChartContainer"></div>
		</script>

	</div>
{% endblock %}
