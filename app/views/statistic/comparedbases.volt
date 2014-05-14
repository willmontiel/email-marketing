{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
	{{ javascript_include('amcharts/pie.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		var chartData1 = [];
		var chartData2 = [];
		
		{%for data in summaryChartData1 %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			chartData1.push(data);
		{%endfor%}
			
		{%for data in summaryChartData2 %}
			var data = new Object();
			data.title = '{{ data['title'] }}';
			data.value = {{ data['value'] }};
			chartData2.push(data);
		{%endfor%}
		
		AmCharts.ready(function () {
			chart1 = createPieChart(chartData1);	
			chart1.write('summaryChart1');
			chart2 = createPieChart(chartData2);	
			chart2.write('summaryChart2');
		});
		
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparedbases')}}/{{dbase1.idDbase}}/" + id;
			}
		}
			
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}

		<div class="row">
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
		</div>
		<div class="container-fluid">
			<div class="col-xs-6 col-sm-5 col-md-4">
				<form class="form-horizontal" role="form">
		  			<div class="form-group">
		  				<label class="sr-only" for=""></label>
						<select id="dbasestocompare" class="form-control">
							{%for cdb in compareDbase %}
								<option value="{{cdb.id}}"
									{%if cdb.id == dbase2.idDbase%}
										selected
									{%endif%}
								>{{cdb.name}}</option>
							{%endfor%}
						</select>
					</div>
				</form>
			</div>
			<div class="col-md-2 col-xs-4 ptop-3">
				<button class="btn btn-sm btn-default btn-add extra-padding" onclick="compareDbases()">Comparar</button>
			</div>
			<div class="clearfix"></div>
			<div class="row">
				<div class="col-md-6">
					<h4 class="sectiontitle">{{dbase1.name}}</h4>
					<div id="summaryChart1" style="width: 640px; height: 400px;"></div>
				</div>
				<div class="col-md-6">
					<h4 class="sectiontitle">{{dbase2.name}}</h4>
					<div id="summaryChart2" style="width: 640px; height: 400px;"></div>
				</div>
			</div>
			<div class="space"></div>
		</div>
		<div class="container-fluid">
			<div class="col-md-8 col-md-offset-2">
				<table class="table table-normal">
					<tr class="opens big-number">
						<td>
							{{statisticsData1.uniqueOpens}}
						</td>
						<td>
							{{statisticsData1.percentageUniqueOpens}}%
						</td>
						<td>
							Aperturas
						</td>
						<td>
							{{statisticsData2.percentageUniqueOpens}}%
						</td>
						<td>
							{{statisticsData2.uniqueOpens}}
						</td>
					</tr>
					<tr class="clics big-number">
						<td>
							{{statisticsData1.clicks}}
						</td>
						<td>
								{#{{statisticsData1.statclicks}}%#}0%
						</td>
						<td>
							Clics
						</td>
						<td>
								{#{{statisticsData2.statclicks}}%#}0%
						</td>
						<td>
							{{statisticsData2.clicks}}
						</td>
					</tr>
					<tr class="unsubs big-number">
						<td>
							{{statisticsData1.unsubscribed}}
						</td>
						<td>
							{{statisticsData1.percentageUnsubscribed}}%
						</td>
						<td>
							Desuscritos
						</td>
						<td>
							{{statisticsData2.percentageUnsubscribed}}%
						</td>
						<td>
							{{statisticsData2.unsubscribed}}
						</td>
					</tr>
					<tr class="bounced big-number">
						<td>
							{{statisticsData1.bounced}}
						</td>
						<td>
							{{statisticsData1.percentageSpam}}%
						</td>
						<td>
							Rebotes
						</td>
						<td>
							{{statisticsData2.percentageSpam}}%
						</td>
						<td>
							{{statisticsData2.bounced}}
						</td>
					</tr>
				</table>
			</div>
		</div>
{% endblock %}