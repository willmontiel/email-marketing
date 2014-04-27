{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js')}}
	{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
	{{ partial("partials/datetimepicker_view_partial") }}
	<script type="text/javascript">
		var db;
		var MyUrl = "{{urlManager.getBaseUri()}}mail/savemail";
	</script>
	{{ javascript_include('js/mixin_save.js') }}
	{{ javascript_include('js/app_mail.js') }}
	<script type="text/javascript">
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
		<script type="text/x-handlebars" data-template-name="index">
			<div class="row">
				<div class="col-md-12">
					{{ partial("mail/partials/mailstatus_partial") }}
				</div>
			</div>
			
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
	<br />
{% endblock %}