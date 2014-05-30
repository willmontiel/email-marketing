{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function preview(id) {
			$.post("{{url('template/preview')}}/" + id, function(template){
				var e = template.template;
				$('#preview-modal').empty();
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal').contents().find('body').append(e);
			});
		}
	</script>
{% endblock %}
{% block content %}

{# Botones de navegacion #}
{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'template']) }}

	<div class="row">
		<div class="col-sm-12">
		<h4 class="sectiontitle">Plantillas</h4>
		<div class="bs-callout bs-callout-info">
			Administre plantillas que podrá usar como base en la creación de futuras campañas de correo.
		</div>
		</div>
	</div>		

	<div class="row">
		<div class="col-sm-12">
			{{ flashSession.output()}}
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 text-right">
			<a href="{{ url('template/new') }}" class="btn btn-default btn-sm extra-padding">
				Nueva Plantilla
			</a>
		</div>
	</div>
	
	<hr></hr>
	{#   tabs de plantillas   #}
	<div class="row">
		{% if arrayTemplate|length != 0%}
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
											<div class="preview-mail img-wrap">
												<div class="not-available">
													{% if t['preview'] == null%}
														<span class="glyphicon glyphicon-eye-close icon-not-available"></span>
														<label>Previsualización no disponible</label>
													{% else %}
														<img src="data: image/png;base64, {{t['preview']}}" />
													{%endif%}
													<a href="{{url('mail/contenteditor')}}/{{ (t['idMail'] != null)?t['idMail']:'template'}}/{{  t['id']  }}">
														<div class="img-info"><p style="font-size: 18px;"><i class="icon-ok"></i> Elegir</p></div>
													</a>
												</div>
											</div>
											<div class="clearfix"></div>
											<div class="space"></div>
											<div class="btn-group"  style="text-align: center;">
												<button class="btn btn-default" onClick="preview({{t['id']}})" title="Previsualizar" data-toggle="modal" data-target="#myModal">
													<span class="glyphicon glyphicon-eye-open"></span>
												</button>

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
		{% else %}
			<div class="col-sm-12">
				<div class="bs-callout bs-callout-warning">
					<h4>No hay plantillas predefinidas</h4>
					<p>
						Para empezar a crear una nueva plantilla haga clic en el botón <strong>Nuevo plantilla</strong> que se
						sitúa en la parte superior derecha.
					</p>
				</div>
			</div>
		{% endif %}
		
	</div>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content modal-prevew-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Previsualización de plantilla</h4>
				</div>
				<div class="modal-body modal-prevew-body" id="preview-modal">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	
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
	
	<div class="space"></div>
	<div class="clearfix"></div>
	
	<div class="space"></div>
	<div class="space"></div>
	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMail").attr('href', myURL );
		});
	</script>
{% endblock %}
