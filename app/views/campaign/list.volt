{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# bootstrap switch master B3 #}
	{{ stylesheet_link('bootstrap-switch-master/bootstrap-switch.css')}}
	{{ javascript_include('bootstrap-switch-master/bootstrap-switch.js')}}

	<script> 
		$(function (){
			$(".switch-campaign").bootstrapSwitch();
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

{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

	<div class="row">
		<h4  class="sectiontitle">Lista de autorespuestas</h4>
		
		{{ flashSession.output() }}
		
		<div class="container-fluid">
			
			{% if autoresponse|length != 0%}
				
				{%for item in autoresponse%}

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
								<dd><strong>Destinatarios:</strong> {{item.target}}</dd>
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
			
			
			{%else%}
			
				<div class="bs-callout bs-callout-warning">
					<h4>No ha creado ninguna autorespuesta aún</h4>
					<p>Gestione sus autorespuestas desde aquí.</p>
				</div>
			
			{%endif%}
			
			
			
			
{# Ejemplos de autorespuesta			
			
	
			<div class="col-md-12 col-sm-12" style="width: 90%; margin: auto; border-top: 1px solid rgb(216, 213, 213);">
				<div class="col-sm-2">
					<div class="image-64-autorespons img-64-n1"></div>
				</div>
				<div class="col-sm-2" style="margin-top:5%;">
					<div style="font-size: 60px;text-align: center;margin-left: 45%;"><span class="glyphicon glyphicon-calendar"></span></div>
				</div>
				<div class="col-sm-4" style="margin-top:3%;text-align: center;">
					<div>
						<div>
							<h4>Nombre de la autorespuesta</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> Lista de pruebas</dd>
								<dd><strong>Asunto:</strong> Mi asunto</dd>
							</dl>
						</div>
						<div style="margin: auto;display: table;">
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-pencil"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-eye-open"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-trash"></span></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="margin-top:3%;text-align: center;">
					<div>
						<div style="margin-bottom:-15px">Enviar</div>
						<div style="font-size: 70px;">20</div>
						<div style="margin-top:-15px">días despues</div></div>
				</div>
				<div class="col-sm-2" style="margin-top:7%;">
					<div><input type="checkbox" class="switch-campaign" checked></div>
				</div>
			</div>




			<div class="col-md-12 col-sm-12" style="width: 90%; margin: auto; border-top: 1px solid rgb(216, 213, 213);">
				<div class="col-sm-2">
					<div class="image-64-autorespons img-64-n2"></div>
				</div>
				<div class="col-sm-2" style="margin-top:5%;">
					<div style="font-size: 60px;text-align: center;margin-left: 45%;"><span class="glyphicon glyphicon-hand-up"></span></div>
				</div>
				<div class="col-sm-4" style="margin-top:3%;text-align: center;">
					<div>
						<div>
							<h4>Nombre de la autorespuesta numero dos</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> Lista de pruebas</dd>
								<dd><strong>Asunto:</strong> Mi asunto</dd>
							</dl>
						</div>
						<div style="margin: auto;display: table;">
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-pencil"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-eye-open"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-trash"></span></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="margin-top:3%;text-align: center;">
					<div style="font-size: 70px;">20</div>
				</div>
				<div class="col-sm-2" style="margin-top:7%;">
					<div><input type="checkbox" class="switch-campaign" checked></div>
				</div>
			</div>




			<div class="col-md-12 col-sm-12" style="width: 90%; margin: auto; border-top: 1px solid rgb(216, 213, 213);">
				<div class="col-sm-2">
					<div class="image-64-autorespons img-64-n3"></div>
				</div>
				<div class="col-sm-2" style="margin-top:5%;">
					<div style="font-size: 60px;text-align: center;margin-left: 45%;"><span class="glyphicon glyphicon-gift"></span></div>
				</div>
				<div class="col-sm-4" style="margin-top:3%;text-align: center;">
					<div>
						<div>
							<h4>Nombre de la autorespuesta numero Tres!</h4>
							<dl>
								<dd><strong>Destinatarios:</strong> Otra lista de pruebas</dd>
								<dd><strong>Asunto:</strong> El super asunto asunto</dd>
							</dl>
						</div>
						<div style="margin: auto;display: table;">
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-pencil"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-eye-open"></span></div>
							<div class="btn btn-default btn-sm" style="float:left;margin: 0 2px;"><span class="glyphicon glyphicon-trash"></span></div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="col-sm-2" style="margin-top:3%;text-align: center;">
					<div style="font-size: 70px;">20</div>
				</div>
				<div class="col-sm-2" style="margin-top:7%;">
					<div><input type="checkbox" class="switch-campaign" checked></div>
				</div>
			</div>
#}

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