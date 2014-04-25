{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	<script type = "text/javascript">
		var chartData = [{
			month: "Ene",
			apertures: 0
		}, {
			month: "Feb",
			apertures: 0
		}, {
			month: "Mar",
			apertures: 0
		}, {
			month: "Abr",
			apertures: 0
		}, {
			month: "May",
			apertures: 0
		}, {
			month: "Jun",
			apertures: 0
		}, {
			month: "Jul",
			apertures: 0
		}, {
			month: "Ago",
			apertures: 0
		}, {
			month: "Sep",
			apertures: 0
		}, {
			month: "Oct",
			apertures: 500
		}, {
			month: "Nov",
			apertures: 5000
		}, {
			month: "Dic",
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
	<div class="row">
		<div class="news span4">
			<table class="table-condensed">
				<thead>
					<tr>
						<td colspan="5"><label class="label-gray-light-percent">Resumén de aperturas</label></td>
					</tr>
				</thead>
				<tbody>
					<tr><td colspan="5"></td></tr>
					<tr>
						<td colspan="3">
							<label class="label-blue-percent">
								<table>
									<tr>
										<td><i class="icon-envelope"></i></td>
										<td>3000</td>
									</tr>
								</table>	
							</label>
						</td>
						<td colspan="2"><h4>Correos enviados</h4></td>
					</tr>
					<tr>
						<td colspan="3">
							<label class="label-green-percent">
								<table>
									<tr>
										<td><i class="icon-search"></i></td>
										<td>2500</td>
										<td>|</td>
										<td>83%</td>
									</tr>
								</table>
							</label>
						</td>
						<td  colspan="2"><h4>Aperturas</h4></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div id="chartContainer" class="time-graph span8"></div>
	</div>
	<div class="row">
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
