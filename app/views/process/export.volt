{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}

	{% if export.status != 'Finalizado' OR export.status != 'Cancelado'%}
		<script type="text/javascript">
			var baseurl = '{{urlManager.getBaseUri(true)}}';

			var refresher = setInterval(loadNow, 3000);

			function loadNow () { 
				$.getJSON(baseurl + 'process/resfreshexport/' + {{export.idExportfile}}, function(data){
					if(data.length !== 0) {
						$('#contacts-processed').empty();
						$('#contacts-processed').append(data.contactsProcessed);
						
						$('#contacts-to-process').empty();
						$('#contacts-to-process').append(data.contactsToProcess);
						
						if (data.status === 'Finalizado') {
							$('#export-status').empty();
							$('#export-status').append(data.status);
							$('#file-available').show('slow');
							clearInterval(refresher);
						}
						else if (data.status === 'Cancelado') {
							$('#export-status').empty();
							$('#export-status').append(data.status);
							clearInterval(refresher);
						}
					}
				});
			};
		</script>
	{% endif %}
{% endblock %}
{% block content %}
	{% if export.fields == 'custom-fields'%}
		{% set ctype = 'Si' %}
	{% else %}
		{% set ctype = 'No' %}
	{% endif %}
		
	{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'export']) }}
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="header">
					<div class="title">Resumen de exportación de contactos</div>
					<div class="title-info">Podría tardar unos minutos</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-8 col-sm-offset-2 col-md-8 col-md-offset-2 col-lg-8 col-lg-offset-2">
			<div class="header-background">
				<table class="table table-striped table-bordered">
					<thead></thead>
					<tbody>
						<tr>
							<td><strong>{{criteria}}</strong></td>
							<td>{{model.name}}</td>
						</tr>
						<tr>
							<td><strong>Tipo de contactos</strong></td>
							<td>{{export.contactStatus}}</td>
						</tr>
						<tr>
							<td><strong>Campos personalizados</strong></td>
							<td>{{ctype}}</td>
						</tr>
						<tr>
							<td><strong>Contactos a procesar(Aprox)</strong></td>
							<td>{% if export.status == 'Finalizado' OR export.status == 'Cancelado'%}
									{{export.contactsToProcess}}
								{% else %}
									<div id="contacts-to-process">0</div>
								{% endif %}</td>
						</tr>
						<tr>
							<td><strong>Contactos procesados(Aprox)</strong></td>
							<td>
								{% if export.status == 'Finalizado' OR export.status == 'Cancelado'%}
									{{export.contactsProcessed}}
								{% else %}
									<div id="contacts-processed">0</div>
								{% endif %}
							</td>
						</tr>
						<tr>
							<td><strong>Estado</strong></td>
							<td>
								{% if export.status == 'Finalizado' OR export.status == 'Cancelado'%}
									{{export.status}}
								{% else %}
									<div id="export-status">{{export.status}}</div>
								{% endif %}
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
	<div class="row" id="file-available" style="display: {% if export.status == 'Finalizado' %}block{%else%}none{% endif %};">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
			<div class="header-background">
				<div class="header">
					<div class="title">
						<a href="{{url('contacts/getexportfile')}}/{{export.idExportfile}}">
							Haga click aqui para descargar
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="space"></div>
{% endblock %}