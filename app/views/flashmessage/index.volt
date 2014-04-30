{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-bullhorn"></i> Mensajes administrativos{%endblock%}
{% block sectionsubtitle %}Lista de mensajes administrativos activos e inactivos{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'flashmessage']) }}
		</div>
	</div>
	
	<div class="row">
		<h4 class="sectiontitle">Mensajes informativos</h4>

		<div class="bs-callout bs-callout-info">
			Aqui podrá configurar mensajes informativos, para que determinados clientes o todos puedan verlos en
			el momento en el que inician sesión
		</div>
	</div>

	<div class="row">
		<div class="col-md-6">
			{{flashSession.output()}}
		</div>
		<div class="col-md-6">
			<a href="{{url('flashmessage/new')}}" class="btn btn-sm btn-primary extra-padding pull-right">Crear nuevo mensaje</a>
		</div>
	</div>
	
	<br />

	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead></thead>
				<tbody>
			{%for item in page.items%}
					<tr>
						<td>
							<div class="preview-message-{{item.type}}"></div>
						</td>
						<td>
							<h4>{{item.name}}</h4>
							Fecha de creación: <strong>{{ date('d/M/Y',item.createdon)}}</strong><br />
							Inicio: <strong>{{ date('M/d/Y H:i',item.start)}}</strong>, Fin: <strong>{{ date('M/d/Y H:i',item.end)}}</strong>
						</td>
						<td class="pull-right">
							<a href="#" class="btn btn-green" rel="tooltip" data-placement="left" title="" data-original-title="{{item.message}}">Ver</a>
							<a href="{{url('flashmessage/edit')}}/{{item.idFlashMessage}}" class="btn btn-sm btn-default extra-padding">Editar</a>
							<button class="ShowDialog btn btn-sm btn-danger extra-padding" data-toggle="modal" data-target="#modal-simple" data-id="{{url('flashmessage/delete')}}/{{item.idFlashMessage}}">Eliminar</button>
						</td>
					</tr>
			{% endfor %}
				</tbody>
			</table>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 text-center">
			{{ partial('partials/pagination_static_partial', ['pagination_url': 'flashmessage/index']) }}
		</div>
	</div>
	
	<div class="modal fade" id="modal-simple" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Eliminar Mensaje</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Esta seguro que desea eliminar este mensaje.?
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteMsg" class="btn btn-sm btn-danger extra-padding">Eliminar</a>
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
{% endblock %}