{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1_2Url() ~ '/mail/' ~ mail.idMail }}';
	</script>
	{# Paginación de Ember #}
	{{ javascript_include('js/mixin_pagination_statistics.js') }}
	
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('js/app_charts.js') }}
	
	{# Moment.js#}
	{{ javascript_include('js/pluggins-editor/moment/moment-with-langs.min.js') }}
	
	
	{# HighCharts & HighMaps #}
	{{ javascript_include('vendors/highcharts/highcharts.js')}}
	{{ javascript_include('vendors/highmaps/modules/map.js')}}
	{{ javascript_include('vendors/highcharts/modules/exporting.js')}}
	{{ javascript_include('vendors/highcharts/modules/drilldown.js')}}
	{{ javascript_include('vendors/highmaps/modules/data.js')}}
	{{ javascript_include('vendors/highmaps/mapdata/world.js')}}
	{#<script src="http://code.highcharts.com/mapdata/custom/world.js"></script>#}
	
	{{ javascript_include('js/maps.js')}}
	
	{{ javascript_include('js/select2.js') }}
	<script type="text/javascript">
		function selectSummary() {
			$('#inputsummary').focus();
			$('#inputsummary').select();
		}
		
		function selectComplete() {
			$('#inputcomplete').focus();
			$('#inputcomplete').select();
		}
		function getUrlForStatistics(id) {
			$.post("{{url('share/statistics')}}/" + id, function(response){
				$('#summary').empty();
				$('#complete').empty();
				
				$('#summary').append('<input type="text" class="col-sm-12" readonly="readonly" id="inputsummary" value="' + response[0] + '" onClick="selectSummary();">');
				$('#complete').append('<input type="text" class="col-sm-12" readonly="readonly" id="inputcomplete" value="' + response[1] + '" onClick="selectComplete();">');
			});
		}
		
		function autoScroll() {
			event.preventDefault();
			
			var n = $(document).height();
			$('html, body').animate({ scrollTop: 2000 }, 'slow');
		}
		
		var chartData = [];
		App.mails = [];
		
		{%for cmail in compareMail %}
			var cmail = new Object();
			cmail.id = {{ cmail.id }};
			cmail.name = '{{ cmail.name }}';
			App.mails.push(cmail);
		{%endfor%}
		
		{#
		var cData = [
			{%for data in summaryChartData %}
				['{{ data['title'] }}',   {{ data['value'] }}],
			{%endfor%}
		];
		
		$(function () {
			var container = $('#container');
			createHighPieChart(container, cData);
		});
		#}
		function compareMails() {
			if(App.mailCompare !== undefined) {
				window.location = "{{url('statistic/comparemails')}}/{{mail.idMail}}/" + App.mailCompare;
			}
		}
		
		function expandMailPreview() {
			if ($("#mail-preview").is(":visible")) {
				$('#mail-preview').hide("slow");
			}
			else {
				$('#mail-preview').show("slow");
			}
		}
	</script>
	
	<script type="text/javascript">
		
		var gData = [
			{% for geostat in geostats %}
				{% if geostat.code is not empty AND geostat.name is not empty %}
					{code: "{{geostat.code}}",value: {{geostat.value}},name: "{{geostat.name}}"},
				{% endif %}
			{% endfor %}
		];
		
		$(function () {
			var obj = new Object();
			obj.container = "map-container";
			obj.title = "Aperturas por ubicación";
			obj.navigation = false;
			obj.label = "Aperturas";
			obj.valueText = " Aperturas";
			obj.data = gData;
			
			createMap(obj);
		});
	</script>
{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">

			{#   Botones de navegacion   #}
			{{ partial("mail/partials/small_buttons_nav_partial",['activelnk': 'statisticsmail']) }}

			{#   Encabezado pag   #}
			{{ partial("statistic/partials/header_partial") }}
						
			{#   Vista en miniatura del correo y datos del mismo   #}
			{{ partial("statistic/partials/preview_email_partial") }}
			
			{#   Estadisticas generales   #}
			{{ partial("statistic/partials/general_stats_partial") }}
			
			{#  Estadisticas redes sociales   #}
			{{ partial("statistic/partials/social_media_stats_partial") }}
			<div class="space"></div>			
			{#   Partial para compartir estadisticas y comparar estadisticas de correo	#}
			{{ partial("statistic/partials/shareandcompare_partial") }}	
			
			{# Geolocalización por aperturas #}
			<div id="map-container"></div>
	
			{#   Tabs de opciones de interacciones en estadisticas   #}
			{{ partial("statistic/partials/partial_statistics_nav") }}
			{{ "{{outlet}}" }}
		</script>
		
		{#   Contenido de los tabs de opciones de inteeracciones en estadisticas   #}
		{{ partial("statistic/partials/partial_ember_details") }}
		{#	 Gráfica de estadisticas	#}
		{{ partial("statistic/partials/partial_graph") }}
	</div>
	
	<div id="modal-simple" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Compartir estadísticas</h4>
				</div>
				<div class="modal-body">
					<p>
						Copie estos enlaces y compartalos con quien quiera, y así las personas que los abran
						en el navegador podrán ver las estadisticas del correo.
					</p>
					
					<h4>Compartir resumen de estadisticas del correo</h4>
					<p id="summary"></p>
					
					<div class="space"></div>
					
					<h4>Compartir estadísticas completas del correo</h4>
					<p id="complete"></p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
