{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}

	<script type="text/javascript">
		var urlBase = "{{url('')}}";
		{%if autoresponse.target is defined%}
			var serializerObject = {{autoresponse.target}};
		{%else%}
			var serializerObject = null;
		{%endif%}
	</script>
	
	{{ javascript_include('js/campaign/automatic.js')}}
	
	{# Select2 master#}
	{{ stylesheet_link('vendors/select2-master/select2.css') }}
	{{ javascript_include('vendors/select2-master/select2.js')}}

	{# Selección de destinatarios #}
	{{ partial('partials/target_selection_partial') }}
	
	<script type="text/javascript"> 
		$(function (){
			{%if autoresponse is defined%}
				$('.input-autoresponse-time-hour').val('{{autoresponse.time.hour}}');
				$('.input-autoresponse-time-minutes').val('{{autoresponse.time.minute}}');
				$('.input-autoresponse-time-text').val('{{autoresponse.time.meridian}}');
				
				{%for day in autoresponse.days%}
					$('#auto-day-{{day}}').prop('checked', true);
				{%endfor%}
					
				{%if autoresponse.active == 0%}
					$(".switch-campaign").bootstrapSwitch('state', false);
				{%endif%}
					
				{%if autoresponse.from is defined%}
					$('#from_email').val('{{autoresponse.from.email}}');
					$('#from_name').val('{{autoresponse.from.name}}');
				{%endif%}
					
				{%if autoresponse.subject.text == 'Meta Tag'%}
					$('#meta-tag').prop('checked', true);
					$("input[name='subject']").prop('disabled', true);
				{%endif%}
					
			{%endif%}
		});
		
		function previewAutoSend() {
			
			var url = $('input[name=content]').val();

			$.ajax({
				url: "{{url('campaign/preview')}}",
				type: "POST",			
				data: { 
					type: 'url',
					url: url
				},
				error: function(msg){
					var txt = JSON.parse(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: txt.status, sticky: false, time: 2000});
				},
				success: function() {
					$('#preview-modal-content').empty();
					$('#preview-auto-send-modal').modal('show');
					$('#preview-modal-content').append($('<iframe frameborder="0" width="100%" height="100%" src="{{url('campaign/previewframe')}}"/>'));
				}
			});
		}
	</script>
{% endblock %}
{% block content %}
	{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'compose']) }}
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="header-background">
				<div class="title">Autorespuestas en el tiempo</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			{{ flashSession.output() }}	
		</div>
	</div>	
	
	<div class="space"></div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="panel" style="box-shadow: 2px 2px 5px 0px #afafaf;">
				<form id="autosend_form" class="form-horizontal"  action="{%if autoresponse is defined%} {{url('campaign/automatic')}}/{{autoresponse.idAutoresponder}} {%else%} {{url('campaign/automatic')}} {%endif%}" method="post"  role="form">
				
					<div class="panel-header panel-heading box-header">
						<div class="box-title">Nueva autorespuesta en el tiempo</div>
					</div>
					
					<div class="panel-body" style="margin-top: 20px;">
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Nombre de envío automático
							</label>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<input class="form-control" autofocus placeholder="Nombre de envío automático" type="text" name="name" required="required" {%if autoresponse is defined%} value="{{autoresponse.name}}" {%endif%}>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								¿A quién envías?
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<div class="panel panel-default">
									<div class="panel-body" style="background-color: #f5f5f5;">
										<div id="panel-container"></div>
									</div>
								</div>
							</div>
							<input class="form-control" type="hidden" name="target">
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Hora del envío
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<div class="bg-wrap-time center-block without-margin time-clock-bigger">
									<select class="input-autoresponse-time-hour" name="hour"><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option></select>
									<select class="input-autoresponse-time-minutes" name="minute"><option>00</option><option>10</option><option>20</option><option>30</option><option>40</option><option>50</option></select>
									<select class="input-autoresponse-time-text" name="meridian"><option>am</option><option>pm</option></select>
								</div>
							</div>
						</div>
						
						<div class="space"></div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Día(s) de la semana
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-monday" name="monday"><label class="autoresponse-day" for="auto-day-monday">Lunes</label></div>
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-tuesday" name="tuesday"><label class="autoresponse-day" for="auto-day-tuesday">Martes</label></div>
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-wednesday" name="wednesday"><label class="autoresponse-day" for="auto-day-wednesday">Miércoles</label></div>
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-thursday" name="thursday"><label class="autoresponse-day" for="auto-day-thursday">Jueves</label></div>
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-friday" name="friday"><label class="autoresponse-day" for="auto-day-friday">Viernes</label></div>
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-saturday" name="saturday"><label class="autoresponse-day" for="auto-day-saturday">Sábado</label></div>
								<div class="automatic-day-opt"><input type="checkbox" id="auto-day-sunday" name="sunday"><label class="autoresponse-day" for="auto-day-sunday">Domingo</label></div>
							</div>
						</div>
						
						<div class="space"></div>
						
						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Contenido capturado de una URL
							</label>
							<div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
								<input class="form-control" placeholder="Pegar dirección de enlace aqui" type="text" name="content" required="required" {%if autoresponse is defined%} value="{{autoresponse.content.url}}" {%endif%}>
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<a onclick="previewAutoSend();" class="btn btn-default"><span class="glyphicon glyphicon-eye-open"></span></a>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Asunto
							</label>
							<div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
								<input class="form-control" placeholder="Asunto" type="text" name="subject" required="required" {%if autoresponse is defined and autoresponse.subject.text != 'Meta Tag'%} value="{{autoresponse.subject.text}}" {%endif%}>
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<input type="checkbox" id="meta-tag" name="meta-tag">
								<label for="meta-tag">Meta Tag</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Remitente
							</label>

						<div id="select-from">
							<div class="col-xs-10 col-sm-10 col-md-8 col-lg-8">
								<select id="select-field" class="form-control">
									<option>Seleccione nombre de remitente</option>
									{%for sender in senders%}
									<option value="{{sender.name}}/{{sender.email}}" {%if autoresponse is defined %} {%if autoresponse.from and autoresponse.from.email == sender.email%} selected {%endif%} {%endif%}>{{sender.name}} / {{sender.email}}</option>
									{%endfor%}
								</select>
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<a onclick="newSender();" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span></a>
							</div>
						</div>	

						<div id="new-from" class="hide-temporary">
							<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
								<input id="from_email" class="form-control" name="from_email" type="text" placeholder="Correo">
							</div>
							<div class="col-xs-5 col-sm-5 col-md-4 col-lg-4">
								<input id="from_name" class="form-control" name="from_name" type="text" placeholder="Nombre">
							</div>
							<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
								<a onclick="senderList();" class="btn btn-default"><span class="glyphicon glyphicon-list"></span></a>
							</div>
						</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Responder a
							</label>
							<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
								<input class="form-control" placeholder="Responder a" type="text" name="reply" {%if autoresponse is defined%} value="{{autoresponse.reply}}" {%endif%}>
							</div>
						</div>

						<div class="form-group">
							<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2 control-label">
								Habilitado
							</label>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<input type="checkbox" class="switch-campaign"  name="active" checked>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<div class="form-group">
							<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2"></div>
							<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
								<a href="{{url('campaign')}}" class="btn btn-sm btn-default">Cancelar</a>
								{{ submit_button("Guardar", 'class' : "btn btn-sm btn-guardar extra-padding") }}	
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	<div id="preview-auto-send-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Previsualización</h4>
				</div>
				<div class="modal-body modal-prevew-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}