{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function saveChanges() {
			var configData = $('#configData').val();
			$.ajax({
				data:  {configData: configData},
                url:   "{{url('system/configure')}}",
                type:  "post",
                error: function(msg){
					var response = JSON.parse(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: response.msg, sticky: false, time: 10000});
				},
				success: function(){
					$(location).attr('href', "{{url('system')}}"); 
				}
			});
		}
	</script>
{% endblock %}
{% block content %}
	<br />
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3 class="text-center">Editar archivo de configuración del sistema</h3>
			</blockquote>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			{{flashSession.output()}}
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
				<textarea class="form-control" rows="17" id="configData">{{config}}</textarea>
				<br />
				<a href="{{url('system/index')}}" class="btn btn-default">Cancelar</a>
				<a href="#myModal" class="btn btn-primary" data-toggle="modal" data-target="">Guardar</a>
		</div>
	</div>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Editar archivo de configuración del sistema</h4>
				</div>
				<div class="modal-body">
					<h5>
						Esta a punto de editar el archivo de configuración del sistema, recuerde que cualquier cambio efectuado
						en este archivo cambiará el funcionamiento de la plataforma. 
					</h5>
					
					<h4>
						<strong>¿Esta seguro de querer editarlo?</strong>
					</h4>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<button type="button" class="btn btn-primary" id="confirm-edit-button" data-dismiss="modal">Guardar cambios</button>
				</div>
			</div>
		</div>
	</div>	
	
	<script type="text/javascript">
		$(function() {
			$('#confirm-edit-button').on('click', function() {
				saveChanges();
			});
		});
	</script>
{% endblock %}
