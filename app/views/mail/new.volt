{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('javascripts/moment/moment.min.js')}}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.min.js')}}
	{{ stylesheet_link('bootstrap/datepicker/css/bootstrap-datetimepicker.min.css') }}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.es.js')}}
	{{ partial("partials/datetimepicker_view_partial") }}
	<script type="text/javascript">
		var MyUrl = "{{urlManager.getBaseUri()}}mail/savemail";
	</script>
	{{ javascript_include('js/mixin_save.js') }}
	{{ javascript_include('js/app_mail.js') }}
	<script type="text/javascript">
		function iframeResize() {
			var iFrame = document.getElementById('iframeEditor');
			//iFrame.height = '';
			iFrame.height = iFrame.contentWindow.document.body.scrollHeight + "px";
		};
		
		$(function(){
			$("#editor").click(function() {
				$("#choose-content").hide();
				$("#buttons-content").show();
				$('<iframe />');  // Create an iframe element
				$('<iframe />', {
					id: 'iframeEditor',
					src: "{{url('mail/editor_frame')}}",
					width: "100%",
					onload: "iframeResize()",
					seamless: "seamless"
				}).appendTo('#show-content');
			});
			
			$("#template").click(function() {
				$("#choose-content").hide();
				$("#buttons-content").show();
				$('<iframe />');  // Create an iframe element
				$('<iframe />', {
					id: 'iframeEditor',
					src: "{{url('template/select')}}",
					width: "100%",
					onload: "iframeResize()",
					seamless: "seamless"
				}).appendTo('#show-content');
			});
			
			$("#html").click(function() {
				$("#choose-content").hide();
				$("#buttons-content").show();
				$('<iframe />');  // Create an iframe element
				$('<iframe />', {
					id: 'iframeEditor',
					src: "{{url('template/select')}}",
					width: "100%",
					onload: "iframeResize()",
					seamless: "seamless"
				}).appendTo('#show-content');
			});
		});
	</script>
	<script type="text/javascript">
		//Creación de select's de base de datos, listas de contactos, segmentos y filtros en eleccion de destinatarios
		{% if db == true%}
			App.dbs = [
				{% for dbase in dbases %}
					Ember.Object.create({name: "{{dbase.name}}", id: {{dbase.idDbase}}}),
				{% endfor %}
			];
			
			App.lists = [
				{% for contactlist in contactlists %}
					Ember.Object.create({name: "{{contactlist.Dbase}}", id: {{contactlist.idContactlist}}}),
				{% endfor %}
			];
			
			App.segments = [
				{% for segment in segments %}
					Ember.Object.create({name: "{{segment.name}}", id: {{segment.idSegment}}}),
				{% endfor %}
			];
			
			{% if mails %}
				App.sendByOpen = [
					{% for m in mails%}
						Ember.Object.create({name: "{{m.name}}", id: {{m.idMail}}}),
					{% endfor %}
				];
			{% endif%}
			
			
			{% if links %}
				App.sendByClick = [
					{% for link in links %}
						Ember.Object.create({name: "{{link.link}}", id: {{link.idMailLink}}}),
					{% endfor%}
				];
			{% endif %}
			
			{% if mails %}
				App.excludeContact = [
					{% for m2 in mails%}
						Ember.Object.create({name: "{{m2.name}}", id: {{m2.idMail}}}),
					{% endfor %}	
				];
			{% endif%}
									
		{% endif %}
	</script>
	<script type="text/javascript">
		$(function(){
			$("input[name=radios]").on('click', function () { 
				$("#db").hide();
				$("#list").hide();
				$("#seg").hide();

				$("#dbases").val('');
				$('#segments').val('');
				$('#contactlists').val('');

				var val = $('input[name=radios]:checked').val();

				switch (val) {
					case "dataBase":
						$("#db").show();
						break;
					case "contactList":
						$("#list").show();
						break;
					case "segment":
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
			
			$("input[name=schedule]").on('click', function () { 
				$("#programmer").hide();
				$('#schedule').data("DateTimePicker").hide();
				$("#schedule").val('');
				
				var v = $('input[name=schedule]:checked').val();
				switch (v) {
					case "now":
						break;
						
					case "later":
						$("#programmer").show();
						$('#schedule').data("DateTimePicker").show();
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
	<div id="emberAppContainer">
	</script>
		<script type="text/x-handlebars" data-template-name="index">
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-body">
							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label for="fromName" class="col-sm-2 control-label">Nombre del correo: </label>
									<div class="col-sm-10">
										{{'{{view Ember.TextField valueBinding="name" id="name" required="required" class="form-control"}}'}}
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
						  <h3 class="panel-title">Envíe el correo ahora, programelo para que se envíde déspues</h3>
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
		</script>
	</div>	
	<br />
{% endblock %}