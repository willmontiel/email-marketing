{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		var MyBaseURL = '{{urlManager.getBaseUri(true)}}';
		
		function inProcess(x, text) {
			$('#' + x).empty();
			$('#' + x).removeClass("red");
			$('#' + x).addClass("blue");
			$('#' + x).append('<td></div><img src="' + MyBaseURL + 'images/loading4.GIF" height="30" width="30"></td><td>' + text +'</td><td>En proceso</td>');
		}
		
		function done(x, text) {
			$('#' + x).empty();
			$('#' + x).removeClass("blue");
			$('#' + x).addClass("green");
			$('#' + x).append('<td></div><span class="glyphicon glyphicon-ok-circle"></span></td><td>' + text + '</td><td>Hecho</td>');
		}
		
		function waiting(x, text) {
			$('#' + x).empty();
			$('#' + x).addClass("red");
			$('#' + x).append('<td></div><span class="glyphicon glyphicon-remove-circle"></span></td><td>' + text + '</td><td><div class="status">Esperando</div></td>');
		}
		
		function loadNow(idProcess) {   
			$.getJSON(MyBaseURL + 'process/refreshimport/' + idProcess, function(data){
				if(data.length !== 0) {
					updateView(data.status);
					if (data.status === 'Finalizado' || data.status === 'Cancelado') {
						finished = true;
						location.reload(MyBaseURL + 'process/refreshimport/' + idProcess);
					}
				}
			});
		};
		
		function updateView(status) {
			switch (status) {
				case 'Pendiente':
				case 'En ejecución':
					inProcess('1', 'Cargando servicios');

					waiting('2', 'Validando registros');
					waiting('3', 'Cargando registros en la lista');
					waiting('4', 'Finalizado');
					break;

				case 'Preprocesando registros':
					done('1', 'Cargando servicios');
					inProcess('2', 'Validando registros');
					waiting('3', 'Cargando registros en la lista');
					waiting('4', 'Finalizado');
					break;

				case 'Mapeando contactos':
				case 'Cargando registros en base de datos':
				case 'Actualizando campos personalizados':
					done('1', 'Cargando servicios');
					done('2', 'Validando registros');
					inProcess('3', 'Cargando registros en la lista');
					waiting('4', 'Finalizado');
					break;

				case 'Finalizado':
					done('1', 'Cargando servicios');
					done('2', 'Validando registros');
					done('3', 'Cargando registros en la lista');
					done('4', 'Finalizado');
					$('#details').show();
					break;
				
				case 'Cancelado':
					waiting('1', 'Cargando servicios');
					waiting('2', 'Validando registros');
					waiting('3', 'Cargando registros en la lista');
					waiting('4', 'Finalizado');
					$('.status').empty();
					$('.status').append('Cancelado');
					break;
			}
		}
		
		$(function() {
			if('{{process['status']}}' !== 'Finalizado' && '{{process['status']}}' !== 'Cancelado') {
				loadNow({{process['idProcess']}});
				setInterval(loadNow, 3000);
			}
			else {
				updateView('{{process['status']}}');
			}
		});
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
				<tr id="1">
					<td><span class="glyphicon glyphicon-remove-circle"></span></td>
					<td>Cargando servicios</td>
					<td>En proceso</td>
				</tr>
				
				<tr id="2">
					<td><span class="glyphicon glyphicon-remove-circle"></span></td>
					<td>Validando registros</td>
					<td>Esperando</td>
				</tr>
				
				<tr id="3">
					<td><span class="glyphicon glyphicon-remove-circle"></span></td>
					<td>Cargando registros en la lista</td>
					<td>Esperando</td>
				</tr>
				
				<tr id="4">
					<td><span class="glyphicon glyphicon-remove-circle"></span></td>
					<td>Finalizado</td>
					<td>Esperando</td>
				</tr>
			
				<tr>
					<td colspan="3" class="text-center">
						<div id="details" style="display: none;">
							<div class="space"></div>
							<a class="accordion-toggle collapsed btn btn-sm btn-default extra-padding btn-for-modal-accordion" data-toggle="collapse" data-parent="#accordion2" href="#collapseInfo">
								Ver detalles
							</a>
						</div>
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
											Total contactos no importados <a href="{{ url('process/downoladerror/') }}{{ process['idProcess'] }}" target="_blank">(Descargar reporte)</a>
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
	<div class="space"></div>
{% endblock %}