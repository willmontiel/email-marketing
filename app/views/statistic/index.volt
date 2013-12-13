{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url}}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/app_std.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ stylesheet_link('css/statisticStyles.css') }}
	
	<script>
		var chartData = [{
			type: "Aperturas",
			amount: 4252
		}, {
			type: "Clicks",
			amount: 3882
		}, {
			type: "Rebotados",
			amount: 964
		}]; 
	</script>
		<script>
	
			AmCharts.ready(function () {
				var chart = new AmCharts.AmPieChart();
				chart.dataProvider = chartData;
				chart.titleField = "type";
				chart.valueField = "amount";
				
				chart.sequencedAnimation = true;
                chart.startEffect = "elastic";
                chart.innerRadius = "30%";
                chart.startDuration = 2;
                chart.labelRadius = 15;
                chart.balloonText = "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>";
                // this makes the chart 3D
                chart.depth3D = 10;
                chart.angle = 15;
				chart.write('chartdiv');
			});
		
		</script>
	
{% endblock %}
{% block sectiontitle %}<i class="icon-signal icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberApplistContainer">
		<script type="text/x-handlebars">
			{# Tabs de navegacion #}
			<div class="news span5">
				<div class="offset3 titleMail">
					<h2>Correo XYZ</h2>
				</div>
				<div class="offset3 dataMailContacts">
					<table class="table-condensed">
						<tr>
							<td>
								<div class="avatar blue">
									<i class="icon-reorder icon-2x"></i>
								</div>
							</td>
							<td><h4 class="totalColor">9098</h4></td>
							<td><h4>|</h4></td>
							<td><h3 class="totalColor">100%</h3></td>
							<td><h4>Totales</h4></td>
						</tr>
						<tr>
							<td>
								<div class="avatar green">
									<i class="icon-lightbulb icon-2x"></i>
								</div>
							</td>
							<td><h4 class="darkturquoise">4252</h4></td>
							<td><h4>|</h4></td>
							<td><h3 class="darkturquoise">46.74%</h3></td>
							<td><h4>Aperturas</h4></td>
						</tr>
						<tr>
							<td>
								<div class="avatar cyan">
									<i class="icon-hand-up icon-2x"></i>
								</div>
							</td>
							<td><h4 class="clicksColor">3882</h4></td>
							<td><h4>|</h4></td>
							<td><h3 class="clicksColor">42.67%</h3></td>
							<td><h4>Clicks</h4></td>
						</tr>
						<tr>
							<td>
								<div class="avatar gray">
									<i class="icon-warning-sign icon-2x"></i>
								</div>
							</td>
							<td><h4 class="unsubscribedColor">964</h4></td>
							<td><h4>|</h4></td>
							<td><h3 class="unsubscribedColor">10.60%</h3></td>
							<td><h4>Des-suscritos</h4></td>
						</tr>
						<tr>
							<td>
								<div class="avatar darkred">
									<i class="icon-warning-sign icon-2x"></i>
								</div>
							</td>
							<td><h4 class="darkred">964</h4></td>
							<td><h4>|</h4></td>
							<td><h3 class="darkred">10.60%</h3></td>
							<td><h4>Rebotes</h4></td>
						</tr>
						<tr>
							<td>
								<div class="avatar red">
									<i class="icon-warning-sign icon-2x"></i>
								</div>
							</td>
							<td><h4 class="red">964</h4></td>
							<td><h4>|</h4></td>
							<td><h3 class="red">10.60%</h3></td>
							<td><h4>Spam</h4></td>
						</tr>
					</table>
				</div>
			</div>
	
			<div class="span6">
				<div id="chartdiv" style="width: 640px; height: 400px;">
				</div>
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="apertures/index">
			lalalalal
		</script>
	</div>
{% endblock %}
