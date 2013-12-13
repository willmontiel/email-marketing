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
	<script type = "text/javascript">
		var chartData = [{
			month: "Enero",
			apertures: 0
		}, {
			month: "Febrero",
			apertures: 0
		}, {
			month: "Marzo",
			apertures: 0
		}, {
			month: "Abril",
			apertures: 0
		}, {
			month: "Mayo",
			apertures: 0
		}, {
			month: "Junio",
			apertures: 0
		}, {
			month: "Julio",
			apertures: 0
		}, {
			month: "Agosto",
			apertures: 0
		}, {
			month: "Septiembre",
			apertures: 0
		}, {
			month: "Octubre",
			apertures: 500
		}, {
			month: "Noviembre",
			apertures: 900
		}, {
			month: "Diciembre",
			apertures: 800
		}]; 
	</script>
	<script type="text/javascript">
		AmCharts.ready(function () {
			var chart = new AmCharts.AmSerialChart();
			chart.dataProvider = chartData;
			chart.categoryField = "month";
			chart.startDuration = 1;
				
			var graph = new AmCharts.AmGraph();
			graph.valueField = "apertures";
			graph.type = "column";
			graph.title = "Aperturas de correo 2013";
			graph.lineColor = "#000000";
            graph.fillColors = "#6eb056";
            graph.fillAlphas = 0.7;
            graph.balloonText = "<span style='font-size:13px;'>[[title]] en [[category]]:<b>[[value]]</b></span>";
			chart.addGraph(graph);
	
			// LEGEND
            var legend = new AmCharts.AmLegend();
            legend.useGraphSettings = true;
            chart.addLegend(legend);
				
			chart.write('chartContainer');
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
			<div class="row-fluid">
				<div class="span3">
					<div class="news-title">
						Resumen
					</div>
					<table>
						<tr>
							<td>
								<dl>
									<dd><label class="small-circle-for-icon"><i class="icon-envelope icon-2x"></i></label></dd>
									<dd><i class="icon-eye-open"></i></dd>
									<dd><i class="icon-thumbs-up"></i></dd>
								</dl>
							</td>
							<td>
								<dl>
									<dd>Correos enviados</dd>
									<dd>Aperturas totales</dd>
									<dd>Promedio de aperturas</dd>
								</dl>
							</td>
							<td>
								<dl>
									<dd>2000</dd>
									<dd>1500</dd>
									<dd>3</dd>
								</dl>
							</td>
						</tr>
					</table>
				</div>
				
				<div id="chartContainer" class="time-graph span9"></div>
			</div>
			<div class="row-fluid">
				<div class="span12">
					<div class="box">
						<div class="box-header">
							<div class="title">
								Lista de aperturas
							</div>
						</div>
						<div class="box-content">
							<table class="table table-normal">
								<thead>
									<tr>
										<td>Fecha y hora</td>
										<td>Dirección de correo</td>
										<td>Sistema operativo?</td>
									</tr>
								</thead>
								<tbody>
								</tbody>
									<tr>
										<td></td>
										<td>xxxxx@xxxxx.com</td>
										<td>???</td>
									</tr>
									<tr>
										<td></td>
										<td>xxxxx@xxxxx.com</td>
										<td>???</td>
									</tr>
									<tr>
										<td></td>
										<td>xxxxx@xxxxx.com</td>
										<td>???</td>
									</tr>
									<tr>
										<td></td>
										<td>xxxxx@xxxxx.com</td>
										<td>???</td>
									</tr>
							</table>
						</div>
					</div>
				</div>
			</div>
		</script>
	</div>
{% endblock %}
