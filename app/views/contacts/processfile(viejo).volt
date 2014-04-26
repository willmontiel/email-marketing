{% extends "templates/index_new.volt" %}
{% block sectiontitle %}Reporte de importación de contactos{% endblock %}
{% block content %}
	<div class="row">
		<h4 class="sectiontitle">Resumen de importación de contactos</h4>
		<div class="well relative">
			Este es el resultado de la importación de contactos que ha sido efectuada, en donde podrá consultar,
			la totalidad de contactos que tenia el archivo importado, los contactos efectivos (importados
			exitosamente), y los contactos que no pudieron ser procesados correctamente y la razón de ello.
		</div>
		<div class="col-md-1"></div>
		<div class="col-md-4">
			<div class="col-md-10 report">
				<h4 class="sectiontitle">Importación de contactos</h4>
			</div>
			<div class="col-md-1 mini-icon pull-right">
				<span class="glyphicon glyphicon-eye-open"></span>
			</div>
		</div>
		<table class="table table-normal">
			<thead></thead>
			<tbody>
				<tr class="status-info">
					<td class="icon"><i class="icon-cloud-upload"></i></td>
					<td>
						<span class="news-title">Contactos totales en archivo</span>
					</td>
					<td><b style="font-size: 20px;">{{count['total']}}</b></td>
				</tr>
				<tr class="status-success">
					<td class="icon"><i class="icon-ok"></i></td>
					<td>
						<span class="news-title">Importados exitosamente </span>
						<a href="{{ url('process/downoladsuccess/') }}{{ count['idProcess'] }}" target="_blank">(Descargar reporte)</a>
					</td>
					<td><b style="font-size: 20px;">{{count['import']}}</b></td>
				</tr>

				<tr class="status-warning">
					<td class="icon"><i class="icon-refresh"></i></td>
					<td>No importados porque ya existen</td>
					<td><b>{{count['exist']}}</b></td>
				</tr>

				<tr class="status-error">
					<td class="icon"><i class="icon-remove"></i></td>
					<td>No importados por correo inválido </td>
					<td><b>{{count['invalid']}}</b></td>
				</tr>

				<tr class="status-error">
					<td class="icon"><i class="icon-ban-circle"></i></td>
					<td>No importados por correo bloqueado </td>
					<td><b>{{count['bloqued']}}</b></td>
				</tr>

				<tr class="status-warning">
					<td class="icon"><i class="icon-random"></i></td>
					<td>No importados porque estan duplicados en el archivo </td>
					<td><b>{{count['repeated']}}</b></td>
				</tr>

				<tr class="status-pending">
					<td class="icon"><i class="icon-exclamation-sign"></i></td>
					<td>No importados por limite de contactos excedido </td>
					<td><b>{{count['limit']}}</b></td>
				</tr>
				<tr class="status-error">
					<td class="icon"><i class="icon-warning-sign"></i></td>
					<td>
						<span class="news-title">Total contactos no importados</span>
						<a href="{{ url('process/downoladerror/') }}{{ count['idProcess'] }}" target="_blank">(Descargar reporte)</a>
					</td>
					<td><b style="font-size: 20px;">{{count['Nimport']}}</b></td>
				</tr>
			</tbody>
		</table>
	</div>
{% endblock %}