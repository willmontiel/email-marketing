{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlstatistic.url ~ '/mail/' ~ mail.idMail }}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
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
			data.url = '{{ data['url'] }}';
			chartData.push(data);
		{%endfor%}
		
		AmCharts.ready(function () {
			chart = createPieChart(chartData);	
			chart.write('summaryChart');
			$("select").select2();
		});
		
		function compareMails() {
			window.location = "{{url('statistic/comparemails')}}/{{mail.idMail}}/" + App.mailCompare;
		}
		
		
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">
			<div class="row-fluid">
				<div class="span12">
					<h3>{{mail.name}} <small>{{statisticsData.total|numberf}} correos enviados</small></h3>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					<table class="table" style="border: 0px !important;" >
						<thead></thead>
						<tbody>
							<tr>
								<td>
									<div class="box">
										<div class="box-section news with-icons">
											<label class="avatar-openings"><i class="icon-folder-open icon-3x"></i></label>
											<div class="news-time">
											  <span>{{statisticsData.statopens}}%</span>
											</div>
											<div class="news-content">
												<label class="label-openings">
													 {{statisticsData.opens|numberf}}
												</label>
												<div class="news-text">
													{{'{{#linkTo "drilldown.opens" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Aperturas</span>{{/linkTo}}'}}
												</div>
											</div>
										</div>	
									</div>
								</td>
								<td>
									<div class="box">
										<div class="box-section news with-icons">
											<label class="avatar-clicks"><i class="icon-hand-up icon-3x"></i></label>
											<div class="news-time">
											  <span>{{statisticsData.statclicks}}%</span>
											</div>
											<div class="news-content">
												<label class="label-clicks">{{statisticsData.clicks|numberf}}</label>
												<div class="news-text">
													{{'{{#linkTo "drilldown.clicks" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Clics</span>{{/linkTo}}'}}
												</div>
											</div>
										</div>	
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="box">
										<div class="box-section news with-icons">
											<label class="avatar-unsubscribed"><i class="icon-minus-sign icon-3x"></i></label>
											<div class="news-time">
											  <span>{{statisticsData.statunsubscribed}}%</span>
											</div>
											<div class="news-content">
												<label class="label-unsubscribed">{{statisticsData.unsubscribed|numberf}}</label>
												<div class="news-text">
													{{'{{#linkTo "drilldown.unsubscribed" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Des-suscritos</span>{{/linkTo}}'}}
												</div>
											</div>
										</div>	
									</div>
								</td>
								<td>
									<div class="box">
										<div class="box-section news with-icons">
											<label class="avatar-bounced"><i class="icon-ban-circle icon-3x"></i></label>
											<div class="news-time">
											  <span>{{statisticsData.statbounced}}%</span>
											</div>
											<div class="news-content">
												<label class="label-bounced">{{statisticsData.bounced|numberf}}</label>
												<div class="news-text">
													{{'{{#linkTo "drilldown.bounced" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Rebotes</span>{{/linkTo}}'}}
												</div>
											</div>
										</div>	
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="box">
										<div class="box-section news with-icons">
											<label class="avatar-spam"><i class="icon-warning-sign icon-3x"></i></label>
											<div class="news-time">
											  <span>{{statisticsData.statspam}}%</span>
											</div>
											<div class="news-content">
												<label class="label-spam">{{statisticsData.spam|numberf}}</label>
												<div class="news-text">
													{{'{{#linkTo "drilldown.spam" href=false}}<span style="text-decoration: underline;" onClick="autoScroll()">Reportes de Spam</span>{{/linkTo}}'}}
												</div>
											</div>
										</div>	
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					<div class="span4">
						{{ '{{view Ember.Select
							class="select"
							contentBinding="App.mails"
							optionValuePath="content.id"
							optionLabelPath="content.name"
							valueBinding="App.mailCompare"}}'
						}}
					</div>
					<button class="btn btn-blue" onclick="compareMails()">Comparar</button>
				</div>
			{#</div>
			<div class="row-fluid">#}
				<div class="span6">
					<div class="box">
						<div id="summaryChart" style="width: 640px; height: 400px;"></div>
					</div>
				</div>
			</div>
			<div class="row-fluid">
				<div class="span6">
					{#<div id="summaryChart" style="width: 640px; height: 400px;"></div>#}
				</div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					{{ "{{outlet}}" }}
				</div>
			</div>
		</script>
		{{ partial("statistic/mailpartial") }}
		<script type="text/x-handlebars" data-template-name="timeGraph">
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
		<div id="ChartContainer"></div>
		</script>
	</div>
{% endblock %}
