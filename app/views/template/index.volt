{% extends "templates/index_new.volt" %}
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
	<div class="row">
		<div class="span6">{{ flashSession.output()}}</div>
		<div class="span6 text-right">
			<a href="{{ url('mail') }}" class="btn btn-default">
				<i class="icon-plus"></i> Regresar a correos
			</a>
			<a href="{{ url('template/new') }}" class="btn btn-default">
				<i class="icon-plus"></i> Nueva Plantilla
			</a>
		</div>
	</div>
	<hr class="divider">
	<div class="row">
		<div class="span10">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Escoja una plantilla
					</div>
				</div>
				<div class="box-content">
					<div class="padded">
						<div class="tab-content">
							{% for category, template in arrayTemplate %}
								<div class="tab-pane {% if loop.first %}active{% else %}fade{% endif %}" id="{{category|change_spaces_in_between}}">
									<ul class="thumbnails">
										{% for t in template %}
										<li>
											<h5 style="text-align: center;">{{t['name']}}</h5>
											{#<a href="{{url('mail/editor')}}/{{mail.idMail}}/{{t['id']}}" class="thumbnail">#}
												<div class="preview-template img-wrap">
													{% if t['preview'] == null%}
														<div class="not-available-template"></div>
													{% else %}
														<img src="data: image/png;base64, {{t['preview']}}" />
													{%endif%}
													{% if t['idMail'] != null %}
														<a href="{{url('mail/editor')}}/{{t['idMail']}}/{{t['id']}}">
													{% else %}
														<a href="{{url('mail/setup')}}/0/{{t['id']}}/new">
													{% endif %}
															<div class="img-info-x2"><p><i class="icon-ok"></i> Elegir</p></div>
														</a>
												</div>
											</a>
											<div class="btn-toolbar" style="text-align: center !important;">
												<div class="btn-group template-tools">
													<a href="#preview-modal" data-toggle="modal" onClick="preview({{t['id']}})" class="btn btn-default" title="Previsualizar"><i class="icon-eye-open"></i></a>
													{% if t['idMail'] == null %}
														{% if t['idAccount'] == null%}
															{% if userObject.userrole == 'ROLE_SUDO'%}
																<a href="{{url('template/edit')}}/{{t['id']}}" data-toggle="modal" onClick="preview({{t['id']}})" class="btn btn-default" title="Editar"><i class="icon-edit"></i></a>
																<a class="ShowDialog btn btn-default" data-backdrop="static" data-toggle="modal" href="#modal-simple" data-id="{{url('template/delete')}}/{{t['id']}}" title="Eliminar"><i class="icon-trash"></i></a>
															{% endif %}
														{% else%}
															<a href="{{url('template/edit')}}/{{t['id']}}" data-toggle="modal" onClick="preview({{t['id']}})" class="btn btn-default" title="Editar"><i class="icon-edit"></i></a>
															<a class="ShowDialog btn btn-default" data-backdrop="static" data-toggle="modal" href="#modal-simple" data-id="{{url('template/delete')}}/{{t['id']}}" title="Eliminar"><i class="icon-trash"></i></a>
														{% endif %}
													{% endif %}
												</div>
											</div>
										</li>
										{% endfor %}
									</ul>
								</div>
							{% endfor %}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Categorías
					</div>
				</div>
				<div class="box-content">
					<div class="padded">
						<ul class="nav nav-pills nav-stacked nav-template">
							{% for category, template in arrayTemplate %}
								<li class="{% if loop.first %}active{% endif %}"><a href="#{{category|change_spaces_in_between}}" data-toggle="tab">{{category}}</a></li> 
							{% endfor %}
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="span12 padded">
			{#<a href="{{url('mail/source')}}/{{mail.idMail}}" class="btn btn-default"><i class="icon-circle-arrow-left"></i> Anterior</a>#}
		</div>
	</div>
	
	<div id="preview-modal" class="modal hide fade preview-modal">
	</div>
	
	<div id="modal-simple" class="modal hide fade" aria-hidden="false">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <h6 id="modal-tablesLabel">Eliminar plantilla</h6>
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
		  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
		  <a href="" id="deleteMail" class="btn btn-danger" >Eliminar</a>
		</div>
	</div>
	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMail").attr('href', myURL );
		});
	</script>
{% endblock %}
