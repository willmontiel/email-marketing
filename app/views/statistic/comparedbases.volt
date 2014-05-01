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
		<div class="col-sm-12">
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
		</div>
	</div>

	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-3 col-md-offset-7">
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
		<div class="col-md-2">
			<button class="btn btn-sm btn-guardar extra-padding" onclick="compareDbases()">Comparar</button>
		</div>
	</div>
	
	<div class="space"></div>
	
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
	
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<table class="table table-striped">
				<tr>
					<td>
						<div class="optiontotal pull-right">
							{{statisticsData1.uniqueOpens}}
						</div>
					</td>
					<td>
						<div class="openscomponent optionpercent pull-right">
							{{statisticsData1.percentageUniqueOpens}}%
						</div>
					</td>
					<td>
						<div class="optionname">
							Aperturas
						</div>
					</td>
					<td>
						<div class="openscomponent optionpercent pull-left">
							{{statisticsData2.percentageUniqueOpens}}%
						</div>
					</td>
					<td>
						<div class="optiontotal pull-left">
							{{statisticsData2.uniqueOpens}}
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="clickscomponent optiontotal pull-right">
							{{statisticsData1.clicks}}
						</div>
					</td>
					<td>
						<div class="clickscomponent optionpercent pull-right">
							{#{{statisticsData1.statclicks}}%#}0%
						</div>
					</td>
					<td>
						<div class="optionname">
							Clics
						</div>
					</td>
					<td>
						<div class="clickscomponent optionpercent pull-left">
							{#{{statisticsData2.statclicks}}%#}0%
						</div>
					</td>
					<td>
						<div class="clickscomponent optiontotal pull-left">
							{{statisticsData2.clicks}}
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="unsubscribedcomponent optiontotal pull-right">
							{{statisticsData1.unsubscribed}}
						</div>
					</td>
					<td>
						<div class="unsubscribedcomponent optionpercent pull-right">
							{{statisticsData1.percentageUnsubscribed}}%
						</div>
					</td>
					<td>
						<div class="optionname">
							Des-suscritos
						</div>
					</td>
					<td>
						<div class="unsubscribedcomponent optionpercent pull-left">
							{{statisticsData2.percentageUnsubscribed}}%
						</div>
					</td>
					<td>
						<div class="unsubscribedcomponent optiontotal pull-left">
							{{statisticsData2.unsubscribed}}
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div class="bouncedcomponent optiontotal pull-right">
							{{statisticsData1.bounced}}
						</div>
					</td>
					<td>
						<div class="bouncedcomponent optionpercent pull-right">
							{{statisticsData1.percentageSpam}}%
						</div>
					</td>
					<td>
						<div class="optionname">
							Rebotes
						</div>
					</td>
					<td>
						<div class="bouncedcomponent optionpercent pull-left">
							{{statisticsData2.percentageSpam}}%
						</div>
					</td>
					<td>
						<div class="bouncedcomponent optiontotal pull-left">
							{{statisticsData2.bounced}}
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
{% endblock %}