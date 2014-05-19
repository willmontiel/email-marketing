{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
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
		
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparedbases')}}/{{dbase1.idDbase}}/" + id;
			}
		}
	</script>
{% endblock %}
{% block content %}
	{#   Botones pequeños navegacion   #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
	<div class="space"></div>
	
	<div class="row">
		<div class="col-md-6 col-md-offset-6">
			<div class="col-md-6 text-right">
				<select id="dbasestocompare" class="form-control">
					{%for cdb in compareDbase %}
						<option value="{{cdb.id}}">
							{%if cdb.id == dbase2.idDbase%}
								selected
							{%endif%}
						{{cdb.name}}</option>
					{%endfor%}
				</select>
			</div>
			<div class="col-md-6 text-right">
				<button class="btn btn-sm btn-default btn-add extra-padding" onclick="compareDbases()">Comparar</button>
			</div>
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	<div class="row">
		<div class="col-md-6">
			<h4 class="sectiontitle">{{dbase1.name}}</h4>
			<div id="summaryChart1" class="col-md-12"></div>
		</div>
			
		<div class="col-md-6">
			<h4 class="sectiontitle">{{dbase2.name}}</h4>
			<div id="summaryChart2" class="col-md-12"></div>
		</div>
	</div>
	
	<div class="space"></div>
	
	{{ partial('statistic/partials/partial_statistics_compare') }}
{% endblock %}