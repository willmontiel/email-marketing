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
		});
		
	</script>
{% endblock %}
{% block sectiontitle %}Reporte de importación de contactos{% endblock %}
{% block content %}

{# Menu de navegacion pequeño #}
{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'import']) }}
{# /Menu de navegacion pequeño #}

{%for res in result%}
	<div class="row">
		<div class="span8 offset2">
			<div class="well relative">
				<p>Importacion de Archivo: {{res['name']}}</p>
				{%if res['status'] == "En Ejecucion"%}
					<p id="status-progress-{{res['idProcess']}}"></p>
					<div id="progress-bar-{{res['idProcess']}}" class="progress progress-striped progress-blue active"></div>
				{% endif %}
					
				<p id="status-title-{{res['idProcess']}}">Estado: {{res['status']}}</p>
				
				{%if res['status'] == "Finalizado"%}
				<div class="text-right">
					<a class="accordion-toggle collapsed btn btn-default" data-toggle="collapse" data-parent="#accordion2" href="#collapseInfo-{{res['idProcess']}}">
					  Ver Detalles
					</a>
				</div>
				{% endif %}
			</div>
		</div>
	</div>
	<div class="row">
		<div id="collapseInfo-{{res['idProcess']}}" class="accordion-body collapse" style="height: 0px;">
			<div class="span8 offset2">
				<div class="box">
					<div class="box-header">
						<span class="title">Resultado importación de contactos</span>
						<ul class="box-toolbar">
							<li><span class="icon"><i class="icon-folder-open-alt" style="font-size: 20px;"></i></span></li>
						</ul>
					</div>
					<div class="box-content">
						<table class="table table-striped table-contacts">
							<thead></thead>
							<tbody>
								<tr class="status-info">
									<td class="icon"><i class="icon-cloud-upload"></i></td>
									<td>Contactos totales en archivo</td>
										
									
									<td><span class="big-number">{{res['linesprocess']}}</span></td>
								</tr>
								<tr class="status-success">
									<td class="icon"><i class="icon-ok"></i></td>
									<td>
										<span class="news-title">Importados exitosamente </span>
										<a href="{{ url('process/downoladsuccess/') }}{{ res['idProcess'] }}" target="_blank">(Descargar reporte)</a>
									</td>
									<td><span class="big-number">{{res['import']}}</span></td>
								</tr>

								<tr class="status-warning">
									<td class="icon"><i class="icon-refresh"></i></td>
									<td>No importados porque ya existen</td>
									<td><span class="big-number">{{res['exist']}}</span></td>
								</tr>

								<tr class="status-error">
									<td class="icon"><i class="icon-remove"></i></td>
									<td>No importados por correo inválido </td>
									<td><span class="big-number">{{res['invalid']}}</span></td>
								</tr>

								<tr class="status-error">
									<td class="icon"><i class="icon-ban-circle"></i></td>
									<td>No importados por correo bloqueado </td>
									<td><span class="big-number">{{res['bloqued']}}</span></td>
								</tr>

								<tr class="status-warning">
									<td class="icon"><i class="icon-random"></i></td>
									<td>No importados porque estan duplicados en el archivo </td>
									<td><span class="big-number">{{res['repeated']}}</span></td>
								</tr>

								<tr class="status-pending">
									<td class="icon"><i class="icon-exclamation-sign"></i></td>
									<td>No importados por limite de contactos excedido </td>
									<td><span class="big-number">{{res['limit']}}</span></td>
								</tr>
								<tr class="status-error">
									<td class="icon"><i class="icon-warning-sign"></i></td>
									<td>
										<span class="news-title">Total contactos no importados</span>
										<a href="{{ url('process/downoladerror/') }}{{ res['idProcess'] }}" target="_blank">(Descargar reporte)</a>
									</td>
									<td><span class="big-number">{{res['Nimport']}}</span></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
{%endfor%}
{% endblock %}
