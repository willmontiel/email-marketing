{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">

		var MyBaseURL = '{{urlManager.getBaseUri(true)}}';
		function checkUnfinishedImports() {
			{%for res in result%}
				if('{{res['status']}}' !== 'Finalizado' && '{{res['status']}}' !== 'Cancelado') {
					loadNow('{{res['idProcess']}}');
				}	
			{%endfor%}
		}
		
		function loadNow (idProcess) {   
			$.getJSON(MyBaseURL + 'process/refreshimport/' + idProcess, function(data){
				if(data.length !== 0) {
					$('#processing-' + data.idProcess).empty();
					$('#processing-' + data.idProcess).append(data.status);
					
					if (data.status === 'Finalizado' || data.status === 'Cancelado') {
						location.reload(true);
					}
				}
			});
		};
		
		$(function() {
			setInterval(checkUnfinishedImports, 5000);
			$('.btn-for-modal-accordion').on('click', function(){
				if(($(this).text()).trim() === 'Ver detalles') {
					$(this).text('Colapsar');
				}
				else {
					$(this).text('Ver detalles');
				}
			});
		});
		
	</script>
{% endblock %}
{% block sectiontitle %}Reporte de importación de contactos{% endblock %}
{% block content %}
	{# Menu de navegacion pequeño #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'import']) }}
	{# /Menu de navegacion pequeño #}

	<div class="row">
		<h4 class="sectiontitle">Lista de importaciones</h4>
	</div>

	<div class="row">
		{% if result|length != 0 %}
			<table class="table table-striped table-bordered">
				<tr>
					<td>Nombre del archivo</td>
					<td>Estado</td>
					<td></td>
				</tr>
			{%for res in result%}
				{% if res['status'] == 'Finalizado' OR res['status'] == 'Cancelado'%}
					<tr>
						<td>{{res['name']}}</td>
						<td>{{res['status']}}</td>
						<td><a href="{{url('')}}/{{res['idProcess']}}">Ver detalles</a></td>
					</tr>
				{% else %}
					<tr>
						<td>{{res['name']}}</td>
						<td><div id="processing-{{res['idProcess']}}"></div> <img src="{{url('')}}images/loading1.gif" height="40" width="40"></td>
						<td><a href="{{url('')}}/{{res['idProcess']}}">Ver detalles</a></td>
					</tr>
				{% endif %}
			{%endfor%}
			</table>
		{% else %}
			<div class="bs-callout bs-callout-warning">
				No ha importado contactos por este medio aún, para importar contactos desde una archivo .csv
				diríjase a una lista de contactos y haga clic en el botón <strong>Importar contactos</strong>
			</div>
		{% endif %}
	</div>
{% endblock %}
