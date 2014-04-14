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
	<script type="text/javascript">
		function sendData() {
			var url = $('#url').val();
			var image = $('#image').val();
			
			$.ajax({
				url: "{{url('mail/importcontent')}}",
				type: "POST",			
				data: { 
					url: url,
					image: image	
				},
				error: function(msg){
					var obj = $.parseJSON(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.errors, sticky: false, time: 10000});
				},
				success: function(){
					$(location).attr('href', "{{url('mail/contenthtml')}}"); 
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