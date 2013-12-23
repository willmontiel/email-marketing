{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlstatistic.url ~ '/dbase/' ~ stat.idDbase }}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ stylesheet_link('css/statisticStyles.css') }}
	<script>
			var chartData = [{
				type: "Aperturas",
				amount: {{stat.uniqueOpens}}
			}, {
				type: "Rebotados",
				amount: {{stat.bounced}}
			},{
				type: "No Aperturas",
				amount: {{stat.sent - stat.uniqueOpens}}
			}]; 

			AmCharts.ready(function () {
				var chart = new AmCharts.AmPieChart();
				chart.dataProvider = chartData;
				chart.titleField = "type";
				chart.valueField = "amount";

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
{% block sectiontitle %}<i class="icon-signal icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		{# <script type="text/x-handlebars"> #}
			<div class="news span7">
				<div class="titleMail">
					<h2>Base de Datos XYZ</h2>
				</div>
				<div class="dataMailContacts">
					<table class="table-condensed">
						<tr>
							<td>
								<table class="table-condensed">
									<tr>
										<td class="border-radious-blue-left">
											<i class="icon-envelope" style="font-size: 20px;"></i>
										</td>
										<td class="border-radious-blue-center" colspan="2">

										</td>
										<td class="border-radious-blue-right">
											{{stat.sent}}
										</td>
										<td>
											<h4 class="totalColor">Totales</h4>
										</td>
									</tr>
									<tr><td colspan="5"><td></tr>
									<tr>
										<td class="border-radious-green-left">
											<i class="icon-search"></i>
										</td>
										<td class="border-radious-green-center">
											{{stat.uniqueOpens}}
										</td>
										<td class="border-radious-green-center">
											|
										</td>
										<td class="border-radious-green-right">
											{{stat.percentageUniqueOpens}}%
										</td>
										<td>
											<h4 class="openColor subtitleColor">Aperturas</h4>
										</td>
									</tr>
									<tr><td colspan="5"><td></tr>
									<tr>
										<td class="border-radious-cyan-left">
											<i class="icon-hand-up"></i>
										</td>
										<td class="border-radious-cyan-center ">
											{{stat.clicks}}
										</td>
										<td class="border-radious-cyan-center">
											|
										</td>
										<td class="border-radious-cyan-right">
											---
										</td>
										<td><h4 class="clicksColor subtitleColor">Clics</h4></td>
									</tr>
								</table>
							</td>
							<td>
								<table class="table-condensed">
									<tr>
										<td class="border-radious-gray-left">
											<i class="icon-minus-sign"></i>
										</td>
										<td class="border-radious-gray-center ">
											{{stat.unsubscribed}}
										</td>
										<td class="border-radious-gray-center">
											|
										</td>
										<td class="border-radious-gray-right">
											{{stat.percentageUnsubscribed}}%
										</td>
										<td><h4 class="unsubscribedColor subtitleColor">Des-suscritos</h4></td>
									</tr>
									<tr><td colspan="5"><td></tr>
									<tr>
										<td class="border-radious-scarlet-left">
											<i class="icon-warning-sign"></i>
										</td>
										<td class="border-radious-scarlet-center ">
											{{stat.bounced}}
										</td>
										<td class="border-radious-scarlet-center">
											|
										</td>
										<td class="border-radious-scarlet-right">
											{{stat.percentageBounced}}%
										</td>
										<td><h4 class="bouncedColor subtitleColor">Rebotes</h4></td>
									</tr>
									<tr><td colspan="5"><td></tr>
									<tr>
										<td class="border-radious-red-left">
											<i class="icon-remove"></i>
										</td>
										<td class="border-radious-red-center ">
											{{stat.spam}}
										</td>
										<td class="border-radious-red-center">
											|
										</td>
										<td class="border-radious-red-right">
											{{stat.percentageSpam}}%
										</td>
										<td><h4 class="spamColor subtitleColor">Spam</h4></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="span5">
				<div id="summaryChart" style="width: 640px; height: 400px;">
				</div>
			</div>
		</script>
	</div>
{% endblock %}
