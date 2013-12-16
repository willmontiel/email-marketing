{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
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
			amount: 63
		}, {
			type: "Rebotados",
			amount: 22
		},{
			type: "No Aperturas",
			amount: 15
		}]; 
	</script>
	<script>

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
				<div class="offset2 titleMail">
					<h2>Correo XYZ</h2>
				</div>
				<div class="offset3 dataMailContacts">
					<table class="table-condensed">
						<tr>
							
							<td>
								<label class="label-total-percent centertext">
									<table><tr>
										<td>
											<i class="icon-envelope" style="font-size: 20px;"></i>
										</td>
										<td>9098</td>
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
										<td>4252</td>
										<td>|</td>
										<td>46.74%</td>
									</tr></table>
								</label>
							</td>
							<td><h4 class="openColor">{{'{{#linkTo "drilldown.apertures" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Aperturas</a>{{/linkTo}}'}}</h4></td>
						</tr>
						<tr>
						
							<td>
								<label class="label-click-percent">
									<table><tr>
										<td>
											<i class="icon-hand-up"></i>
										</td>
										<td>3882</td>
										<td>|</td>
										<td>42.67%</td>
									</tr></table>
								</label>
							</td>
							<td><h4 class="clicksColor">Clicks</h4></td>
						</tr>
						<tr>
							
							<td>
								<label class="label-unsubscribed-percent">
									<table><tr>
										<td>
											<i class="icon-minus-sign"></i>
										</td>
										<td>964</td>
										<td>|</td>
										<td>10.60%</td>
									</tr></table>
								</label>
							</td>
							<td><h4 class="unsubscribedColor">Des-suscritos</h4></td>
						</tr>
						<tr>
							
							<td>
								<label class="label-bounced-percent">
									<table><tr>
										<td>
											<i class="icon-warning-sign"></i>
										</td>
										<td>964</td>
										<td>|</td>
										<td>10.60%</td>
									</tr></table>
								</label>
							</td>
							<td><h4 class="bouncedColor">Rebotes</h4></td>
						</tr>
						<tr>
							
							<td>
								<label class="label-spam-percent">
									<table><tr>
										<td>
											<i class="icon-remove"></i>
										</td>
										<td>964</td>
										<td>|</td>
										<td>10.60%</td>
									</tr></table>
								</label>
							</td>
							<td><h4 class="spamColor">Spam</h4></td>
						</tr>
					</table>
				</div>
			</div>
	
			<div class="span6">
				<div id="chartdiv" style="width: 640px; height: 400px;">
				</div>
			</div>
			<div class="span12">
				{{ "{{outlet}}" }}
			</div>
		</script>
		
		<script type="text/x-handlebars" data-template-name="drilldown/apertures">
			<div class="row-fluid">
				<div class="span3">
					<div class="news-title">
						Resumen
					</div>
					<table>
						<tr>
							<td>
								<ul>
									<li><label class="small-circle-for-icon"><i class="icon-envelope icon-2x"></i></label></li>
									<li><i class="icon-eye-open"></i></li>
									<li><i class="icon-thumbs-up"></i></li>
								</ul>
							</td>
							<td>
								<ul>
									<li>Correos enviados</li>
									<li>Aperturas totales</li>
									<li>Promedio de aperturas</li>
								</ul>
							</td>
							<td>
								<ul>
									<li>2000</li>
									<li>1500</li>
									<li>3</li>
								</ul>
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
