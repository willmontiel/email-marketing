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
			<div class="row wrapper">
				<div class="col-sm-8 col-md-6">
					<div class="bg-thumb">
						<img src="data: image/png;base64, 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" class="" alt="thumbnail del correo" />
					</div>
				</div>
				<div class="col-md-3 ptop-50">
					<p>Información sobre esta campaña</p>
					<p>Información sobre esta campaña</p>
					<p>Información sobre esta campaña</p>
				</div>
			</div>
			<hr>
			<div class="col-md-2 col-sm-4 col-xs-6 item">
				{{'{{#link-to "drilldown.opens" class="anchor" href=false}}' }}
					<div class="sends anchor">
						<div class="sm-icons-stats-sends center-block"></div>
						<span class="number-send">{{statisticsData.total|numberf}}</span><br>
						<p>Envíos</p>
					</div>
				{{ '{{/link-to}}'}}
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 item">
				{{'{{#link-to "drilldown.opens" class="anchor" href=false}}' }}
					<div class="opens anchor">
						<div class="sm-icons-stats-opens center-block"></div>
						<span class="number">{{statisticsData.opens|numberf}}</span><br>
						<span class="percent">{{statisticsData.statopens}}%</span>
						<p>Aperturas</p>
					</div>
				{{ '{{/link-to}}'}}
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 item">
				{{'{{#link-to "drilldown.clicks" class="anchor" href=false}}' }}
					<div class="clics anchor">
						<div class="sm-icons-stats-clics center-block"></div>
						<span class="number">{{statisticsData.totalclicks|numberf}}</span><br>
						<span class="percent">{{statisticsData.percent_clicks_CTR}}%</span>
						<p>Clics</p>
					</div>
				{{'{{/link-to}}'}}
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 bounced anchor item">
				{{'{{#link-to "drilldown.bounced" class="anchor" href=false}}' }}
					<div class="bounced anchor">
						<div class="sm-icons-stats-bounced center-block"></div>
						<span class="number">{{statisticsData.hardbounced|numberf}}</span><br>
						<span class="percent">{{statisticsData.stathardbounced}}%</span>
						<p>Rebotes</p>
					</div>
				{{ '{{/link-to}}'}}
			</div>
			<div class="clearfix"></div>
			<div class="space"></div>
			<hr>
			<div class="row wrapper">
				<div class="col-xs-6 col-sm-4 col-md-3">
					{{'{{#link-to "drilldown.unsubscribed" class="anchor" href=false}}' }}
						<div class="sm-icons-stats-unsubs unsubs anchor">
							<div class="pleft-60">
								<span class="little-number">{{statisticsData.unsubscribed|numberf}}</span>
								<span class="little-number">{{statisticsData.statunsubscribed}}%</span>
								<p class="mbottom-0">Desuscritos</p>
							</div>
						</div>
					{{ '{{/link-to}}'}}
				</div>
				<div class="col-xs-6 col-sm-4 col-md-3">
					{{'{{#link-to "drilldown.spam" class="anchor" href=false}} '}}
						<div class="sm-icons-stats-spam spam anchor">
							<div class="pleft-60">
								<span class="little-number">{{statisticsData.spam|numberf}}</span>
								<span class="little-number">{{statisticsData.statspam}}%</span>
								<p class="mbottom-0">Spam</p>
							</div>
						</div>
					{{' {{/link-to}}'}}
				</div>
				<div class="clearfix"></div>
			</div>
			<hr>
			<div class="text-right">
				<span>Compartir </span> <a href=""> <img src="{{url('')}}b3/images/icon-face-color.png" /> </a> <a href=""> <img src="{{url('')}}b3/images/icon-tweett-color.png" /></a>
			</div>
			<hr>
			<h4 class="sectiontitle">Interacciones en redes sociales</h4>
			<div class="col-md-2 col-sm-4 col-xs-6 social facebook">
				<div class="sm-icons-stats-facebook center-block"></div>
				<span class="number-stats-dashboard-summary">{{statisticsSocial.share_fb|numberf}}</span>
				<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_fb|numberf}}</span></p></div>
				<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_fb|numberf}}</span></p></div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 social twitter">
				<div class="sm-icons-stats-tweet center-block"></div>
				<span class="number-stats-dashboard-summary">{{statisticsSocial.share_tw|numberf}}</span>
				<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_tw|numberf}}</span></p></div>
				<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_tw|numberf}}</span></p></div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 social google">
				<div class="sm-icons-stats-gplus center-block"></div>
				<span class="number-stats-dashboard-summary">{{statisticsSocial.share_gp|numberf}}</span>
				<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_gp|numberf}}</span></p></div>
				<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_gp|numberf}}</span></p></div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-6 social linkedin">
				<div class="sm-icons-stats-linkedin center-block"></div>
				<span class="number-stats-dashboard-summary">{{statisticsSocial.share_li|numberf}}</span>
				<div class="opens-social"><p>Aperturas <span class="number"> {{statisticsSocial.open_li|numberf}}</span></p></div>
				<div class="clics-social"><p>Clics <span class="number"> {{statisticsClicksSocial.click_li|numberf}}</span></p></div>
			</div>
		</div>
		<div class="clearfix"></div>
		<hr>

		<h4 class="sectiontitle">Aperturas</h4>
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
