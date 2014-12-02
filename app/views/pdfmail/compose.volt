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
		var MyUrl = "{{urlManager.getBaseUri()}}pdfmail/savemail";
		var urlComplete = "{{urlManager.getBaseUri(true)}}mail/savemail/mails";
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
						<div {{'{{bind-attr class=": isPDFMailReadyToSend:bg-success:bg-blue"}}'}} >
							<div class="wrapper">
								{{ '{{#if isPDFMailReadyToSend}}' }}
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-right">
											<a href="{{url('pdfmail/loadpdf')}}/{{ '{{unbound id}}' }}" class="btn btn-sm btn-success">
												Siguiente paso
											</a>
										</div>
									</div>
								{{ '{{else}}' }}
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<h4>Aún faltan datos</h4>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
											<strong>{{ '{{summaryMail}}' }}</strong>
										</div>
									</div>
								{{ '{{/if}}' }}
							</div>
						</div>
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
						{{ partial("mail/partials/pdf_content_partial") }}
					</div>
				</div>
			</script>
		</div>
	</div>
	
	<div class="clearfix"></div>
	<div class="space"></div>
{% endblock %}
