{% extends "templates/index_new.volt" %}
{% block content %}
	<div class="row-fluid">
		<div class="span12">
			<h3>Reporte de importación de contactos</h3>
		</div>
	</div>
	<br>
	<div class="row-fluid">
		<div class="span12">
			<p>
				Este es el resultado de la importación de contactos que ha sido efectuada, en donde podrá consultar,
				la totalidad de contactos que tenia el archivo importado, los contactos efectivos (importados
				exitosamente), y los contactos que no pudieron ser procesados correctamente y la razón de ello.
				
			</p>
		</div>
	</div>
	<br>
	<div class="row-fluid">
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
								<td><b style="font-size: 20px;">2015</b></td>
							</tr>
							<tr class="status-success">
								<td class="icon"><i class="icon-ok"></i></td>
								<td>
									<span class="news-title">Importados exitosamente </span>
									<a href="#">(Descargar reporte)</a>
								</td>
								<td><b style="font-size: 20px;">1900</b></td>
							</tr>

							<tr class="status-warning">
								<td class="icon"><i class="icon-refresh"></i></td>
								<td>No importados porque ya existen</td>
								<td><b>50</b></td>
							</tr>

							<tr class="status-error">
								<td class="icon"><i class="icon-remove"></i></td>
								<td>No importados por correo inválido </td>
								<td><b>10</b></td>
							</tr>

							<tr class="status-error">
								<td class="icon"><i class="icon-ban-circle"></i></td>
								<td>No importados por correo bloqueado </td>
								<td><b>20</b></td>
							</tr>

							<tr class="status-warning">
								<td class="icon"><i class="icon-random"></i></td>
								<td>Duplicados en el archivo </td>
								<td><b>10</b></td>
							</tr>

							<tr class="status-pending">
								<td class="icon"><i class="icon-exclamation-sign"></i></td>
								<td>No importados por limite de contactos excedido </td>
								<td><b>25</b></td>
							</tr>
							<tr class="status-error">
								<td class="icon"><i class="icon-warning-sign"></i></td>
								<td>
									<span class="news-title">Total contactos no importados</span>
									<a href="#">(Descargar reporte)</a>
								</td>
								<td><b style="font-size: 20px;">115</b></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			
		</div>
	</div>
{% endblock %}