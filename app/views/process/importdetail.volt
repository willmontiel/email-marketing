{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">

		
		
	</script>
{% endblock %}
{% block content %}
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'import']) }}
	
	<div class="row">
		<h4 class="sectiontitle">Lista de importaciones</h4>
	</div>
	
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<table class="table table-contacts report-import table-condensed table-striped">
				<tr class="green">
					<td><span class="glyphicon glyphicon-ok-circle"></span></td>
					<td>Validando registros</td>
					<td>Hecho</td>
				</tr>
				
				<tr class="blue">
					<td><img src="{{url('')}}images/loading1.gif" height="30" width="30"></td>
					<td>Mapeando contactos</td>
					<td>En proceso</td>
				</tr>
				
				<tr class="red">
					<td><span class="glyphicon glyphicon-remove-circle"></td>
					<td>Cargando registros en la lista</td>
					<td>Esperando</td>
				</tr>
				
				<tr class="red">
					<td><span class="glyphicon glyphicon-remove-circle"></td>
					<td>Actualizando campos personalizados</td>
					<td>Esperando</td>
				</tr>
				
				<tr class="red">
					<td><span class="glyphicon glyphicon-remove-circle"></td>
					<td>Finalizado</td>
					<td>Esperando</td>
				</tr>
				
				<tr>
					<td colspan="3" class="text-center">
						<a class="accordion-toggle collapsed btn btn-sm btn-default extra-padding btn-for-modal-accordion" data-toggle="collapse" data-parent="#accordion2" href="#collapseInfo">
							Ver detalles
						</a>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12">
			<div id="collapseInfo" class="accordion-body collapse" style="height: 0px;">
				<div class="container-fluid">
					<div class="box">
						<div class="box-content col-md-12">
							<h4>Resumen de importación del archivo: nombre del archivo a la lista: {{process['name']}}</h4>
							
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
											{{process['linesprocess']}}
										</td>
									</tr>
									<tr class="green">
										<td>
											<span class="glyphicon glyphicon-ok-circle"></span>
										</td>
										<td>
											Importados exitosamente <a href="{{ url('process/downoladsuccess/') }}{{ process['idProcess'] }}" target="_blank">(Descargar reporte)</a>
										</td>
										<td class="big-number text-right">
											{{process['import']}}
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
											{{process['exist']}}
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
											{{process['invalid']}}
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
											{{process['bloqued']}}
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
											{{process['repeated']}}
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
											{{process['limit']}}
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
											{{process['Nimport']}}
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
		</div>
	</div>
{% endblock %}