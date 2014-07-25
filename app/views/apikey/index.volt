{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('vendors/bootstrap_v2/switch/js/bootstrap-switch.js')}}
	{{ stylesheet_link('vendors/bootstrap_v2/switch/css/bootstrap3/bootstrap-switch.css') }}
	<script type="text/javascript">
		
		$(function() {
			eventStatus();
		});
		
		function createAPIKey(id) 
		{
			$.ajax({
				url: "{{url('apikey/create')}}/" + id,
				type: "POST",
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg.statusText, sticky: false, time: 30000});
				},
				success: function(obj){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: 'Se ha creado la API Key exitosamente', sticky: false, time: 30000});
					var row = $('#row-apikey-' + obj.APIKey.idUser);
					row.find('td:last').remove();
					var status = (obj.APIKey.status === 'enabled') ? 'checked' : '';
					var columns =	'<td id="col-apikey-for-' + obj.APIKey.idUser + '">\n\
										<input type="text" onClick="this.select();" class="form-control" value="' + obj.APIKey.apikey + '" />\n\
									</td>\n\
									<td id="col-secret-for-' + obj.APIKey.idUser + '">\n\
										<input type="text" onClick="this.select();" class="form-control" value="' + obj.APIKey.secret + '" />\n\
									</td>\n\
									<td id="col-status-for-' + obj.APIKey.idUser + '">\n\
										<input type="checkbox" class="apikey-status" data-id="' + obj.APIKey.idUser + '" ' + status + '>\n\
									</td>\n\
									<td id="col-actions-for-' + obj.APIKey.idUser + '">\n\
										<a class="btn btn-sm btn-default btn-guardar extra-padding" onclick="remakeAPIKey(' + obj.APIKey.idUser + ');"><span class="glyphicon glyphicon-repeat"></span></a>\n\
										<a data-toggle="modal" href="#modal-simple" class="btn btn-delete btn-sm btn-default extra-padding" onclick="deleteAPIKey(' + obj.APIKey.idUser + ');"><span class="glyphicon glyphicon-trash"></span></a>\n\
									</td>';
					row.append($(columns));
					
					eventStatus();
				}
			});
		}
		
		function preventRemakeAPIKey(id) {$('#remakeApiKey').data('id', id);}
		
		function remakeAPIKey() 
		{
			var id = $('#remakeApiKey').data('id');
			$.ajax({
				url: "{{url('apikey/remake')}}/" + id,
				type: "POST",
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg.statusText, sticky: false, time: 30000});
				},
				success: function(obj){
					$('#col-apikey-for-' + obj.APIKey.idUser).find('input').val(obj.APIKey.apikey);
					$('#col-secret-for-' + obj.APIKey.idUser).find('input').val(obj.APIKey.secret);
					var status = (obj.APIKey.status === 'enabled') ? true : false;
					$('#col-status-for-' + obj.APIKey.idUser + ' input.apikey-status').bootstrapSwitch('state', status);
				}
			});
		}
		
		function eventStatus()
		{
			$(".apikey-status").bootstrapSwitch();
			
			$('input.apikey-status').on('switchChange.bootstrapSwitch', function(event, state) {
				changeStatus($(this).data('id'), state);
			});
		}
		
		function changeStatus(id, state)
		{
			$.ajax({
				url: "{{url('apikey/changestatus')}}/" + id,
				type: "POST",
				data: { state: state},
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg.statusText, sticky: false, time: 30000});
				},
				success: function(obj){
					var msg = (obj.APIKey.status === 'enabled') ? 'La API Key del usuario ' + obj.APIKey.firstname + ' ' + obj.APIKey.lastname + ' ha sido habilitada' : 'La API Key del usuario ' + obj.APIKey.firstname + ' ' + obj.APIKey.lastname + ' ha sido deshabilitada' ;
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg, sticky: false, time: 30000});
				}
			});
		}
		
		function deleteAPIKey(id)
		{
			var myURL = "{{ url('apikey/delete/') }}" + id;
			$("#deleteApiKey").attr('href', myURL );
		}
		
	</script>
{% endblock %}
{% block content %}
		{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'footer']) }}

	<div class="row">
		<h4 class="sectiontitle">API Keys</h4>
		<div class="bs-callout bs-callout-info">
			<p>La clave API es obligatoria para acceder a los servicios web de Email Sigma.</p>
			<p>La clave secreta debería guardarse muy bien, como la contraseña; no la compartas con nadie.</p>
		</div>
	</div>

	<div class="row">
		{{ flashSession.output() }}
	</div>

	<div class="row">
		<table class="table table-contacts table-striped">
			<thead>
				<tr>
					<td></td>
					<td>Tipo de usuario</td>
					<td>Api Key</td>
					<td>Secreto</td>
					<td>Estado</td>
					<td></td>
				</tr>
			</thead>
			<tbody>
				{%for item in page.items%}
					<tr id="row-apikey-{{item.idUser}}">
						<td>
							<div class="box-section news with-icons">
								<div class="avatar blue">
									<i class="icon-user icon-2x"></i>
								</div>
								<div class="news-content">
									<div class="news-title">
										<strong>{{item.username}}</strong>
									</div>
									<div class="news-text">
										{{item.firstName}} {{item.lastName}}<br />
										{{item.email}}
									</div>
								</div>
							</div>
						</td>
						<td>{{item.userrole}}</td>
						
						{%if item.apikey.apikey is defined%}
						
						<td id="col-apikey-for-{{item.idUser}}">
							<input type="text" onClick="this.select();" class="form-control" value="{{item.apikey.apikey}}" />
						</td>
						<td id="col-secret-for-{{item.idUser}}">
							<input type="text" onClick="this.select();" class="form-control" value="{{item.apikey.secret}}" />
						</td>
						<td id="col-status-for-{{item.idUser}}">
							<input type="checkbox" class="apikey-status" data-id="{{item.idUser}}" {% if item.apikey.status == 'enabled'%} checked {%endif%}>
						</td>
						<td id="col-actions-for-{{item.idUser}}">
							<a data-toggle="modal" href="#modal-simple-remake" title="Regenerar API Key" onclick="preventRemakeAPIKey({{item.idUser}});" class="btn btn-sm btn-default btn-guardar extra-padding"><span class="glyphicon glyphicon-repeat"></span></a>
							<a data-toggle="modal" href="#modal-simple" title="Eliminar API Key" onclick="deleteAPIKey({{item.idUser}});" class="btn btn-sm btn-default btn-delete  extra-padding"><span class="glyphicon glyphicon-trash"></span></a>
						</td>
						
						{% else %}
						
						<td colspan="3">
							<a class="btn btn-sm btn-default btn-guardar extra-padding" onclick="createAPIKey({{item.idUser}});">Crear API Key</a>
						</td>
						
						{% endif %}
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>
	
	
	{#  Este es el modal (lightbox) que se activa al hacer clic en el boton eliminar   #}
	<div id="modal-simple" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Eliminar API KEY</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Está seguro que desea eliminar esta API KEY?
					</p>
					<p>
						Recuerde que si elimina esta API KEY las herramientas de configuración externas dejarán de funcionar.
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteApiKey" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
				</div>
			</div>
		</div>
	</div>
	
	
	{#  Este es el modal (lightbox) que se activa al hacer clic en el boton de recrear   #}
	<div id="modal-simple-remake" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h4 class="modal-title">Regenerar API Keys</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Está seguro que desea regenerar esta API KEY?
					</p>
					<p>
						Recuerde que si regenera esta API KEY las herramientas de configuración externas no funcionaran con la API Key anterior.
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a onclick="remakeAPIKey();" id="remakeApiKey" class="btn btn-sm btn-default btn-guardar extra-padding" data-dismiss="modal">Generar</a>
				</div>
			</div>
		</div>
	</div>
	
{% endblock %}