{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	
	{# Time picker #}
	{{ javascript_include('vendors/time-picker/js/bootstrap-timepicker.min.js')}}
	{{ stylesheet_link('vendors/time-picker/css/bootstrap-timepicker.min.css') }}
	
	{# Date picker #}
	{{ stylesheet_link('vendors/bootstrap_v3/datetimepickerb3/bootstrap-datetimepicker.min.css') }}
	{{ javascript_include('vendors/bootstrap_v3/datetimepickerb3/bootstrap-datetimepicker.js')}}
	{{ javascript_include('vendors/bootstrap_v3/datetimepickerb3/bootstrap-datetimepicker.es.js')}}
	
	{# Select2 master#}
	{{ stylesheet_link('vendors/select2-master/select2.css') }}
	{{ javascript_include('vendors/select2-master/select2.js')}}
	
	{# Moment.js #}
	{{ javascript_include('js/pluggins-editor/moment/moment-with-langs.min.js')}}

	{{ partial("partials/datetimepicker_view_partial") }}
	{{ partial("partials/select2_view_partial") }}

	{{ javascript_include('js/pluggins-editor/dropzone/dropzone.js')}}
	{{ stylesheet_link('js/pluggins-editor/dropzone/css/dropzone.css') }}

	<script type="text/javascript">
		var db;
		var urlBase = "{{url('')}}";
		var MyUrl = "{{urlManager.getBaseUri()}}mail/savemail";
		var AttUrl = "/{{urlManager.getBaseUri()}}mail/attachment";
		var urlComplete = "{{urlManager.getBaseUri(true)}}mail/savemail/mails";
		var config = {assetsUrl: "{{url('asset/show')}}", imagesUrl: "{{url('images')}}", baseUrl: "{{url()}}", fbloginUrl: "{{fbloginUrl}}", twloginUrl: "{{twloginUrl}}"};
	</script>
	
	{{ javascript_include('js/mixin_config.js') }}
	
	{# Ember Uploader#}
	{{ javascript_include('js/ember-uploader/ember-uploader.min.js') }}
	
	{# Ember App Mail #}
	{{ partial("mail/partials/app_mail_partial") }}
	
	{{ javascript_include('js/editor/gallery.js') }}
	{{ javascript_include('js/editor/social_media_displayer.js') }}
	
	<script type="text/javascript">
		function showNewRemittent() {
			$('#not-allowed-remittents').hide();
			$('#allowed-remittents').show();
		}
		
		function hideNewRemittent() {
			$('#not-allowed-remittents').show();
			$('#allowed-remittents').hide();
		}
	</script>
	
	<script type="text/javascript">
		$(function() {
			{%for asset in assets%}
				var media = new Gallery("{{asset['thumb']}}", "{{asset['image']}}", "{{asset['title']}}", {{asset['id']}});
				media.createMedia();
				media.mediaSelected();
			{%endfor%}
		});
		var idMail;
		{% if mail is defined%}
			idMail = {{mail.idMail}}
		{% endif %}
		
		function sendMail() {
			$(function() {
				$.ajax({
					url: "{{url('mail/confirmmail')}}/" + idMail,
					type: "POST",			
					data: {},
					error: function(msg){
						var obj = $.parseJSON(msg.responseText);
						$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.error, sticky: false, time: 30000});
					},
					success: function(msg){
						$(location).attr('href', "{{url('mail/list')}}"); 
					}
				});
			});
		}
		
		function resetAttachment() {
			$.ajax({
				url: "{{url('mail/resetattachment')}}/" + idMail,
				type: "POST",			
				data: {},
				error: function(msg){
					$.gritter.add({title: '<i class="glyphicon glyphicon-remove-sign"></i> Ha ocurrido un error', text: msg.message, sticky: false, time: 10000});
					App.controller.refreshRecords();
				},
				success: function(msg){
					$.gritter.add({title: '<i class="glyphicon glyphicon-paperclip"></i> Exitoso', text: msg.message, sticky: false, time: 10000});
					App.controller.refreshRecords();
				}
			});
		}
		
		function saveDataAndGoToSocialMedia() {
			var name = $("#name").val();
			var fromName = $("#fromName").val();
			var fromEmail = $("#fromEmail").val();
			var replyTo = $("#replyTo").val();
			var subject = $("#subject").val();
			
			{#
			$(function() {
				$.ajax({
					url: "{{url('mail/savemail')}}/" + idMail,
					type: "POST",			
					data: {
						name: name,
						fromName: fromName,
						fromEmail: fromEmail,
						replyTo: replyTo,
						subject: subject
					},
					error: function(msg){
						var obj = $.parseJSON(msg.responseText);
						$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.error, sticky: false, time: 30000});
					},
					success: function(msg){
						$(location).attr('href', "{{url('mail/socialmedia')}}"); 
					}
				});
			});
			#}
		}
	</script>
	
	<script type="text/javascript">
		App.controller = "";
		//Full Mail Content
		{% if mail is defined %}
			App.maildata = [{
				id: {{mail.idMail}}
			}];
		{% endif %}
		
		//Relacion de direcciones de remitente y nombres de remitentes configurados previamente en la creación de la cuenta
		App.senders = [];
		{% if senders is defined %}
			App.senders = [
				{% for sender in senders %}
					Ember.Object.create({id: "{{sender.email|escape_js}}/{{sender.name|escape_js}}", value: "{{sender.name|escape_js}}  <{{sender.email|escape_js}}>"}),
				{% endfor %}
			];
		{% endif %}
		
		//Cuentas de Redes sociales
		{% if fbsocials %}
			App.fbaccounts = [
				{% for fbsocial in fbsocials %}
					Ember.Object.create({name: "{{fbsocial.name|escape_js}}", id: {{fbsocial.idSocialnetwork}}}),	
				{% endfor %}
			];
		{% endif %}
		
		{% if twsocials %}
			App.twaccounts = [
				{% for twsocial in twsocials %}
					Ember.Object.create({name: "{{twsocial.name|escape_js}}", id: {{twsocial.idSocialnetwork}}}),	
				{% endfor %}
			];
		{% endif %}
		
		{% if linksForTrack is defined%}
			{% if linksForTrack|length !== 0 %}
				App.googleAnalyticsLinks = [
					{% for link in linksForTrack%}
						Ember.Object.create({name: "{{link|escape_js}}"}),
					{% endfor %}
				];
			{% endif %}
		{% endif %}
	</script>
	
	{{ partial('partials/target_selection_partial') }}
{% endblock %}
{% block content %}
	
	{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'compose']) }}
	{{flashSession.output()}}
	
	<div class="border-mail mail-wrapper">
		<div id="emberAppContainer">
			<script type="text/x-handlebars" data-template-name="index">
				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/mailstatus_partial") }}
					</div>
				</div>
	
				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/mailstatus_warning_partial") }}
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/name_partial") }}
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/header_partial") }}
					</div>
				</div>	

				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/target_partial") }}
					</div>
				</div>	

				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/content_partial") }}
					</div>
				</div>	
		
				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/attachment_partial") }}
					</div>
				</div>	

				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/social_partial") }}
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/googleanalytics_partial") }}
					</div>
				</div>


				<div class="row">
					<div class="col-md-12">
						{{ partial("mail/partials/schedule_partial") }}
					</div>
				</div>
			</script>
		</div>
	</div>
	
	<div class="clearfix"></div>
	<div class="space"></div>
	
	<div class="modal fade gallery-modal" id="images" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h3>Imágenes</h3>
				</div>
					
				<ul class="nav nav-tabs nav-tabs-in-modal">
					<li id="tabgallery" class="active">
						<a href="#gallery" data-toggle="tab">Galería</a>
					</li>
					<li id="tabuploadimage" class="">
						<a href="#uploadimage" data-toggle="tab">Cargar</a>
					</li>
				</ul>
					
				<div class="modal-body">
					<div class="tab-content imagesbody">
						<div id="gallery" class="tab-pane active">
						</div>
						<div id="uploadimage" class="tab-pane well">
							<h2 class="text-center">Cargar imágen</h2>
							<form action="{{url('asset/upload')}}" id="my-dropzone">
								<div class="dz-message"><span>Suelte su imagen aquí! <br/><br/>(o Click)</span></div>
							</form>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="accept_cancel_image">
						<a href="#" class="btn btn-default btn-sm extra-padding" id="cancel_change" data-dismiss="modal">Cancelar</a>
						<a href="#" class="btn btn-guardar btn-sm extra-padding" id="accept_change" data-dismiss="modal">Aplicar</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h1 class="modal-title" id="myModalLabel">Enviar una prueba</h1>
				</div>
			<form {% if mail is defined %} action="{{url('mail/sendtest')}}/{{mail.idMail}}" {% endif %} id="testmail" method="post" role="form">
				<div class="modal-body">
					<div class="form-group">
						<label for="target">Enviar una prueba a:</label>
						<input type="text" class="form-control" id="target" name="target" autofocus="autofocus" placeholder="Escriba las direcciones de correo separadas por coma"/>
					</div>
					<div class="form-group">
						<label for="message">Incluír instrucciones o un mensaje personal (opcional)</label>
						<textarea class="form-control" rows="3" cols="30" id="message" name="message"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<input class="btn btn-sm btn-primary extra-padding" type="submit" value="Enviar">
				</div>
			</form>
			</div>
		</div>
	</div>
{% endblock %}
