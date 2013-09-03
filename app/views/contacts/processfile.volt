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
				</div>
				<div class="box-content">
					<table class="table table-normal">
						<thead></thead>
						<tbody>
							<tr class="status-info">
								<td class="icon"><i class="icon-cloud-upload"></i></td>
								<td>
									<div class="news-title">
										<div class="accordion-group">
											<a class="accordion-toggle collapsed" data-toggle="collapse" href="#collapseOne">
												Contactos totales en archivo
											</a>
										</div>
										<div id="collapseOne" class="accordion-body collapse" style="height: 0px;">
											<div class="accordion-inner">
												<a href="#">Descargar resultados</a>
											</div>
										</div>
									</div>
								</td>
								<td><b style="font-size: 20px;">2015</b></td>
							</tr>
								
							<tr class="status-info">
								<td class="icon"><i class="icon-cloud-upload"></i></td>
								<td>
									<div class="news-title">
										<a href="#">Contactos totales en archivo</a>
									</div>
								</td>
								<td><b style="font-size: 20px;">2015</b></td>
							</tr>
							
							<tr class="status-success">
							  <td class="icon"><i class="icon-ok"></i></td>
							  <td>
								  <div class="news-title">
									  <a href="#">Importados exitosamente</a>
								  </div>
							  </td>
							  <td><b style="font-size: 20px;">1900</b></td>
							</tr>

							<tr class="status-warning">
							  <td class="icon"><i class="icon-refresh"></i></td>
							  <td><a href="#">No importados porque ya existen </a></td>
							  <td><b>50</b></td>
							</tr>

							<tr class="status-error">
							  <td class="icon"><i class="icon-remove"></i></td>
							  <td><a href="#">No importados por correo inválido </a></td>
							  <td><b>10</b></td>
							</tr>

							<tr class="status-error">
							  <td class="icon"><i class="icon-ban-circle"></i></td>
							  <td><a href="#">No importados por correo bloqueado </a></td>
							  <td><b>20</b></td>
							</tr>

							<tr class="status-warning">
							  <td class="icon"><i class="icon-random"></i></td>
							  <td><a href="#">Duplicados en el archivo </a></td>
							  <td><b>10</b></td>
							</tr>

							<tr class="status-pending">
							  <td class="icon"><i class="icon-exclamation-sign"></i></td>
							  <td><a href="#">No importados por limite de contactos excedido </a></td>
							  <td><b>25</b></td>
							</tr>
							<tr class="status-error">
							  <td class="icon"><i class="icon-warning-sign"></i></td>
							  <td>
								  <div class="news-title">
									  <a href="#">Total contactos no importados </a>
								  </div>
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