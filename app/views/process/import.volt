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
			setInterval(checkUnfinishedImports, 3000);
		});
		
	</script>
{% endblock %}
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
				<thead>
					<tr>
						<th>Nombre del archivo</th>
						<th>Estado</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
			{%for res in result%}
					<tr>
						<td>{{res['name']}}</td>
					{% if res['status'] == 'Finalizado' OR res['status'] == 'Cancelado'%}
						<td>{{res['status']}}</td>
					{% else %}
						<td>
							<div id="processing-{{res['idProcess']}}" style="display: inline;">{{res['status']}} </div>
							<div style="display: inline;">
								<img class="pull-right" src="{{url('')}}images/loading2.gif" height="30" width="30">
							</div>
						</td>
					{% endif %}
						<td><a href="{{url('process/importdetail')}}/{{res['idProcess']}}">Ver detalles</a></td>
					</tr>
			{%endfor%}
				</tbody>
			</table>
		{% else %}
			<div class="bs-callout bs-callout-warning">
				No ha importado contactos por este medio aún, para importar contactos desde una archivo .csv
				diríjase a una lista de contactos y haga clic en el botón <strong>Importar contactos</strong>
			</div>
		{% endif %}
	</div>
{% endblock %}
