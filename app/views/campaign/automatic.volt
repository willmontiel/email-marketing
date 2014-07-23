{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}

	<script> 
		$(function (){
			$(".switch-campaign").bootstrapSwitch();
			
			{%if autoresponse is defined%}
				$('.input-autoresponse-time-hour').val('{{autoresponse.time.hour}}');
				$('.input-autoresponse-time-minutes').val('{{autoresponse.time.minute}}');
				$('.input-autoresponse-time-text').val('{{autoresponse.time.meridian}}');
				
				{%for day in autoresponse.days%}
					$('#auto-day-{{day}}').prop('checked', true);
				{%endfor%}
					
				{%if autoresponse.activated == 0%}
					$(".switch-campaign").bootstrapSwitch('state', false);
				{%endif%}
				
			{%endif%}
		});
		
		function previewAutoSend() {
			
			var url = $('input[name=content]').val();

			$.ajax({
				url: "{{url('campaign/preview')}}",
				type: "POST",			
				data: { 
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
	<div class="row">
		<h4 class="sectiontitle">Nueva autorespuesta en el tiempo</h4>
		<div class="col-md-10">
			<form class="form-horizontal"  action="{%if autoresponse is defined%} {{url('campaign/automatic')}}/{{autoresponse.idAutosend}} {%else%} {{url('campaign/automatic')}} {%endif%}" method="post"  role="form">
				<div class="form-group">
					<label class="col-sm-4 control-label">Nombre de envío automático</label>
					<div class="col-md-5">
						<input class="form-control" type="text" name="name" required="required" {%if autoresponse is defined%} value="{{autoresponse.name}}" {%endif%}>
					</div>
				</div>
					
				<div class="space"></div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">¿A qué hora?</label>
					<div class="col-md-8">
						<div class="bg-wrap-time center-block without-margin time-clock-bigger">
							<select class="input-autoresponse-time-hour" name="hour"><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option></select>
							<select class="input-autoresponse-time-minutes" name="minute"><option>00</option><option>10</option><option>20</option><option>30</option><option>40</option><option>50</option></select>
							<select class="input-autoresponse-time-text" name="am_pm"><option>am</option><option>pm</option></select>
						</div>
					</div>
				</div>
				
				<div class="space"></div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">¿Qué día de la semana?</label>
					<div class="col-md-8">
						<input type="checkbox" id="auto-day-monday" name="monday"><label class="autoresponse-day" for="auto-day-monday">Lunes</label>
						<input type="checkbox" id="auto-day-tuesday" name="tuesday"><label class="autoresponse-day" for="auto-day-tuesday">Martes</label>
						<input type="checkbox" id="auto-day-wednesday" name="wednesday"><label class="autoresponse-day" for="auto-day-wednesday">Miércoles</label>
						<input type="checkbox" id="auto-day-thursday" name="thursday"><label class="autoresponse-day" for="auto-day-thursday">Jueves</label>
						<input type="checkbox" id="auto-day-friday" name="friday"><label class="autoresponse-day" for="auto-day-friday">Viernes</label>
						<input type="checkbox" id="auto-day-saturday" name="saturday"><label class="autoresponse-day" for="auto-day-saturday">Sábado</label>
						<input type="checkbox" id="auto-day-sunday" name="sunday"><label class="autoresponse-day" for="auto-day-sunday">Domingo</label>
					</div>
				</div>
				
				<div class="space"></div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">¿Qué envías?</label>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Contenido capturado de una URL</label>
					<div class="col-md-5">
						<input class="form-control" type="text" name="content" required="required" {%if autoresponse is defined%} value="{{autoresponse.content}}" {%endif%}>
					</div>
					<div class="col-md-1">
						<a onclick="previewAutoSend();" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></a>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Asunto:</label>
					<div class="col-md-5">
						<input class="form-control" type="text" name="subject" required="required" {%if autoresponse is defined%} value="{{autoresponse.subject}}" {%endif%}>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Remitente:</label>
					<div class="col-md-5">
						<input class="form-control" type="text" name="from" required="required" {%if autoresponse is defined%} value="{{autoresponse.from}}" {%endif%}>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Responder a:</label>
					<div class="col-md-5">
						<input class="form-control" type="text" name="reply" {%if autoresponse is defined%} value="{{autoresponse.reply}}" {%endif%}>
					</div>
				</div>
				
				<div class="space"></div>
				
				<div class="form-group">
					<label class="col-sm-4 control-label">Habilitado</label>
					<div class="col-md-5">
						<input type="checkbox" class="switch-campaign"  name="activated" checked>
					</div>
				</div>
				
				<div class="form-actions pull-right">
					<button class="btn btn-sm btn-default extra-padding">Cancelar</button>
					{{ submit_button("Guardar", 'class' : "btn btn-sm btn-guardar extra-padding") }}
				</div>
				
			</form>
		</div>
	</div>
	
	<div id="preview-auto-send-modal" class="modal fade">
		<div class="modal-dialog modal-prevew-width">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Footer</h4>
				</div>
				<div class="modal-body modal-prevew-body" id="preview-modal-content"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
{% endblock %}