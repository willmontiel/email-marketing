{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('js/pluggins-editor/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script>
		var color = ['#97c86b', '#ef8807', '#BDBDBD'];
		var data1 = [];
		var i = 0;
		{%for sum1 in summaryChartData1 %}
			var obj = new Object;
				obj.name = '{{ sum1['title'] }}';
				obj.y = {{ sum1['value'] }};
				obj.color = color[i];

				data1.push(obj);
				i++;
		{%endfor%}
		
		var data2 = [];
		var j = 0;
		{%for sum2 in summaryChartData2 %}
			var obj = new Object;
				obj.name = '{{ sum2['title'] }}';
				obj.y = {{ sum2['value'] }};
				obj.color = color[j];

				data2.push(obj);
				j++;
		{%endfor%}
		
		createCharts('summaryChart1', data1);
		createCharts('summaryChart2', data2);
		
		function compareList() {
			var id = $('#liststocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparelists')}}/{{compare1.idContactlist}}/" + id;
			}
		}
			
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-bar-chart icon-2x"></i>Estadisticas{% endblock %}
{% block sectionsubtitle %}{% endblock %}
{% block content %}

	<div class="row">
		<div class="col-sm-12">
			{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}
		</div>
	</div>

	<div class="space"></div>

	<div class="row">
		<div class="col-md-3 col-md-offset-7">
			<select id="liststocompare" class="form-control">
				{%for clt in compareList %}
					<option value="{{clt.id}}"
						{%if clt.id == compare2.idContactlist%}
							selected
						{%endif%}
					>{{clt.name}}</option>
				{%endfor%}
			</select>
		</div>
		<div class="col-md-2 text-right">
			<button class="btn btn-sm btn-default extra-padding" onclick="compareList();">Comparar</button>
		</div>
	</div>
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-6">
			<h4 class="sectiontitle">{{compare1.name}}</h4>
			<div id="summaryChart1" class="col-sm-12"></div>
		</div>
		<div class="col-md-6">
			<h4 class="sectiontitle">{{compare2.name}}</h4>
			<div id="summaryChart2" class="col-sm-12"></div>
		</div>
	</div>

	<div class="space"></div>
	
	{{ partial('statistic/partials/partial_statistics_compare') }}	
{% endblock %}