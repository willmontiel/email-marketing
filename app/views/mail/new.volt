{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js')}}
	{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
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
				createIframe("iframeEditor", "{{url('mail/editor_frame')}}", "100%", "", "iframeResize()");
			});
			
			$("#template").click(function() {
				createIframe("iframeEditor", "{{url('template/select')}}", "100%", "", "iframeResize()");
			});
			
			$("#html").click(function() {
				createIframe("iframeHtml", "{{url('mail/contenthtml')}}", "100%", "600", "");
			});
			
			$("#import").click(function() {
				createIframe("iframeHtml", "{{url('mail/importcontent')}}", "100%", "600", "");
			});
			
		});
		
		function createIframe(id, url, width, height, fn) {
			$("#choose-content").hide();
			$("#plaintext-content").show();
			$("#buttons-content").show();
		
			$('<iframe />');  // Create an iframe element
			$('<iframe />', {
				id: id,
				src: url,
				width: width,
				height: height,
				onload: fn,
				seamless: "seamless"
			}).appendTo('#show-content');
		}
	</script>
	<script type="text/javascript">
		//Full Mail Content
		App.maildata = [{id: {{mail.idMail}}, name: "{{mail.name}}"}];
		
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
	
			<div class="row">
				<div class="col-md-12 text-right">
					<a href="{{url('mail/list')}}" class="btn btn-default">Confirmar luego </a>
					<a href="{{url('mail/confirmmail')}}/{{ '{{id}}' }}" class="btn btn-primary">Confirmar</a>
				</div>
			</div>
		</script>
	</div>	
	<br />
{% endblock %}