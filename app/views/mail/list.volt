{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function verPreview(id) {
			$.post("{{url('mail/previewindex')}}/" + id, function(preview){
				var e = preview.preview;
				$( "#preview-modal-content" ).empty();
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal-content').contents().find('body').append(e);
			});
		}
	</script>
	{{ partial("partials/getstatistics_partial") }}
{% endblock %}
{% block content %}
	
{# Botones de navegacion #}
{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'list']) }}

<div class="row">
	<h4 class="sectiontitle">Lista de correos</h4>

	<div class="bs-callout bs-callout-info">
		Esta es la página principal de los correos en la cuenta, aquí podrá encontrar información acerca de la configuración
		de cada correo enviado, programado, en borrador, etc. Además podrá ver las estadísticas de cada envío.
	</div>

	{{ flashSession.output() }}

	<div class="col-sm-12 col-md-12 col-lg-8 pull-right">
		<ul class="list-inline pull-right">
			<li><a class="btn btn-default btn-sm extra-padding" href="{{ url('scheduledmail') }}">
				<span class="glyphicon glyphicon-calendar"></span> Administrar Programación</a></li>
			<li><a class="btn btn-default btn-sm extra-padding" href="{{ url('mail/compose') }}">
				<span class="glyphicon glyphicon-plus"></span> Nuevo Correo</a></li>
			
			<li><a class="btn btn-default btn-sm extra-padding" href="{{ url('template/index') }}" class="btn btn-default btn-sm extra-padding">
				<span class="glyphicon glyphicon-th"></span> Administrar Plantillas</a></li>
		</ul>
	</div>
</div>

<!-- Lista de mis correos -->
{% if page.items|length != 0%}
	{#   parcial paginacion   #}	
	{{ partial('partials/pagination_static_partial', ['pagination_url': 'mail/list']) }}

	{% for item in page.items %}
		<div class="mail-block">
			<div class="row">
				{# Variables para la vista inteligente#}	
				{% if item.status == 'Sent' %}
					{% set hexagon = 'hexagon-success' %}
					{% set icon = 'glyphicon glyphicon-ok'%}
					{% set status = 'Enviado'%}
					{% set color = "green" %}
				{% elseif item.status == 'Pending' OR item.status == 'Paused'%}
					{% set hexagon = 'hexagon-warning' %}
					{% set icon = 'glyphicon glyphicon-pause'%}
					{% set status = 'Pendiente'%}
					{% set color = "orange" %}
				{% elseif item.status == 'Cancelled' %}
					{% set hexagon = 'hexagon-danger' %}
					{% set icon = 'glyphicon glyphicon-warning-sign'%}
					{% set status = 'Cancelado'%}
					{% set color = "red" %}
				{% elseif item.status == 'Scheduled' %}
					{% set hexagon = 'hexagon-primary' %}
					{% set icon = 'glyphicon glyphicon-list-alt'%}
					{% set status = 'Programado'%}
					{% set color = "blue" %}
				{% elseif item.status == 'Scheduled' %}
					{% set hexagon = 'hexagon-primary' %}
					{% set icon = 'glyphicon glyphicon-send'%}
					{% set status = 'Enviando'%}
					{% set color = "blue" %}
				{% else %}
					{% set hexagon = 'hexagon-disable' %}
					{% set icon = 'glyphicon glyphicon-edit'%}
					{% set status = 'Borrador'%}
					{% set color = "black" %}
				{% endif %}

				<div class="col-xs-12 col-sm-12 col-md-1 col-lg-1">
					<div class="hexagon hexagon-sm {{hexagon}}">
						<div class="hexagon-wrap">
							{% if item.status == 'Sent' %}
								<a href="{{url('statistic/mail')}}/{{item.idMail}}" class="hexagon-inner toolTip">
							{% elseif item.status == 'Draft' %}
								<a href="{{url('mail/compose')}}/{{item.idMail}}" class="hexagon-inner toolTip">
							{% else %}
								<a href="#" class="hexagon-inner toolTip">
							{% endif %}
									<i class="{{icon}}"></i>
								</a>
						</div>
					</div>
				</div>

				<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
					<div class="mail-info">
						<div class="mail-name">
							{% if item.status == 'Sent' %}
								<a href="{{url('statistic/mail')}}/{{item.idMail}}">{{item.name}}</a>
							{% elseif item.status == 'Draft' %}
								<a href="{{url('mail/compose')}}/{{item.idMail}}">{{item.name}}</a>
							{% else %}
								{{item.name}}
							{% endif %}
						</div>

						<div class="mail-detail {{color}}">{{status}}</div>
						<div class="mail-detail" style="color: #777;">
							Creado el {{date('d/M/Y g:i a', item.createdon)}} 
						</div>
					</div>
				</div>

				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
					{%if item.status == 'Sent'%}
						<dl class="dl-horizontal" style="margin-bottom: 0px !important; margin-top: 0px !important;">
							<dt class="blue medium-indicator">Destinatarios</dt>
							<dd class="blue medium-indicator">{{item.messagesSent|numberf}}</dd>

							<dt class="green medium-indicator">Aperturas</dt>
							<dd class="green medium-indicator">{{item.uniqueOpens|numberf}} </dd>

							<dt class="red medium-indicator">Rebotes</dt>
							<dd class="red medium-indicator">{{item.bounced|numberf}} </dd>
						</dl>
					{%endif%}
				</div>

				<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
					{%if item.status == 'Scheduled'%}
						<button class="ShowDialogEditScheduled btn btn-sm btn-warning tooltip-b3" data-toggle="modal" data-target="#modal-simple-stop" data-id="{{ url('mail/stop/index') }}/{{item.idMail}}" data-placement="top" title="Pausar este correo">
							<span class="glyphicon glyphicon-pause"></span>
						</button>
					{%endif%}

					{% for value in mail_options(item) %}
						<a class="btn btn-sm {% if value.url == 'mail/cancel/'%}btn-danger{% else %}btn-primary{% endif %} tooltip-b3" href="{{ url(value.url) }}{{item.idMail}}" data-placement="top" title="{{value.text}}">
							<span class="{{value.icon}}"></span>
						</a>
					{% endfor %}

						<a href="{{ url('mail/clone/') }}{{item.idMail}}" class="btn btn-sm btn-primary tooltip-b3" data-placement="top" title="Duplicar correo">
							<span class="glyphicon glyphicon-export"></span>
						</a>

					{% if item.type == 'Editor'%}
						<a class="ShowDialogTemplate btn btn-sm btn-primary tooltip-b3" data-toggle="modal" data-target="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}" data-placement="top" title="Crear una plantilla a partir de este correo">
							<span class="glyphicon glyphicon-star"></span>
						</a>
					{%endif%}

					{%if item.status == 'Sent'%}
						<a class="btn btn-sm btn-primary tooltip-b3" href="{{url('statistic/mail')}}/{{item.idMail}}" data-placement="top" title="Ver estadísticas">
							<span class="glyphicon glyphicon-stats"></span>
						</a>
						{#
						<button id="sharestats-{{item.idMail}}" type="button" class="btn btn-sm btn-default btn-add extra-padding" data-container="body" data-toggle="popover" data-placement="left" data-idmail="{{item.idMail}}">Compartir estadísticas</button>
						#}
					{%endif%}
					<a href="#preview-modal" class="btn btn-sm btn-primary tooltip-b3" data-toggle="modal" onClick="verPreview({{item.idMail}});" data-placement="top" title="Ver previsualización">
						<span class="glyphicon glyphicon-eye-open"></span>
					</a>

					<button class="btn btn-sm btn btn-info tooltip-b3" data-toggle="collapse" data-target="#preview{{item.idMail}}" data-placement="top" title="Ver detalles">
						<span class="glyphicon glyphicon-collapse-down"></span>
					</button>	

					<button class="ShowDialog btn btn-sm btn btn-danger tooltip-b3" data-toggle="modal" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}" data-placement="top" title="Eliminar correo">
						<span class="glyphicon glyphicon-trash"></span>
					</button>
				</div>
			</div>

			<div id="preview{{item.idMail}}" class="collapse row">
				<hr>	
				<div style="font-size: 1.8em; text-align: center; font-weight: 600; color: #777;">Detalles</div><br />
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

				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="small-widget">
						<div class="widget-icon"><span class="glyphicon glyphicon-cog"></span></div>
						<div class="widget-content">
							<div class="widget-header">Estado</div>	
							<div class="widget-body {{color}}">{{status}}</div>	
						</div>
					</div>
						
					{%if item.status == 'Sent'%}
						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-send"></span></div>
							<div class="widget-content">
								<div class="widget-header">Fecha de envío</div>	
								<div class="widget-body">{{date('d/M/Y g:i a', item.startedon)}}</div>	
							</div>
						</div>
					{%elseif item.status == 'Scheduled'%}
						<div class="small-widget">
							<div class="widget-icon"><span class="glyphicon glyphicon-calendar"></span></div>
							<div class="widget-content">
								<div class="widget-header">Fecha de programación</div>	
								<div class="widget-body">{{date('d/M/Y g:i a', item.scheduleDate)}}</div>	
							</div>
						</div>
					{%endif%}
						
					<div class="small-widget">
						<div class="widget-icon"><span class="glyphicon glyphicon-tag"></span></div>
						<div class="widget-content">
							<div class="widget-header">Asunto</div>	
							<div class="widget-body">
								{% if item.subject is empty%}<span style="color: #bd1b06">Este correo no contiene un asunto</span>{% else %}{{item.subject}}{% endif %}
							</div>	
						</div>
					</div>

					<div class="small-widget">
						<div class="widget-icon"><span class="glyphicon glyphicon-share-alt"></span></div>
						<div class="widget-content">
							<div class="widget-header">Remitente</div>	
							<div class="widget-body">
								{% if item.fromName is empty OR item.fromEmail is empty%}<span style="color: #bd1b06">Este correo no contiene un remitente</span>{% else %} {{item.fromName}}&lt;{{item.fromEmail}}&gt;{% endif %}
							</div>	
						</div>
					</div>

					<div class="small-widget">
						<div class="widget-icon"><span class="glyphicon glyphicon-retweet"></span></div>
						<div class="widget-content">
							<div class="widget-header">Responder a</div>	
							<div class="widget-body">
								{% if item.replyTo is empty%}<span style="color: #777">Este correo no tiene configurado un "Responder a"</span>{% else %}{{item.replyTo}}{% endif %}
							</div>	
						</div>
					</div>

					<div class="small-widget">
						<div class="widget-icon"><span class="glyphicon glyphicon-asterisk"></span></div>
						<div class="widget-content">
							<div class="widget-header">Tipo de correo</div>	
							<div class="widget-body">
								{% if item.type is empty%}<span style="color: #777">Indefinido</span>{% else %}{{item.type}}{% endif %}
							</div>	
						</div>
					</div>
				</div>

				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					{%if item.status == 'Sent'%}
						<div class="small-widget">
							<dl class="dl-horizontal" style="margin-bottom: 0px !important; margin-top: 0px !important;">
								<dt class="blue" style="font-weight: lighter !important;">Destinatarios</dt>
								<dd class="blue">{{item.messagesSent|numberf}}</dd>

								<dt class="green" style="font-weight: lighter !important;">Aperturas</dt>
								<dd class="green">{{item.uniqueOpens|numberf}} </dd>

								<dt class="gray" style="font-weight: lighter !important;">Clicks</dt> 
								<dd class="gray">{{item.clicks|numberf}} </dd>

								<dt class="orange" style="font-weight: lighter !important;">Rebotes</dt>
								<dd class="orange">{{item.bounced|numberf}} </dd>

								<dt class="red" style="font-weight: lighter !important;">Quejas de spam</dt>
								<dd class="red">{{item.spam|numberf}} </dd>

								<dt class="gray" style="font-weight: lighter !important;">Des-suscritos</dt>
								<dd class="gray">{{item.unsubscribed|numberf}} </dd>
							</dl>
						</div>
					{%endif%}
				</div>
			</div>	
			<div class="small-space"></div>
		</div>
		<div class="small-space"></div>
	{% endfor %}
	<div class="space"></div>
	
	{{ partial('partials/pagination_static_partial', ['pagination_url': 'mail/list']) }}
{% else %}
	<div class="row">
		<div class="bs-callout bs-callout-warning">
			<h4>No ha creado ningún correo aún</h4>
			<p>
				Para empezar la creación de un nuevo correo haga clic en el botón <strong>Nuevo correo</strong> de la parte de arriba, encontrará muchas opciones para crear espléndidos correos.
			</p>
		</div>
	</div>
{% endif %}
		
<div id="modal-simple" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Eliminar correo y sus estadísticas</h4>
			</div>
			<div class="modal-body">
				<p>
					¿Está seguro que desea eliminar este correo?
				</p>
				<p>
					Recuerde que si elimina este correo se perderán todos los datos asociados, excepto las imágenes
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
				<a href="" id="deleteMail" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal-simple-stop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Pausar correo programado</h4>
			</div>
			<div class="modal-body">
				<p>
					¿Está seguro que desea pausar este correo?
				</p>
				<p>
					Recuerde que si pausa este correo deberá programarlo de nuevo.
				</p>
			</div>
			<div class="modal-footer">
				<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
				<a href="" id="editScheduledMail" class="btn btn-sm btn-default btn-delete extra-padding" >Pausar</a>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal-simple-template" aria-hidden="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="temapletMail" class="form-horizontal" role="form" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Crear template</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="nametemplate" class="col-sm-4 control-label">Nombre del template:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="nametemplate" name="nametemplate">
						</div>
					</div>
					
					<div class="form-group">
						<label for="category" class="col-sm-4 control-label">Categoría:</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="category" name="category" value="Mis Templates" readonly>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<input class="btn btn-sm btn-guardar extra-padding" type="submit" value="Crear">
				</div>
			</form>
		</div>
	</div>
</div>
{#
<div id="modal-simple-template" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h5></h5>
	</div>
	<div class="modal-body">
		
			
	</div>
	<div class="modal-footer">
	 
	</div>
		</form>
</div>

#}


<div id="preview-modal" class="modal fade">
	<div class="modal-dialog modal-prevew-width">
		<div class="modal-content modal-prevew-content">
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


<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteMail").attr('href', myURL );
	});
	
	$(function() {
		$('.ShowDialogTemplate').on('click', function() {
			var myURL = $(this).data('id');
			$("#temapletMail").attr('action', myURL );
		});
	});
	
	$(function() {
		$('.ShowDialogEditScheduled').on('click', function() {
			var myURL = $(this).data('id');
			$("#editScheduledMail").attr('href', myURL );
		});
	});
</script>
{% endblock %}
