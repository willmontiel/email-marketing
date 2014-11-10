{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function preview(id) {
			$.post("{{url('smartmanagment/preview')}}/" + id, function(preview){
				var e = preview.preview;
				$( "#preview-modal-content" ).empty();
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal-content').contents().find('body').append(e);
			});
		}
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'smartmanagent']) }}
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			{{flashSession.output()}}
		</div>
	</div>

	<div class="space-small"></div>

	{% if page.items|length != 0%}
		{#   parcial paginacion   #}	
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'smartmanagment/index']) }}

		{% for item in page.items %}
			<div class="mail-block">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5">
						<div class="mail-info">
							<div class="mail-name">
								<a href="{{url('smartmanagment/edit')}}/{{item.idSmartmanagment}}">{{item.name}}</a>
							</div>
							<div class="mail-detail" style="color: #777;">
								Creado el {{date('d/M/Y g:i a', item.createdon)}} 
							</div>
						</div>
					</div>
						
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
					</div>
						
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 text-right">
						<a href="#preview-modal" class="btn btn-sm btn-default tooltip-b3" data-toggle="modal" onClick="preview({{item.idSmartmanagment}});" data-placement="top" title="Ver previsualización">
							<span class="glyphicon glyphicon-eye-open"></span>
						</a>
							
						<a href="{{url('smartmanagment/edit')}}/{{item.idSmartmanagment}}" class="btn btn-sm btn btn-default tooltip-b3" data-placement="top" title="Editar esta gestión inteligente">
							<span class="glyphicon glyphicon-edit"></span>
						</a>
							
						<button class="ShowDialog btn btn-sm btn btn-danger tooltip-b3" data-toggle="modal" href="#modal-simple" data-id="{{ url('smartmanagment/delete') }}/{{item.idSmartmanagment}}" data-placement="top" title="Eliminar esta gestión inteligente">
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
				</div>
			</div>
		{% endfor %}

		{{ partial('partials/pagination_static_partial', ['pagination_url': 'smartmanagment/index']) }}
	{% else %}
		<div class="row">
			<div class="bs-callout bs-callout-warning">
				<h4>No hay ninguna gestión inteligente creada</h4>
				<p>
					Para empezar la creación de una nueva gestión inteligente <strong><a href="{{url('smartmanagment/new')}}">haga clic aqui</a></strong>
				</p>
			</div>
		</div>
	{% endif %}

	<div id="modal-simple" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Eliminar gestión inteligente</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Está seguro que desea eliminar esta gestión inteligente?
					</p>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteSmart" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	<div id="preview-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content modal-prevew-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Previsualización</h4>
				</div>
				<div class="modal-body modal-prevew-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteSmart").attr('href', myURL );
		});
	</script>
{% endblock %}	