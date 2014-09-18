{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{#
	{{ stylesheet_link('css/statisticStyles.css') }}
	#}
	{{ super() }}
	{{ partial("statistic/partials/partial_pie_highcharts") }}
	<script>
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
		
		var domain_opening = [];
		{% for dopen in domainsByOpens%}
			var obj = new Object;
				obj.name = '{{dopen['domain']}}';
				obj.y = {{dopen['total']}};
				
				domain_opening.push(obj);
		{% endfor %}
		createCharts('domain-opening', domain_opening, false, false);
		
		
		var domain_bounced = [];
		{% for dbounced in domainsByBounced%}
			var obj = new Object;
				obj.name = '{{dbounced.domain}}';
				obj.y = {{dbounced.total}};
				
				domain_bounced.push(obj);
		{% endfor %}
		createCharts('domain-bounced', domain_bounced, false, false);
		
		var domain_unsubscribed = [];
		{% for dunsubscribed in domainsByUnsubscribed%}
			var obj = new Object;
				obj.name = '{{dunsubscribed.domain}}';
				obj.y = {{dunsubscribed.total}};
				
				domain_unsubscribed.push(obj);
		{% endfor %}
		createCharts('domain-dunsubscribed', domain_unsubscribed, false, false);
		
		var domain_spam = [];
		{% for dspam in domainsBySpam%}
			var obj = new Object;
				obj.name = '{{dspam.domain}}';
				obj.y = {{dspam.total}};
				
				domain_spam.push(obj);
		{% endfor %}
		createCharts('domain-spam', domain_spam, false, false);
		
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
			Estadísticas de envíos
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
		<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
			<span class="number">{{statisticsData.uniqueOpens|numberf}}</span><br>
			<span class="percent">{{statisticsData.percentageUniqueOpens}}%</span>
			<p>Aperturas</p>
		</div>
		<div class="col-xs-12 col-xs-12 col-sm-4 col-md-4 col-lg-4">
			Aperturas agrupadas por dominio
			<div id="domain-opening small-pie-chart"></div>
		</div>
	</div>
	
	<hr> 
	
	<div class="row header-background">
		<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
			
		</div>
		<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
			Rebotes agrupados por dominio
			<div id="domain-bounced small-pie-chart"></div>
		</div>
	</div>
	
	<hr> 
	
	<div class="row header-background">
		<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			Rebotes agrupados por dominio
			<div id="domain-dunsubscribed small-pie-chart"></div>
		</div>
	</div>
	
	<hr> 
	
	<div class="row header-background">
		<div class="col-xs-12 col-sm-4 col-sm-offset-2 col-md-4 col-md-offset-2 col-lg-4 col-lg-offset-2">
			
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			Rebotes agrupados por dominio
			<div id="domain-spam small-pie-chart"></div>
		</div>
	</div>
	
	<hr> 
	
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