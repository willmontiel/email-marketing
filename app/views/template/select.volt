{% block header_javascript %}
	{{ stylesheet_link('b3/css/bootstrap.css') }}
	{{ stylesheet_link('b3/css/font-awesome.css') }}
	{{ stylesheet_link('css/prstyles.css') }}
	{{ stylesheet_link('b3/css/sm-email-theme.css') }}
	{{ stylesheet_link('b3/vendors/css/bootstrap-editable.css') }}
	{{ stylesheet_link('b3/vendors/css/jquery.gritter.css') }}

	<!--[if lt IE 9]>
	{{ javascript_include('javascripts/vendor/html5shiv.js') }}
	{{ javascript_include('javascripts/vendor/excanvas.js') }}
	<![endif]-->
	
	{{ javascript_include('b3/js/jquery-1.9.1.js') }}
	{{ javascript_include('b3/js/bootstrap.js') }}
	{{ javascript_include('b3/vendors/js/jquery.sparkline.js') }}
	{{ javascript_include('b3/vendors/js/spark_auto.js') }}
	{{ javascript_include('b3/vendors/js/bootstrap-editable.js') }}
	{{ javascript_include('b3/vendors/js/jquery.gritter.js') }}


	{{ javascript_include('js/jquery-1.9.1.js') }}
	{{ javascript_include('js/jquery_ui_1.10.3.js') }}
	{{ javascript_include('bootstrap/js/bootstrap.js') }}
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
	{{ super() }}
{% endblock %}
{% block content %}
	<div class="container-fluid">
		<div class="row">
		</div>
		<br />
		<div class="row">
			<div class="panel panel-default">
				<div class="panel-body">
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
												<a href="{{url('mail/editor_frame')}}/0/{{t['id']}}" class="thumbnail">
													{% if t['preview'] == null%}
														<img data-src="holder.js/100%x180" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNzEiIGhlaWdodD0iMTgwIj48cmVjdCB3aWR0aD0iMTcxIiBoZWlnaHQ9IjE4MCIgZmlsbD0iI2VlZSI+PC9yZWN0Pjx0ZXh0IHRleHQtYW5jaG9yPSJtaWRkbGUiIHg9Ijg1LjUiIHk9IjkwIiBzdHlsZT0iZmlsbDojYWFhO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1zaXplOjEycHg7Zm9udC1mYW1pbHk6QXJpYWwsSGVsdmV0aWNhLHNhbnMtc2VyaWY7ZG9taW5hbnQtYmFzZWxpbmU6Y2VudHJhbCI+MTcxeDE4MDwvdGV4dD48L3N2Zz4=">
													{% else %}
														<img src="data: image/png;base64, {{t['preview']}}">
													{%endif%}
												</a>
												<div class="caption text-center">
													<h4>{{t['name']}}</h4><br />
													<h5>
														<a href="#preview-modal" data-toggle="modal" onClick="preview({{t['id']}})" title="Previsualizar"><span class="label label-primary">Previsualizar</span></a>
													</h5>
												</div>
											</div>
									   {% endfor %}
									</div>
								</div>
						   {% endfor %}
					   </div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="preview-modal" class="modal fade preview-modal">
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
						¿Esta seguro que desea eliminar esta plantilla?
					</p>
					<p>
						Recuerde que si elimina esta plantilla se perderán todos los datos asociados, excepto las imágenes
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
