{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function preview(id) {
			$.post("{{url('template/preview')}}/" + id, function(template){
				var e = template.template;
				$("#modal-body-preview").empty();
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#modal-body-preview').contents().find('body').append(e)
			});
		}
	</script>
	{{ super() }}
{% endblock %}
{% block content %}
	<h4 class="sectiontitle">Plantillas predeterminadas</h4>
		<div class="col-md-2">
			<ul class="nav nav-pills nav-stacked">
			   {% for category, template in arrayTemplate %}
				   <li class="{% if loop.first %}active{% endif %}"><a href="#{{category|change_spaces_in_between}}" data-toggle="tab">{{category}}</a></li> 
			   {% endfor %}
		   </ul>
		</div>
		<div class="col-md-10">
			<div class="tab-content">
				{% for category, template in arrayTemplate %}
					<div class="tab-pane {% if loop.first %}active {% else %} fade{% endif %}" id="{{category|change_spaces_in_between}}">
						<div class="row">
						   {% for t in template %}
								<div class="col-xs-6 col-md-3">
									<div class="thumbnail thumn-hight">
										<a href="{{url('mail/contenteditor')}}/{{t['idMail']}}/{{t['id']}}" >
											{% if t['preview'] == null%}
												<img data-src="holder.js/100%x180" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSI+PC9yZWN0Pjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4=">
											{% else %}
												<img src="data: image/png;base64, {{t['preview']}}">
											{%endif%}
										</a>
										<div class="caption text-center">
											<h3>{{t['name']}}</h3>
											<button onclick="preview({{t['id']}})" class="btn btn-info btn-sm extra-padding" data-toggle="modal" data-target="#preview-modal">Visualizar</button>
										</div>
									</div>
								</div>
						   {% endfor %}
						</div>
					</div>
			   {% endfor %}
		   </div>
		</div>

	<div id="preview-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Previsualización</h4>
				</div>
				<div class="modal-body" id="modal-body-preview">

				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="modal-simple">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Eliminar plantilla</h4>
				</div>
				<div class="modal-body">
					<p>
						¿Está seguro que desea eliminar ésta plantilla?
					</p>
					<p>
						Recuerde que si elimina ésta plantilla se perderán todos los datos asociados, excepto las imágenes
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteMail" class="btn btn-danger" >Eliminar</a>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	
	
	
	<script type="text/javascript">
		$(document).on("click", ".ShowDialog", function () {
			var myURL = $(this).data('id');
			$("#deleteMail").attr('href', myURL );
		});
	</script>
{% endblock %}
