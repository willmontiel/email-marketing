{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		var idMail = null;
		{% if idMail is defined and idMail is numeric%}
			idMail = {{idMail}};
		{% endif %}
		function sendData() {
			var url = $('#url').val();
			var image = $('#image').val();
			
			$.ajax({
				url: "{{url('mail/importcontent')}}/" + idMail,
				type: "POST",			
				data: { 
					url: url,
					image: image	
				},
				error: function(msg){
					var obj = $.parseJSON(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.errors, sticky: false, time: 10000});
				},
				success: function(msg){
					$(location).attr('href', "{{url('mail/contenthtml')}}/" + msg.idMail); 
				}
			});
		}
	</script>
{% endblock %}
{% block content %}
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<br />
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="url" class="col-sm-4 control-label">Escriba o copie y pegue la dirección del enlace (url)</label>
						<div class="col-sm-8">
							<input type="url" name="url" id="url" class="form-control" required="required" autofocus="autofocus">
							<br />
							<input type="checkbox" name="image" id="image" value="load">
							<label for="image">Importar imágenes</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-4"></div>
						<div class="col-sm-8">
							<a class="btn btn-primary" onClick="sendData()">Importar</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}