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
			$("select").select2();
		});
		
		function compareMails() {
			var id = $('#liststocompare').val();
			window.location = "{{url('statistic/comparelists')}}/{{List1.idContactlist}}/" + id;
		}
			
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}
<div class="row">
	<div class="span12">
		<div class="span2 pull-right">
			<button class="btn btn-blue" onclick="compareMails()">Comparar</button>
		</div>
		<div class="span3 pull-right">
			<select id="liststocompare">
				{%for clt in compareList %}
					<option value="{{clt.id}}"
						{%if clt.id == List2.idContactlist%}
							selected
						{%endif%}
					>{{clt.name}}</option>
				{%endfor%}
			</select>
		</div>
	</div>
</div>
<div class="row">
	<div class="span6">
		<div class="leftComponent">
			<div class="componentname">
				<h3>{{List1.name}}</h3>
			</div>
			<div class="box">
				<div id="summaryChart1" style="width: 640px; height: 400px;"></div>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="rightComponent">
			<div class="componentname">
				<h3>{{List2.name}}</h3>
			</div>
			<div class="box">
				<div id="summaryChart2" style="width: 640px; height: 400px;"></div>
			</div>
		</div>
	</div>
	<div class="offset3 span6">
		<div class="componentsummary">
			<table class="table table-bordered">
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
</div>
{% endblock %}