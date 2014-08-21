{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		messages = [
			{% for item in page.items %}
					{id: {{item.idFlashMessage}}, msg: '{{item.message|json_encode}}' },
			{% endfor %}
		];
		
		function getMessagePreview(id) {
			for (var i = 0; i < messages.length; i++) {
				if (messages[i].id == id) {
					return messages[i].msg;
				}
			}
		}
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'flashmessage']) }}
		</div>
	</div>
	
	<div class="row">
		<h4 class="sectiontitle">Mensajes informativos</h4>

		<div class="bs-callout bs-callout-info">
			Configure mensajes informativos para que algunos o todos los clientes puedan verlos en
			el momento en que inician sesión
		</div>
	</div>

	<div class="row">
		{{flashSession.output()}}
	</div>
	
	<div class="row">
		<div class="text-right">
			<a href="{{url('flashmessage/new')}}" class="btn btn-sm btn-primary extra-padding"><span class="glyphicon glyphicon-plus"></span> Crear nuevo mensaje</a>
		</div>
	</div>

	<div class="row">
		{% if page.items|length == 0%}
			<div class="bs-callout bs-callout-warning">
				<h4>No hay mensajes administrativos</h4>
				<p>
					Para crear uno haga clic en el botón <strong>Crear nuevo mensaje</strong>
				</p>
			</div>
		{% else %}
			<table class="table table-contacts table-bordered">
				<thead></thead>
				<tbody>
					<tr>
						<th>Nombre</th>
						<th>Creado</th>
						<th>Inicio</th>
						<th>Fin</th>
						<th></th>
					</tr>
			{%for item in page.items%}
					<tr>
						<td><h4><span class="label label-{{item.type}}">°</span> {{item.name}}</h4></td>
						<td>{{ date('d/M/Y',item.createdon)}}</td>
						<td>{{ date('d/M/Y H:i',item.start)}}</td>
						<td>{{ date('d/M/Y H:i',item.end)}}</td>
							
						<td class="text-right">
							<button class="ShowPreview btn btn-sm btn-default" data-toggle="modal" data-target="#modal-simple-preview" data-id="{{item.idFlashMessage}}"><span class="glyphicon glyphicon-eye-open"></span></button>
							<a href="{{url('flashmessage/edit')}}/{{item.idFlashMessage}}" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
							<button class="ShowDialog btn btn-sm btn-default btn-delete " data-toggle="modal" data-target="#modal-simple" data-id="{{url('flashmessage/delete')}}/{{item.idFlashMessage}}"><span class="glyphicon glyphicon-trash"></span></button>
						</td>
					</tr>
			{% endfor %}
				</tbody>
			</table>
		</div>
		{% endif %}
{% if page.items|length != 0%}
	
	{#   paginacion sin ember   #}
	{{ partial('partials/pagination_static_partial', ['pagination_url': 'flashmessage/index']) }}
{% endif %}	

	<div class="modal fade" id="modal-simple-preview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Previsualización de mensaje</h4>
				</div>
				<div class="modal-body">
					<div id="content-preview"></div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal-simple" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Eliminar mensaje</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Está seguro que desea eliminar este mensaje?
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteMsg" class="btn btn-sm btn-default btn-delete extra-padding">Eliminar</a>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMsg").attr('href', myURL );
		});
	</script>
	
	<script type="text/javascript">
		$(document).on("click", ".ShowPreview", function () {
			var id = $(this).data('id');
			var message = getMessagePreview(id);
			$("#content-preview").empty();
			$('#content-preview').append(message);
		});
	</script>
{% endblock %}