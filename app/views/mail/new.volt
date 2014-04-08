{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script>
		$(function(){
			$('#name').editable({
				title: 'Ingrese un nombre para el correo'
			});
			
			if($('#accounts_facebook')[0].selectedOptions.length > 0){
				$('.fbdescription').show();
			}	
			else {
				$('.fbdescription').hide();
			}
		});
	</script>
	
	<script type="text/javascript">
		$(function(){
			$("input[name=radios]").on('click', function () { 
				$("#db").hide();
				$("#list").hide();
				$("#seg").hide();

				$("#dbSelect").val('');
				$('#listSelect').val('');
				$('#segSelect').val('');

				var val = $('input[name=radios]:checked').val();

				switch (val) {
					case "0":
						$("#db").show();
						break;
					case "1":
						$("#list").show();
						break;
					case "2":
						$("#seg").show();
						break;
				}
			 });
			 
			$("input[name=filter]").on('click', function () { 
				$("#mail").hide();
				$("#open").hide();
				$("#click").hide();
				$("#exclude").hide();
			
				$("#sendMail").val('');
				$('#sendOpen').val('');
				$('#sendClick').val('');
				$('#sendExclude').val('');
			
				var val = $('input[name=filter]:checked').val();
				switch (val) {
					case "byMail":
						$("#mail").show();
						break;
					case "byOpen":
						$("#open").show();
						break;
					case "byClick":
						$("#click").show();
						break;
					case "byExclude":
						$("#exclude").show();
						break;
				}
			});
		});
	</script>
{% endblock %}
{% block content %}
	<br />
	<div class="row">
		<div class="col-md-12">
			{{flashSession.output()}}
		</div>
	</div>
	<br />
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label for="fromName" class="col-sm-2 control-label">Nombre del correo: </label>
							<div class="col-sm-10">
								<a href="#" id="name" data-type="text" data-pk="1">Nuevo correo</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3>Encabezado</h3>
			</blockquote>
				
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Nuevo correo</h3>
				</div>
				<div class="panel-body">
					{{ partial("mail/partials/header_partial") }}
				</div>
			</div>
		</div>
	</div>	
	
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3>Destinatarios</h3>
			</blockquote>
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title">Seleccione destinatarios</h3>
				</div>
				<div class="panel-body">
					{{ partial("mail/partials/target_partial") }}
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3>Contenido</h3>
			</blockquote>
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title">Cree el contenido del correo</h3>
				</div>
				<div class="panel-body">
					{{ partial("mail/partials/content_partial") }}
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3>Tracking con Google Analytics</h3>
			</blockquote>
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title">Configure google analytics con los enlaces que haya insertado en el contenido correo</h3>
				</div>
				<div class="panel-body">
					{{ partial("mail/partials/googleanalytics_partial") }}
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<blockquote>
				<h3>Programación</h3>
			</blockquote>
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title">Configure google analytics con los enlaces que haya insertado en el contenido correo</h3>
				</div>
				<div class="panel-body">
					{{ partial("mail/partials/schedule_partial") }}
				</div>
			</div>	
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12 text-right">
			<a href="#" class="btn btn-default">Confirmar luego</a>
			<a href="#" class="btn btn-primary">Confirmar</a>
		</div>
	</div>
	<br />
{% endblock %}