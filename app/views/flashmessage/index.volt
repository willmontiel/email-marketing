{% extends "templates/index_b3.volt" %}
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
		<div class="col-sm-12 text-right">
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
			{%for item in page.items%}
					<tr>
						<td>
							<div class="preview-message-{{item.type}}"></div>
							<div class="flashmessage-title">
								<h4>{{item.name}}</h4>
							</div>
							<div class="flashmessage-text">
								Fecha de creación: <strong>{{ date('d/M/Y',item.createdon)}}</strong><br />
								Inicio: <strong>{{ date('M/d/Y H:i',item.start)}}</strong> ,
								Fin: <strong>{{ date('M/d/Y H:i',item.end)}}</strong>
							</div>
						</td>
						<td class="text-right">
							<button class="ShowPreview btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple-preview" data-id="{{item.message}}"><span class="glyphicon glyphicon-eye-open"></span> Ver</button>
							<a href="{{url('flashmessage/edit')}}/{{item.idFlashMessage}}" class="btn btn-sm btn-default extra-padding"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
							<button class="ShowDialog btn btn-sm btn-default btn-delete extra-padding" data-toggle="modal" data-target="#modal-simple" data-id="{{url('flashmessage/delete')}}/{{item.idFlashMessage}}"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
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
			var preview = $(this).data('id');
			$("#content-preview").empty();
			$('#content-preview').append(preview);
		});
	</script>
{% endblock %}