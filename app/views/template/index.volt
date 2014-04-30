{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function preview(id) {
			$.post("{{url('template/preview')}}/" + id, function(template){
				var e = template.template;
				$( "#preview-modal" ).empty();
				$('#preview-modal').append('<span class="close-preview icon-remove icon-2x" data-dismiss="modal"></span>');
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal').contents().find('body').append(e);
			});
		}
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-magic"></i>Plantillas globales y locales{% endblock %}
{% block sectionsubtitle %}Listado de plantillas creadas en la cuenta{% endblock %}
{% block content %}

{# Botones de navegacion #}
{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'template']) }}

	<div class="row">
		<h4 class="sectiontitle">Plantillas</h4>

		{{ flashSession.output()}}

		<div class="pull-right">
{#			<a href="{{ url('mail/list') }}" class="btn btn-default btn-sm extra-padding">
				<i class="icon-plus"></i> Regresar a correos
			</a>
#}
			<a href="{{ url('template/new') }}" class="btn btn-default btn-sm extra-padding">
				<i class="icon-plus"></i> Nueva Plantilla
			</a>
		</div>
			

	<div class="clearfix"></div>
	<hr></hr>
	{#   tabs de plantillas   #}
	<div class="row">
		<div class="col-md-9">
			<div class="tab-content">
				{% for category, template in arrayTemplate %}
					<div class="tab-pane {% if loop.first %}active{% else %}fade{% endif %}" id="{{category|change_spaces_in_between}}">
						<div class="thumbnails">
							<div class="row">
								{% for t in template %}
								<div class="item-thumb col-xs-6 col-md-3">
									<div class="container-fluid">
										<h5>{{t['name']}}</h5>
										<div class="preview-template img-wrap">
											{% if t['preview'] == null%}
												<div class="not-available-template"></div>
											{% else %}
												<img src="data: image/png;base64, {{t['preview']}}" />
											{%endif%}
											<a href="{{url('mail/contenteditor')}}/{{ (t['idMail'] != null)?t['idMail']:'template'}}/{{  t['id']  }}">
												<div class="img-info-x2"><p><i class="icon-ok"></i> Elegir</p></div>
											</a>
										</div>
										<div class="clearfix"></div>
										<div class="btn-group">
											<a href="#preview-modal" data-toggle="modal" onClick="preview({{t['id']}})" class="btn btn-default" title="Previsualizar"><span class="glyphicon glyphicon-eye-open"></span></a>
											{% if t['idMail'] == null %}
												{% if t['idAccount'] == null%}
													{% if userObject.userrole == 'ROLE_SUDO'%}
														<a href="{{url('template/edit')}}/{{t['id']}}" data-toggle="modal" onClick="preview({{t['id']}})" class="btn btn-default" title="Editar">
															<span class="glyphicon glyphicon-pencil"></span>
														</a>


														<a class="ShowDialog btn btn-default" data-backdrop="static" data-toggle="modal" href="#modal-simple" data-id="{{url('template/delete')}}/{{t['id']}}" title="Eliminar">
															<span class="glyphicon glyphicon-trash"></span>
														</a>

													{% endif %}
												{% else%}
													<a href="{{url('template/edit')}}/{{t['id']}}" data-toggle="modal" onClick="preview({{t['id']}})" class="btn btn-default" title="Editar">
														<span class="glyphicon glyphicon-pencil"></span>
													</a>

													<button class="ShowDialog btn btn-default" data-toggle="modal" data-target="#modal-simple" data-id="{{url('template/delete')}}/{{t['id']}}" title="Eliminar">
														<span class="glyphicon glyphicon-trash"></span>
													</button>
												{% endif %}
											{% endif %}
										</div>
									</div>
								</div>
								{% endfor %}
							</div>
						</div>
					</div>
				{% endfor %}
			</div>
		</div>
		{#   Selector de plantillas a mostrar   #}
		<div class="col-md-3 border-left">
			<h4>Categorías</h4>
			<ul class="nav nav-pills nav-stacked nav-template">
				{% for category, template in arrayTemplate %}
					<li class="{% if loop.first %}active{% endif %}"><a href="#{{category|change_spaces_in_between}}" data-toggle="tab">{{category}}</a></li> 
				{% endfor %}
			</ul>
		</div>
	</div>
	
	<div id="preview-modal" class="modal hide fade preview-modal"></div>

	<div class="modal fade" id="modal-simple" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Eliminar plantilla</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Esta seguro que desea eliminar esta plantilla?
					</p>
					<p>
						Recuerde que si elimina esta plantilla se perderán todos los datos asociados, excepto las imágenes
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<a href="" id="deleteMail" class="btn btn-danger" >Eliminar</a>
				</div>
			</div>
		</div>
	</div>

	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMail").attr('href', myURL );
		});
	</script>
{% endblock %}
