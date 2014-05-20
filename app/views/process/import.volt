{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">

		var MyBaseURL = '{{urlManager.getBaseUri(true)}}';
		function checkUnfinishedImports() {
			{%for res in result%}
				if('{{res['status']}}' !== 'Finalizado') {
					loadNow('{{res['idProcess']}}');
				}	
			{%endfor%}
		}
		function loadNow (idProcess) {   
			$.getJSON(MyBaseURL + 'process/refreshimport/' + idProcess, function(data){
				if(data.length !== 0) {
					var percent = Math.round((data.linesprocess/data.totalReg)*100);{#{{((res['linesprocess'] / res['totalReg']) * 100)|int}}#}
					$('#progress-bar-' + data.idProcess).empty();
					$('#status-progress-' + data.idProcess).empty();
					$('#status-title-' + data.idProcess).empty();
					
					$('#progress-bar-' + data.idProcess).append('<div class="bar tip" title="' + percent + '%" data-percent="' + percent + '" style="width: ' + percent + '%;" data-original-title="' + percent + '%"></div>');
					$('#status-progress-' + data.idProcess).append('Registros Importados: ' + data.linesprocess + ' de ' + data.totalReg + '');
					$('#status-title-' + data.idProcess).append('Estado: ' + data.status);
					
					if (data.status === 'Finalizado') {
						$('#progress-bar' + data.idProcess).empty();
						$('#status-progress' + data.idProcess).empty();
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

	{#   importaciones progreso y reporte   #}

	{# Menu de navegacion pequeño #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'import']) }}
	{# /Menu de navegacion pequeño #}

	<div class="row">
		<h4 class="sectiontitle">Importando archivo: nombre del archivo a la lista: nombre de la lista</h4>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="progress progress-striped active">
				<div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
					<span class="">45% Complete</span>
				</div>
			</div>
		</div>
	</div>
	<div class="row wrapper">
		<div class="">
			<a href="">Regresar a la lista</a> ó 
			<button class="btn btn-sm btn-default extra-padding">Ver detalle</button>
		</div>
	</div>

		<h4>Resumen de importación del archivo: nombre del archivo a la lista: nombre de la lista</h4>
		<table class="table table-contacts report-import table-condensed table-striped">
			<thead>
			</thead>
			<tbody>
				<tr class="blue">
					<td>
						<span class="glyphicon glyphicon-folder-open"></span>
					</td>
					<td>
						Contactos totales en archivo
					</td>
					<td class="big-number text-right">
						50.000
					</td>
				</tr>
				<tr class="green">
					<td>
						<span class="glyphicon glyphicon-ok-circle"></span>
					</td>
					<td>
						Importados exitosamente <a href="">(Descargar reporte)</a>
					</td>
					<td class="big-number text-right">
						49.900
					</td>
				</tr>
				<tr class="red">
					<td>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</td>
					<td>
						No importados porque ya existen
					</td>
					<td class="big-number text-right">
						7
					</td>
				</tr>
				<tr class="red">
					<td>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</td>
					<td>
						No importados por correo inválido
					</td>
					<td class="big-number text-right">
						5
					</td>
				</tr>
				<tr class="red">
					<td>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</td>
					<td>
						No importados por correo bloqueado
					</td>
					<td class="big-number text-right">
						32
					</td>
				</tr>
				<tr class="red">
					<td>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</td>
					<td>
						No importados porque están duplicados en el archivo
					</td>
					<td class="big-number text-right">
						20
					</td>
				</tr>
				<tr class="red">
					<td>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</td>
					<td>
						No importados por límite de contactos exedidos
					</td>
					<td class="big-number text-right">
						25
					</td>
				</tr>
				<tr class="red">
					<td>
						<span class="glyphicon glyphicon-ban-circle"></span>
					</td>
					<td>
						Total contactos no importados <a href="">(Descargar reporte)</a>
					</td>
					<td class="big-number text-right">
						27
					</td>
				</tr>

			</tbody>
			<tfoot>
			</tfoot>
		</table>

	




	
	
	
	

	{# Menu de navegacion pequeño #}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'import']) }}
	{# /Menu de navegacion pequeño #}

	<div class="row">
		<h4 class="sectiontitle">Lista de importaciones</h4>
	</div>
	{% if result|length != 0 %}
		{%for res in result%}
			<div class="row">
				<div class="well relative">
					<p>Importación de archivo: <strong>{{res['name']}}</strong></p>
					{%if res['status'] == "En Ejecución"%}
						<p id="status-progress-{{res['idProcess']}}"></p>
						<div id="progress-bar-{{res['idProcess']}}" class="progress progress-striped progress-blue active"></div>
					{% endif %}

					<p id="status-title-{{res['idProcess']}}">Estado: {{res['status']}}</p>

					{%if res['status'] == "Finalizado"%}
					<div class="text-right">
						<a class="accordion-toggle collapsed btn btn-sm btn-default extra-padding btn-for-modal-accordion" data-toggle="collapse" data-parent="#accordion2" href="#collapseInfo-{{res['idProcess']}}">
						  Ver detalles
						</a>
					</div>
					{% endif %}
				</div>
			</div>
			<div id="collapseInfo-{{res['idProcess']}}" class="accordion-body collapse" style="height: 0px;">
				<div class="container-fluid">
					<div class="box">

						<div class="box-content col-md-8 col col-md-offset-1">
							<h5>Resultado importación de contactos</h5>
							<table class="table table-striped table-contacts">
								<thead></thead>
								<tbody>
									<tr class="status-info">
										<td class="icon"><i class="icon-cloud-upload"></i></td>
										<td>Contactos totales en archivo</td>


										<td><span class="blue big-number pull-right">{{res['linesprocess']}}</span></td>
									</tr>
									<tr class="status-success">
										<td class="icon"><i class="icon-ok"></i></td>
										<td>
											<span class="news-title">Importados exitosamente </span>
											<a href="{{ url('process/downoladsuccess/') }}{{ res['idProcess'] }}" target="_blank">(Descargar reporte)</a>
										</td>
										<td><span class="blue big-number pull-right">{{res['import']}}</span></td>
									</tr>

									<tr class="status-warning">
										<td class="icon"><i class="icon-refresh"></i></td>
										<td>No importados porque ya existen</td>
										<td><span class="blue big-number pull-right">{{res['exist']}}</span></td>
									</tr>

									<tr class="status-error">
										<td class="icon"><i class="icon-remove"></i></td>
										<td>No importados por correo inválido </td>
										<td><span class="blue big-number pull-right">{{res['invalid']}}</span></td>
									</tr>

									<tr class="status-error">
										<td class="icon"><i class="icon-ban-circle"></i></td>
										<td>No importados por correo bloqueado </td>
										<td><span class="blue big-number pull-right">{{res['bloqued']}}</span></td>
									</tr>

									<tr class="status-warning">
										<td class="icon"><i class="icon-random"></i></td>
										<td>No importados porque están duplicados en el archivo </td>
										<td><span class="blue big-number pull-right">{{res['repeated']}}</span></td>
									</tr>

									<tr class="status-pending">
										<td class="icon"><i class="icon-exclamation-sign"></i></td>
										<td>No importados por límite de contactos excedido </td>
										<td><span class="blue big-number pull-right">{{res['limit']}}</span></td>
									</tr>
									<tr class="status-error">
										<td class="icon"><i class="icon-warning-sign"></i></td>
										<td>
											<span class="news-title">Total contactos no importados</span>
											<a href="{{ url('process/downoladerror/') }}{{ res['idProcess'] }}" target="_blank">(Descargar reporte)</a>
										</td>
										<td><span class="blue big-number pull-right">{{res['Nimport']}}</span></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		{%endfor%}
	{% else %}
		<div class="bs-callout bs-callout-warning">
			No ha importado contactos por este medio aún, para importar contactos desde una archivo .csv
			diríjase a una lista de contactos y haga clic en el botón <strong>Importar contactos</strong>
		</div>
	{% endif %}
{% endblock %}
