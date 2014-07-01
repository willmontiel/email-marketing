{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
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
					var columns = '	<td>\n\
										<input type="text" onClick="this.select();" class="form-control" value="' + obj.APIKey.apikey + '" />\n\
									</td>\n\
									<td>\n\
										<input type="text" onClick="this.select();" class="form-control" value="' + obj.APIKey.secret + '" />\n\
									</td>\n\
									<td>\n\
										<select id="status-apikey" name="status" class="form-control">\n\
											<option value="enable" selected>Activo</option>\n\
											<option value="disable">Inactivo</option>\n\
										</select>\n\
									</td>\n\
									<td>\n\
										<a class="btn btn-sm btn-default btn-guardar extra-padding" onclick="createAPIKey(' + obj.APIKey.idUser + ');"><span class="glyphicon glyphicon-repeat"></span></a>\n\
										<a class="btn btn-delete btn-sm btn-default extra-padding" onclick="createAPIKey(' + obj.APIKey.idUser + ');"><span class="glyphicon glyphicon-trash"></span></a>\n\
									</td>';
					row.append($(columns));
				}
			});
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
					<td>Estatus</td>
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
						<td>
							<input type="text" onClick="this.select();" class="form-control" value="{{item.apikey.apikey}}" />
						</td>
						<td>
							<input type="text" onClick="this.select();" class="form-control" value="{{item.apikey.secret}}" />
						</td>
						<td>
							<select id="status-apikey" name="status" class="form-control">
								<option value="enable" {%if item.apikey.status == 'Enable'%} selected {%endif%}>Activo</option>
								<option value="disable"{%if item.apikey.status == 'Disable'%} selected {%endif%}>Inactivo</option>
							</select>
						</td>
						<td>
							<a class="btn btn-sm btn-default btn-guardar extra-padding" onclick="createAPIKey({{item.idUser}});"><span class="glyphicon glyphicon-repeat"></span></a>
							<a class="btn btn-delete btn-sm btn-default extra-padding" onclick="createAPIKey({{item.idUser}});"><span class="glyphicon glyphicon-trash"></span></a>
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
{% endblock %}