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
					var percent = Math.round((data.linesprocess/data.totalReg)*100);{#{{((res['linesprocess'] / res['totalReg']) * 100)|int}}#}
					$('#progress-bar-' + data.idProcess).empty();
					$('#status-progress-' + data.idProcess).empty();
					$('#status-title-' + data.idProcess).empty();
					
					//$('#progress-bar-' + data.idProcess).append('<div class="bar tip" title="' + percent + '%" data-percent="' + percent + '" style="width: ' + percent + '%;" data-original-title="' + percent + '%"></div>');
					
					$('#progress-bar-' + data.idProcess).append('<div class="progress-bar"  role="progressbar" aria-valuenow="' + data.linesprocess + '" aria-valuemin="0" aria-valuemax="' + data.totalReg + '" style="width: 100%"><span class="">' + percent +  '% Completado</span></div>');
					
								
							
					
	
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
					{%if res['status'] != "Finalizado" and res['status'] != "Cancelado"%}
						<p id="status-progress-{{res['idProcess']}}"></p>
						<div id="progress-bar-{{res['idProcess']}}" class="progress progress-striped active"></div>
					{% elseif res['status'] == "Cancelado" %}
						<div class="progress">
							<div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
								<span class="sr-only">{{res['status']}}</span>
							</div>
						</div>
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
						<div class="box-content col-md-12">
							<h4>Resumen de importación del archivo: nombre del archivo a la lista: {{res['name']}}</h4>
							
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
											{{res['linesprocess']}}
										</td>
									</tr>
									<tr class="green">
										<td>
											<span class="glyphicon glyphicon-ok-circle"></span>
										</td>
										<td>
											Importados exitosamente <a href="{{ url('process/downoladsuccess/') }}{{ res['idProcess'] }}" target="_blank">(Descargar reporte)</a>
										</td>
										<td class="big-number text-right">
											{{res['import']}}
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
											{{res['exist']}}
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
											{{res['invalid']}}
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
											{{res['bloqued']}}
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
											{{res['repeated']}}
										</td>
									</tr>
									<tr class="red">
										<td>
											<span class="glyphicon glyphicon-ban-circle"></span>
										</td>
										<td>
											No importados por límite de contactos excedidos
										</td>
										<td class="big-number text-right">
											{{res['limit']}}
										</td>
									</tr>
									<tr class="red">
										<td>
											<span class="glyphicon glyphicon-ban-circle"></span>
										</td>
										<td>
											Total contactos no importados <a href="{{ url('process/downoladerror/') }}{{ res['idProcess'] }}" target="_blank">(Descargar reporte)</a>
										</td>
										<td class="big-number text-right">
											{{res['Nimport']}}
										</td>
									</tr>
								</tbody>
								<tfoot>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="space"></div>
			<div class="space"></div>
		{%endfor%}
	{% else %}
		<div class="bs-callout bs-callout-warning">
			No ha importado contactos por este medio aún, para importar contactos desde una archivo .csv
			diríjase a una lista de contactos y haga clic en el botón <strong>Importar contactos</strong>
		</div>
	{% endif %}
{% endblock %}
