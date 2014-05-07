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
		
		function compareMails() {
			if(App.mailCompare !== undefined) {
				window.location = "{{url('statistic/comparemails')}}/{{mail.idMail}}/" + App.mailCompare;
			}
		}
		
		
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12">
			<a href="">Compartir</a>
		</div>
	</div>
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
						<br><p class="text-right">Fecha del envío  {{date('Y-m-d', mail.finishedon)}}</p>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				{{'{{#link-to "drilldown.opens" class="anchor" href=false}}' }}
					<div class="box-dashboard-summary summary-opens anchor">
						<div class="title-stats-dashboard-summary">{{statisticsData.uniqueOpens|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{statisticsData.percentageUniqueOpens}}%</div>
						<div class="title-stats-dashboard-summary">Aperturas</div>
					</div>
				{{ '{{/link-to}}'}}
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				{{'{{#link-to "drilldown.clicks" class="anchor" href=false}}' }}
					<div class="box-dashboard-summary summary-clicks anchor">
						<div class="title-stats-dashboard-summary">{{statisticsData.clicks|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{statisticsData.percent_clicks_CTR}}%</div>
						<div class="title-stats-dashboard-summary">Clics</div>
					</div>
				{{'{{/link-to}}'}}
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				{{'{{#link-to "drilldown.unsubscribed" class="anchor" href=false}}' }}
					<div class="box-dashboard-summary summary-unsubscribed">
						<div class="title-stats-dashboard-summary">{{statisticsData.unsubscribed|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{statisticsData.percentageUnsubscribed}}%</div>
						<div class="title-stats-dashboard-summary">Desuscritos</div>
					</div>
				{{ '{{/link-to}}'}}
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				{{'{{#link-to "drilldown.bounced" class="anchor" href=false}}' }}
					<div class="box-dashboard-summary summary-bounced anchor">
						<div class="title-stats-dashboard-summary">{{statisticsData.bounced|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{statisticsData.percentageBounced}}%</div>
						<div class="title-stats-dashboard-summary">Rebotes</div>
					</div>
				{{ '{{/link-to}}'}}
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6">
				{{'{{#link-to "drilldown.spam" class="anchor" href=false}} '}}
					<div class="box-dashboard-summary summary-spam anchor">
						<div class="title-stats-dashboard-summary">{{statisticsData.spam|numberf}}</div>
						<div class="number-stats-dashboard-summary">{{statisticsData.percentageSpam}}%</div>
						<div class="title-stats-dashboard-summary">Spam</div>
					</div>
				{{' {{/link-to}}'}}
			</div>
		</div>

		<div class="space"></div>
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
		<div id="ChartContainer"></div>
		</script>
	</div>
{% endblock %}
