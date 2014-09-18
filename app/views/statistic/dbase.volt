{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{#
	{{ stylesheet_link('css/statisticStyles.css') }}
	#}
	{{ super() }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script type="text/javascript">
		function compareDbases() {
			var id = $('#dbasestocompare').val();
			if(id !== null) {
				window.location = "{{url('statistic/comparedbases')}}/{{dbase.idDbase}}/" + id;
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
		createCharts('container', data, true, true);
	</script>
	{{ partial("statistic/partials/partial_statistics_domain") }}
{% endblock %}
{% block content %}
	{#   Navegacion botones pequeños   #}
	<div class="row">
		{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
	</div>
	
	{#   encabezado página   #}
	<div class="row header-background">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="header">
				<div class="title">{{dbase.name}}</div>
				<div class="title-info">Creada el {{date('d/M/Y', dbase.createdon)}}</div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="contact-indicator">
				<span class="total-contacts">{{dbase.Ctotal}}</span><br /> 
				<span class="text-contacts">Contactos</span>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
			<div class="contact-indicator">
				<span class="total-mails">{{statisticsData.sent|numberf}}</span><br /> 
				<span class="text-contacts">Correos enviados</span>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<hr>
	
	{#   Contenedor chart   #}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="big-title"><span class="glyphicon glyphicon-stats"></span> Estadísticas de envíos</div>
			<div id="container"></div>
		</div>
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
					<select id="dbasestocompare" class="form-control">
						{%for cdb in compareDbase %}
							<option value="{{cdb.id}}">{{cdb.name}}</option>
						{%endfor%}
					</select>
				</div>
			</form>
			<button class="btn btn-sm btn-default" onclick="compareDbases();">Comparar</button>
		</div>
	</div>	
{% endblock %}