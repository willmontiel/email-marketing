{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlstatistic.url ~ '/mail/' ~ idMail }}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ stylesheet_link('css/statisticStyles.css') }}

	<script>
		var chartData = [];
		
		{%for data in summaryChartData %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			data.url = '{{ data['url'] }}';
			chartData.push(data);
		{%endfor%}

		AmCharts.ready(function () {
			var chart = new AmCharts.AmPieChart();
			chart.dataProvider = chartData;
			chart.titleField = "title";
			chart.valueField = "value";
			chart.urlField = "url";

			chart.sequencedAnimation = true;
			chart.startEffect = "easeOutSine";
			chart.innerRadius = "40%";
			chart.startDuration = 1;
			chart.labelRadius = 2;
			chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
			// this makes the chart 3D
			chart.depth3D = 10;
			chart.angle = 15;
			
			chart.addListener("clickSlice", function (event) {
				
			});
			
			chart.write('summaryChart');
		});

	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">
			{# Tabs de navegacion #}
			<div class="news span6">
				<div class="titleMail">
					<h2>{{statisticsData.mailName}}</h2>
				</div>
				<div class="offset1 dataMailContacts">
					<div class="infoStat">
						<table class="table-condensed">
							<tr>
								<td>
									<label class="label-total-percent centertext">
										<table><tr>
											<td>
												<i class="icon-envelope" style="font-size: 20px;"></i>
											</td>
											<td>{{statisticsData.total}}</td>
										</tr></table>
									</label>
								</td>
								<td><h4 class="totalColor">Totales</h4></td>
							</tr>
							<tr>
								<td>
									<label class="label-open-percent">
										<table><tr>
											<td>
												<i class="icon-search"></i>
											</td>
											<td>{{statisticsData.opens}}</td>
											<td>|</td>
											<td><b>{{statisticsData.statopens}}%</b></td>
										</tr></table>
									</label>
								</td>
								<td><h4 class="openColor subtitleColor">{{'{{#linkTo "drilldown.opens" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Aperturas</a>{{/linkTo}}'}}</h4></td>
							</tr>
							<tr>
								<td>
									<label class="label-click-percent">
										<table><tr>
											<td>
												<i class="icon-hand-up"></i>
											</td>
											<td>{{statisticsData.clicks}}</td>
											<td>|</td>
											<td><b>{{statisticsData.statclicks}}%</b></td>
										</tr></table>
									</label>
								</td>
								<td><h4 class="clicksColor subtitleColor">{{'{{#linkTo "drilldown.clicks" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Clicks</a>{{/linkTo}}'}}</h4></td>
							</tr>
						</table>
					</div>
					<div class="infoStat">
						<table class="table-condensed" cellspacing="10">
							<tr>
								<td>
									<label class="label-unsubscribed-percent">
										<table><tr>
											<td>
												<i class="icon-minus-sign"></i>
											</td>
											<td>{{statisticsData.unsubscribed}}</td>
											<td>|</td>
											<td><b>{{statisticsData.statunsubscribed}}%</b></td>
										</tr></table>
									</label>
								</td>
								<td><h4 class="unsubscribedColor subtitleColor">{{'{{#linkTo "drilldown.unsubscribed" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Des-suscritos</a>{{/linkTo}}'}}</h4></td>
							</tr>
							<tr>
								<td>
									<label class="label-bounced-percent">
										<table><tr>
											<td>
												<i class="icon-warning-sign"></i>
											</td>
											<td>{{statisticsData.bounced}}</td>
											<td>|</td>
											<td><b>{{statisticsData.statbounced}}%</b></td>
										</tr></table>
									</label>
								</td>
								<td><h4 class="bouncedColor subtitleColor">Rebotes</h4></td>
							</tr>
							<tr>
								<td>
									<label class="label-spam-percent">
										<table><tr>
											<td>
												<i class="icon-remove"></i>
											</td>
											<td>{{statisticsData.spam}}</td>
											<td>|</td>
											<td><b>{{statisticsData.statspam}}%</b></td>
										</tr></table>
									</label>
								</td>
								<td><h4 class="spamColor subtitleColor">Spam</h4></td>
							</tr>
						</table>	
					</div>	
						
						
				</div>
			</div>
	
			<div class="span6">
				<div id="summaryChart" style="width: 640px; height: 400px;">
				</div>
			</div>
			<div class="span12">
				{{ "{{outlet}}" }}
			</div>
		</script>
		{{ partial("statistic/mailpartial") }}
		<script type="text/x-handlebars" data-template-name="timeGraph">
		<div id="ChartContainer"></div>
		</script>
	</div>
{% endblock %}
