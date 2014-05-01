{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js')}}
	{{ javascript_include('javascripts/moment/moment.min.js')}}
	{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
	{{ partial("partials/datetimepicker_view_partial") }}
	<script type="text/javascript">
		var db;
		var MyUrl = "{{urlManager.getBaseUri()}}mail/savemail";
		var config = {assetsUrl: "{{url('asset/show')}}", imagesUrl: "{{url('images')}}", baseUrl: "{{url()}}"};
	</script>
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/app_mail.js') }}
	{{ javascript_include('js/editor/gallery.js') }}
	{{ javascript_include('js/editor/social_media_displayer.js') }}
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
	</script>
	<script type="text/javascript">
		//Full Mail Content
		{% if mail is defined %}
			App.maildata = [{
				id: {{mail.idMail}}
			}];
		{% endif %}
		
		//Creación de select's de base de datos, listas de contactos, segmentos y filtros en eleccion de destinatarios
		{% if db == true%}
			App.dbs = [
				{% for dbase in dbases %}
					Ember.Object.create({name: "{{dbase.name}}", id: {{dbase.idDbase}}}),
				{% endfor %}
			];
			
			App.lists = [
				{% for contactlist in contactlists %}
					Ember.Object.create({name: "{{contactlist.name}}", id: {{contactlist.idContactlist}}}),
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
		
		{% if linksForTrack is defined%}
			{% if linksForTrack|length !== 0 %}
				App.googleAnalyticsLinks = [
					{% for link in linksForTrack%}
						Ember.Object.create({name: "{{link}}"}),
					{% endfor %}
				];
			{% endif %}
		{% endif %}
			
			
		//Cuentas de Redes sociales
		{% if fbsocials %}
			App.fbaccounts = [
				{% for fbsocial in fbsocials %}
					Ember.Object.create({name: "{{fbsocial.name}}", id: {{fbsocial.idSocialnetwork}}}),	
				{% endfor %}
			];
		{% endif %}
		
		{% if twsocials %}
			App.twaccounts = [
				{% for twsocial in twsocials %}
					Ember.Object.create({name: "{{twsocial.name}}", id: {{twsocial.idSocialnetwork}}}),	
				{% endfor %}
			];
		{% endif %}
	</script>
{% endblock %}
{% block content %}

<div class="border-mail mail-wrapper">
	{{flashSession.output()}}
	<div id="emberAppContainer">
		<script type="text/x-handlebars" data-template-name="index">
			<div class="row">
				<div class="col-md-12">
					{{ partial("mail/partials/mailstatus_partial") }}
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
							<form action="{{url('asset/upload')}}" class="dropzone" id="my-dropzone">
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
{% endblock %}