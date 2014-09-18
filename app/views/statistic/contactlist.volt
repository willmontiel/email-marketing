{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script type="text/javascript">
		function compareLists() {
			var id = $('#liststocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparelists')}}/{{contactList.idContactlist}}/" + id;
			}
		}
		
		var color = ['#97c86b', '#ef8807', '#BDBDBD'];
		var data = [];
		var i = 0;
		{%for data in summaryChartData %}
			var obj = new Object;
				obj.name = '{{ data['title'] }}';
				obj.y = {{ data['value'] }};
				obj.color = color[i];

				data.push(obj);
				i++;
		{%endfor%}
		
		createCharts('container', data);
	</script>
	{{ partial("statistic/partials/partial_statistics_domain") }}
{% endblock %}
{% block content %}

	{#   Navegacion botones pequeños   #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}

	{#   encabezado página   #}
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			<div class="header">
				<div class="title">{{contactList.name}}</div>
				<div class="title-info">Creada el {{date('d/M/Y', contactList.createdon)}}</div>
				<div class="sub-title">
					<span class="active-contacts">{{contactList.Ctotal}}</span><br /> 
					<span class="text-contacts">Contactos</span>
				</div>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	{#   Contenedor chart   #}
	<div class="row-fluid">
		<div id="container" class="col-sm-12 col-md-8 col-lg-6" style="padding-top: 5px;"></div>
		<div id="container2" class="col-sm-12 col-md-4 col-lg-6" style="padding-top: 5px;"></div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<hr>
	{#   parcial estadisticas generales   #}
	{#
		{{ partial("statistic/partials/general_stats_contacts_partial") }}
	#}

	{{ partial("statistic/partials/general_stats_with_domain") }}
	
	<hr>
	{#   Select para comparacion de estadisticas   #}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="title">Comparar</div><br /> <br />
			<form class="form-horizontal" role="form" style="padding-left: 15px;">
	  			<div class="form-group">
	  				<label class="sr-only" for=""></label>
					<select id="liststocompare" class="form-control">
						{%for clt in compareList %}
							<option value="{{clt.id}}">{{clt.name}}</option>
						{%endfor%}
					</select>
				</div>
			</form>
			<button class="btn btn-sm btn-default" onclick="compareLists();">Comparar</button>
		</div>
	</div>	
{% endblock %}