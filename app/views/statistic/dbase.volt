{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script>
		{#
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
		#}
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparedbases')}}/{{dbase.idDbase}}/" + id;
			}
		}
		
		
		var domains = [];
		{% for domain in domains%}
			var obj2 = new Object;
				obj2.name = '{{domain['domain']}}';
				obj2.y = {{domain['total']}};
				
				domains.push(obj2);
		{% endfor %}
		
		createCharts('container', domains);
		
	</script>
{% endblock %}
{% block content %}
	{#   Navegacion botones pequeños   #}
	<div class="row">
		{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
	</div>
	{#   encabezado página   #}
	<div class="row header-background">
		<div class="col-sm-12 col-md-6 col-lg-6">
			<div class="header">
				<div class="title">{{dbase.name}}</div>
				<div class="title-info">Creada el {{date('d/M/Y', dbase.createdon)}}</div>
			</div>
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<div class="contact-indicator">
				<span class="active-contacts">{{dbase.Ctotal}}</span><br /> 
				<span class="text-contacts">Contactos</span>
			</div>
		</div>
		<div class="col-sm-12 col-md-3 col-lg-3">
			<div class="contact-indicator">
				<span class="sent-mails">{{statisticsData.sent|numberf}}</span><br /> 
				<span class="text-contacts">Correos enviados</span>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	
	<hr>
	
	{#   Contenedor chart   #}
	<div class="row">
		<div class="col-sm-12 col-md-12 col-lg-12">
			Estadísticas por envíos
			<div id="container"></div>
		</div>
		{#
		<div class="col-sm-12 col-md-8 col-lg-6" style="padding-top: 5px;">
			Dominios
			<div id="container2"></div>
		</div>
		#}
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<hr>
	{#   parcial estadisticas generales   #}
	{#
	{{ partial("statistic/partials/general_stats_contacts_partial") }}
	#}
	
	<div class="row header-background">
		<div class="col-sm-12 col-md-6 col-lg-6">
			
		</div>
		<div class="col-sm-12 col-md-6 col-lg-6">
			
		</div>
	</div>
	{#   Select para comparacion de estadisticas   #}
	<h4 class="sectiontitle">Comparación</h4>
	<div class="container-fluid">
		<div class="col-xs-6 col-sm-5 col-md-4">
			<form class="form-horizontal" role="form">
	  			<div class="form-group">
	  				<label class="sr-only" for=""></label>
					<select id="dbasestocompare" class="form-control">
						{%for cdb in compareDbase %}
							<option value="{{cdb.id}}">{{cdb.name}}</option>
						{%endfor%}
					</select>
				</div>
			</form>
		</div>
		<div class="col-md-2 col-xs-4 ptop-3">
			<button class="btn btn-sm btn-default btn-add extra-padding" onclick="compareDbases()">Comparar</button>
		</div>
	</div>	
{% endblock %}