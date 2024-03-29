{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		var idMail = {{mail.idMail}};
		var dir = "{{url('mail/compose')}}/";
		{% if pdf is defined AND pdf == 'pdf'%}
			dir = "{{url('pdfmail/compose')}}/";
		{% endif %}	
		
		function sendData() {
			var url = $('#url').val();
			var image = $('#image').val();
			
			$('#wait').show();
			
			$.ajax({
				url: "{{url('mail/importcontent')}}/" + idMail,
				type: "POST",			
				data: { 
					url: url,
					image: image	
				},
				error: function(msg){
					var obj = $.parseJSON(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.error, sticky: false, time: 10000});
					$('#wait').hide();
				},
				success: function(){
					$(location).attr('href', dir + idMail); 
				}
			});
		}
	</script>
{% endblock %}
{% block content %}
	<div class="container-fluid">
		<h4 class="sectiontitle">Importar contenido HTML desde una URL</h4>
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
						<div class="col-sm-4">
							<a class="btn btn-sm btn-primary extra-padding" onClick="sendData();">Importar</a>
							<a class="btn btn-sm btn-default extra-padding" href="{% if pdf is defined AND pdf == 'pdf'%}{{url('pdfmail/compose')}}{% else %}{{url('mail/compose')}}{% endif %}/{{mail.idMail}}">Cancelar</a>
							<div id="wait" style="display: none;">Espere... procesando solicitud <img src="{{url('')}}images/loading4.GIF" width="20" height="20"><br />(podría tardar unos minutos)</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}