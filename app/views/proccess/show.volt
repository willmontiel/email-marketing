{% extends "templates/index_new.volt" %}
{% block sectiontitle %}Reporte de importación de contactos{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="span8 offset2">
			<div class="well relative">
				<p>Importacion de Archivo: {{res['name']}}</p>
				<p>Registros Importados: {{res['linesprocess']}} de {{res['totalReg']}}</p>
				<div class="progress progress-striped progress-blue active">
                    <div class="bar tip" title="{{((res['linesprocess'] / res['totalReg']) * 100)|int}}%" data-percent="{{((res['linesprocess'] / res['totalReg']) * 100)|int}}" style="width: {{((res['linesprocess'] / res['totalReg']) * 100)|int}}%;" data-original-title="{{((res['linesprocess'] / res['totalReg']) * 100)|int}}%"></div>
				</div>
				<p>Estado: {{res['status']}}</p>
				{%if res['status'] == "Finalizado"%}
				<div class="text-right">
					<a class="accordion-toggle collapsed btn btn-default" data-toggle="collapse" data-parent="#accordion2" href="#collapseInfo">
					  Ver Detalles
					</a>
				</div>
				{% endif %}
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div id="collapseInfo" class="accordion-body collapse" style="height: 0px;">
			<div class="span8 offset2">
				<div class="box">
					<div class="box-header">
						<span class="title">Resultado importación de contactos</span>
						<ul class="box-toolbar">
							<li><span class="icon"><i class="icon-folder-open-alt" style="font-size: 20px;"></i></span></li>
						</ul>
					</div>
					<div class="box-content">
						<table class="table table-normal">
							<thead></thead>
							<tbody>
								<tr class="status-info">
									<td class="icon"><i class="icon-cloud-upload"></i></td>
									<td>
										<span class="news-title">Contactos totales en archivo</span>
									</td>
									<td><b style="font-size: 20px;">{{res['linesprocess']}}</b></td>
								</tr>
								<tr class="status-success">
									<td class="icon"><i class="icon-ok"></i></td>
									<td>
										<span class="news-title">Importados exitosamente </span>
										<a href="{{ url('proccess/downoladsuccess/') }}{{ res['idProcces'] }}" target="_blank">(Descargar reporte)</a>
									</td>
									<td><b style="font-size: 20px;">{{res['import']}}</b></td>
								</tr>

								<tr class="status-warning">
									<td class="icon"><i class="icon-refresh"></i></td>
									<td>No importados porque ya existen</td>
									<td><b>{{res['exist']}}</b></td>
								</tr>

								<tr class="status-error">
									<td class="icon"><i class="icon-remove"></i></td>
									<td>No importados por correo inválido </td>
									<td><b>{{res['invalid']}}</b></td>
								</tr>

								<tr class="status-error">
									<td class="icon"><i class="icon-ban-circle"></i></td>
									<td>No importados por correo bloqueado </td>
									<td><b>{{res['bloqued']}}</b></td>
								</tr>

								<tr class="status-warning">
									<td class="icon"><i class="icon-random"></i></td>
									<td>No importados porque estan duplicados en el archivo </td>
									<td><b>{{res['repeated']}}</b></td>
								</tr>

								<tr class="status-pending">
									<td class="icon"><i class="icon-exclamation-sign"></i></td>
									<td>No importados por limite de contactos excedido </td>
									<td><b>{{res['limit']}}</b></td>
								</tr>
								<tr class="status-error">
									<td class="icon"><i class="icon-warning-sign"></i></td>
									<td>
										<span class="news-title">Total contactos no importados</span>
										<a href="{{ url('proccess/downoladerror/') }}{{ res['idProcces'] }}" target="_blank">(Descargar reporte)</a>
									</td>
									<td><b style="font-size: 20px;">{{res['Nimport']}}</b></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}