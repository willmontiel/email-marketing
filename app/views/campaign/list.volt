{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}

	<script> 
		$(function (){
			$(".switch-campaign").bootstrapSwitch({
				size: 'mini',
				onColor: 'success',
				offColor: 'danger'
			});
			
			$('.switch-campaign').on('switchChange.bootstrapSwitch', function(event, state) {
				changeStatus($(this).data('id'), state);
			});
			$('.auto_send_delete_btn').on("click", function () {
				var myURL = $(this).data('id');
				$("#delete_auto_send").attr('href', myURL );
			});
		});
		
		function changeStatus(id, state)
		{
			$.ajax({
				url: "{{url('campaign/changestatus')}}/" + id,
				type: "POST",
				data: { state: state},
				error: function(msg){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: msg.statusText, sticky: false, time: 2000});
				},
				success: function(obj){
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: obj.status, sticky: false, time: 2000});
				}
			});
		}
		
		function previewAutoSend(url, id) {
			$.ajax({
				url: "{{url('campaign/preview')}}/" + id,
				type: "POST",			
				data: { 
					url: url
				},
				error: function(msg){
					var txt = JSON.parse(msg.responseText);
					$.gritter.add({class_name: 'error', title: '<i class="icon-warning-sign"></i> Atención', text: txt.status, sticky: false, time: 10000});
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
		<h1 class="sectiontitle">Lista de autorespuestas</h1>

		<div class="bs-callout bs-callout-info">
			Esta es la página principal de los correos en la cuenta, aquí podrá encontrar información acerca de la configuración
			de cada correo enviado, programado, en borrador, etc. Además podrá ver las estadísticas de cada envío.
		</div>

		{{ flashSession.output() }}
	</div>
	
	
	{% if autoresponse|length != 0%}	
		{#
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'campaign/list']) }}
		#}
		{%for item in autoresponse%}
			{% if item.active == 1 %}
				{% set hexagon = 'hexagon-success' %}
				{% set icon = 'glyphicon glyphicon-calendar'%}
				{% set status = 'Activa' %}
				{% set color = "green" %}
			{% else %}
				{% set hexagon = 'hexagon-disable' %}
				{% set icon = 'glyphicon glyphicon-calendar'%}
				{% set status = 'Inactiva' %}
				{% set color = "gray" %}
			{% endif %}
	
			<div class="mail-block">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1 autoresponse-list-icon">
						<span class="glyphicon glyphicon-calendar"></span>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
						<div class="mail-info">
							<div class="mail-name" style="font-size: 2em !important;">
								<a href="{{url("campaign/automatic")}}/{{item.idAutoresponder}}">
									{{item.name}}
								</a>
							</div>

							<div class="mail-detail" style="color: #777; font-size: 0.9em !important;"><strong> Destinatarios:</strong> {{item.target.criteria}}: {{item.target.names}}</div>
							<div class="mail-detail" style="color: #777; font-size: 0.9em !important;">
								<strong>Asunto:</strong> {{item.subject}}
							</div>
						</div>
					</div>	
						
					<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 autoresponse-list-details">
						Programada para la(s) <br />
						<span>{{item.time.hour}}:{{item.time.minute}} {{item.time.meridian}}</span>
						<p>los dias {%for day in item.days%} <strong>{{day}}</strong>, {%endfor%} recurrente.</p>
					</div>
						
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 text-right">
						<a class="btn btn-primary btn-sm tooltip-b3" href="{{url("campaign/automatic")}}/{{item.idAutoresponder}}" data-placement="top" title="Editar esta autorespuesta">
							<span class="glyphicon glyphicon-pencil"></span>
						</a>
						<a onclick="previewAutoSend('{{item.content.url}}', '{{item.idAutoresponder}}');" class="btn btn-primary btn-sm tooltip-b3" data-placement="top" title="Previsualizar esta autorespuesta">
							<span class="glyphicon glyphicon-eye-open"></span>
						</a>
						<button class="btn btn-sm btn btn-info tooltip-b3" data-toggle="collapse" data-target="#preview{{item.idAutoresponder}}" data-placement="top" title="Ver detalles">
							<span class="glyphicon glyphicon-collapse-down"></span>
						</button>
						<a class="auto_send_delete_btn btn btn-danger btn-sm tooltip-b3" data-toggle="modal" href="#modal-simple" data-id="{{ url('campaign/delete/') }}{{item.idAutoresponder}}" data-placement="top" title="Eliminar esta autorespuesta">
							<span class="glyphicon glyphicon-trash"></span>
						</a>
						<br />
						<input type="checkbox" class="switch-campaign"  data-id="{{item.idAutoresponder}}" {%if item.active == 1%}checked{%endif%}>
					</div>
				</div>
					
				<div id="preview{{item.idAutoresponder}}" class="collapse row">
					<hr>
					<div style="font-size: 1.8em; text-align: center; color: #777;">Detalles</div><br />
					
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="preview-mail img-wrap">
							<div class="not-available">
						{% if item.previewData == null%}
								<span class="glyphicon glyphicon-eye-close icon-not-available"></span>
								<label>Previsualización no disponible</label>
						{% else %}
								<img src="data: image/png;base64, {{item.previewData}}" />
						{% endif %}	
							</div>
						</div>
					</div>
					
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-cog"></span></div>
							<div class="widget-content">
								<div class="widget-header">Estado</div>	
								<div class="widget-body {{color}}">{{status}}</div>	
							</div>
						</div>

						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-check"></span></div>
							<div class="widget-content">
								<div class="widget-header">Fecha de creación</div>	
								<div class="widget-body">{{date('d/M/Y g:i a', item.createdon)}}</div>	
							</div>
						</div>

						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-tag"></span></div>
							<div class="widget-content">
								<div class="widget-header">Asunto</div>	
								<div class="widget-body">
									{% if item.subject is empty%}<span style="color: #bd1b06">Esta autorespuesta no contiene un asunto</span>{% else %}{{item.subject}}{% endif %}
								</div>	
							</div>
						</div>

						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-share-alt"></span></div>
							<div class="widget-content">
								<div class="widget-header">Remitente</div>	
								<div class="widget-body">
									{% if item.from is empty %}<span style="color: #bd1b06">Esta autorespuesta no contiene un remitente</span>{% else %} {{item.from}}{% endif %}
								</div>	
							</div>
						</div>

						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-retweet"></span></div>
							<div class="widget-content">
								<div class="widget-header">Responder a</div>	
								<div class="widget-body">
									{% if item.reply is empty%}<span style="color: #777">Esta autorespuesta no tiene configurado un "Responder a"</span>{% else %}{{item.reply}}{% endif %}
								</div>	
							</div>
						</div>

						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-calendar"></span></div>
							<div class="widget-content">
								<div class="widget-header">Programada para:</div>	
								<div class="widget-body">
									<span>{{item.time.hour}}:{{item.time.minute}} {{item.time.meridian}}</span>
									<p>los dias {%for day in item.days%} {{day}}, {%endfor%} recurrente.</p>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<div class="small-space"></div>
			</div>
			<div class="space-small"></div>
			{#
			<div class="row">
				
				<div class="col-md-12 col-sm-12 list-one-autoresponse">
						<div class="col-sm-2">
							{% if item.previewData == null%}
									<div class="image-64-autorespons">
										<span class="glyphicon glyphicon-eye-close icon-not-available without-padding"></span>
										<label>Previsualización no disponible</label>
									</div>
							{% else %}
									<div class="image-64-autorespons">
										<img src="data: image/png;base64, {{item.previewData}}" />
									</div>
							{% endif %}	
						</div>
						<div class="col-sm-1 autoresponse-list-icon">
							<span class="glyphicon glyphicon-calendar"></span>
						</div>
						<div class="col-sm-4 autoresponse-list-information">
							<h4>{{item.name}}</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> {{item.target.criteria}}: {{item.target.names}}</dd>
								<dd><strong>Asunto:</strong> {{item.subject}}</dd>
							</dl>
							<div class="autoresponse-list-options">
								<a class="btn btn-default btn-sm" href="{{url("campaign/automatic")}}/{{item.idAutoresponder}}">
									<span class="glyphicon glyphicon-pencil"></span>
								</a>
								<a onclick="previewAutoSend('{{item.content.url}}', '{{item.idAutoresponder}}');" class="btn btn-default btn-sm">
									<span class="glyphicon glyphicon-eye-open"></span>
								</a>
								<a class="auto_send_delete_btn btn btn-default btn-sm" data-toggle="modal" href="#modal-simple" data-id="{{ url('campaign/delete/') }}{{item.idAutoresponder}}">
									<span class="glyphicon glyphicon-trash"></span>
								</a>
								<div class="clearfix"></div>
							</div>
						</div>
						<div class="col-sm-3 autoresponse-list-details" style="">
							Enviar autorespuesta a las
							<span>{{item.time.hour}}:{{item.time.minute}} {{item.time.meridian}}</span>
							<p>los dias {%for day in item.days%} {{day}}, {%endfor%} recurrente.</p>
						</div>
						<div class="col-sm-2 autoresponse-list-activated">
							<input type="checkbox" class="switch-campaign"  data-id="{{item.idAutoresponder}}" {%if item.active == 1%}checked{%endif%}>
						</div>
					</div>
			</div>
			#}
		{%endfor%}
			<div id="modal-simple" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
							<h4 class="modal-title">Eliminar envío automático</h4>
						</div>
						<div class="modal-body">
							<p>
								¿Está seguro que desea eliminar este envío automático?
							</p>
							<p>
								Recuerde que si elimina este envío automático no se enviaran más correos
							</p>
						</div>
						<div class="modal-footer">
							<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
							<a href="" id="delete_auto_send" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
						</div>
					</div>
				</div>
			</div>
			{#
			{{ partial('partials/pagination_static_partial', ['pagination_url': 'campaign/list']) }}
			#}
		{%else%}
			<div class="bs-callout bs-callout-warning">
				<h4>No ha creado ninguna autorespuesta aún</h4>
				<p>Gestione sus autorespuestas desde aquí.</p>
			</div>
		{%endif%}
	
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