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

{#   parcial paginacion   #}	
{{ partial('partials/pagination_static_partial', ['pagination_url': 'mail/list']) }}

<div class="row">
	<ul class="block-list">
		{% for item in page.items %}
			{# Variables para la vista inteligente#}	
			{% if item.status == 'Sent' %}
				{% set hexagon = 'hexagon-success' %}
				{% set icon = 'glyphicon glyphicon-ok'%}
			{% elseif item.status == 'Pending' OR item.status == 'Paused'%}
				{% set hexagon = 'hexagon-warning' %}
				{% set icon = 'glyphicon glyphicon-pause'%}
			{% elseif item.status == 'Cancelled' %}
				{% set hexagon = 'hexagon-danger' %}
				{% set icon = 'glyphicon glyphicon-warning-sign'%}
			{% elseif item.status == 'Scheduled' %}
				{% set hexagon = 'hexagon-primary' %}
				{% set icon = 'glyphicon glyphicon-list-alt'%}
			{% else %}
				{% set hexagon = 'hexagon-disable' %}
				{% set icon = 'glyphicon glyphicon-edit'%}
			{% endif %}
			<li>
				<div class="mail-block">
					<table class="table-list">
						<tr>
							<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
								<div class="hexagon hexagon-sm {{hexagon}}">
									<div class="hexagon-wrap">
										{% if item.status == 'Sent' %}
											<a href="{{url('statistic/mail')}}/{{item.idMail}}" class="hexagon-inner toolTip">
										{% elseif item.status == 'Draft' %}
											<a href="{{url('mail/compose')}}/{{item.idMail}}" class="hexagon-inner toolTip">
										{% else %}
											<a href="javascript.void(0);" class="hexagon-inner toolTip">
										{% endif %}
												<i class="{{icon}}"></i>
											</a>
									</div>
								</div>
							</td>
							<td class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
								<div class="mail-info">
									<div class="mail-name">
										{% if item.status == 'Sent' %}
											<a href="{{url('statistic/mail')}}/{{item.idMail}}">
										{% elseif item.status == 'Draft' %}
											<a href="{{url('mail/compose')}}/{{item.idMail}}">
										{% else %}
											<a href="javascript.void(0);">
										{% endif %}
												{{item.name}}
											</a>
									</div>
									
									<div class="mail-detail">
										Creado el {{date('d/M/Y', item.createdon)}} 
									</div>
									
									<div class="mail-detail">
										Programado para el {{date('d/M/Y, g:i a', item.scheduleDate)}}
									</div>
										
									<div class="mail-detail">
										Enviado el {{date('d/M/Y, g:i a', item.startedon)}}
									</div>
								</div>
							</td>
							
							<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
								{%if item.status == 'Sent'%}
									<dl class="dl-horizontal">
										<dt class="blue">Destinatarios</dt>
										<dd class="blue">{{item.totalContacts|numberf}}</dd>

										<dt class="green">Aperturas</dt>
										<dd class="green">{{item.uniqueOpens|numberf}} </dd>

										<dt class="gray">Clicks</dt> 
										<dd class="gray">{{item.clicks|numberf}} </dd>

										<dt class="red">Rebotes</dt>
										<dd class="red">{{item.bounced|numberf}} </dd>
									</dl>
								{%endif%}
							</td>
							
							<td class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-right">
								{%if item.status == 'Scheduled'%}
									<button class="ShowDialogEditScheduled btn btn-sm btn-default" data-toggle="modal" data-target="#modal-simple-stop" data-id="{{ url('mail/stop/index') }}/{{item.idMail}}">
										<span class="glyphicon glyphicon-pause"></span>
									</button>
								{%endif%}
									
								{% for value in mail_options(item) %}
									<a class="btn btn-sm btn-default" href="{{ url(value.url) }}{{item.idMail}}">
										<span class="{{value.icon}}"></span>
									</a>
								{% endfor %}
									
									<a href="{{ url('mail/clone/') }}{{item.idMail}}" class="btn btn-sm btn-default">
										<span class="glyphicon glyphicon-flash"></span>
									</a>
									
								{% if item.type == 'Editor'%}
									<a class="ShowDialogTemplate btn btn-sm btn-default" data-toggle="modal" data-target="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}">
										<span class="glyphicon glyphicon-text-width"></span>
									</a>
								{%endif%}
									
								{%if item.status == 'Sent'%}
									<a class="btn btn-sm btn-default" href="{{url('statistic/mail')}}/{{item.idMail}}">
										<span class="glyphicon glyphicon-stats"></span>
									</a>
									{#
									<button id="sharestats-{{item.idMail}}" type="button" class="btn btn-sm btn-default btn-add extra-padding" data-container="body" data-toggle="popover" data-placement="left" data-idmail="{{item.idMail}}">Compartir estadísticas</button>
									#}
								{%endif%}
									
								<button class="ShowDialog btn btn-sm btn-default btn-delete" data-toggle="modal tooltip" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}" data-placement="top" title="Eliminar correo">
									<span class="glyphicon glyphicon-trash"></span>
								</button>
							</td>
						</tr>
					</table>
				</div>
			</li>
		{% endfor %}
	</ul>
</div>

<div class="space"></div>

		<!-- Lista de mis correos -->
		{% if page.items|length != 0%}
			<div class="row">
				<table class="table mail-list">
					<thead></thead>
					<tbody>
						{%for item in page.items%}
						<tr>
							<td>
								<div class="preview-mail img-wrap">
									<a href="#preview-modal" data-toggle="modal" onClick="verPreview({{item.idMail}});">
										<div class="not-available">
									{% if item.previewData == null%}
											<span class="glyphicon glyphicon-eye-close icon-not-available"></span>
											<label>Previsualización no disponible</label>
									{% else %}
											<img src="data: image/png;base64, {{item.previewData}}" />
									{% endif %}	
											<div class="img-info">
												<p style="font-size: 18px;">Previsualizar</p>
											</div>
										</div>
									</a>
								</div>
							</td>
							<td>
								<div class="news-title">
									{%if item.status == 'Sent'%}
										<a href="{{ url('statistic/mail') }}/{{item.idMail}}">{{item.name}}</a>
									{%elseif item.status == 'Pending'%}
										<a href="{{ url('mail/play') }}/{{item.idMail}}">{{item.name}}</a>
									{%elseif item.status == 'Draft'%}
										<a href="{{ url('mail/compose') }}/{{item.idMail}}">{{item.name}}</a>
									{%else%}
										<a href="javascript.void(0);">{{item.name}}</a>
									{%endif%}
								</div>
								<div class="news-text">
									{{item.status}} <br /> 
									Creado el {{date('Y-m-d', item.createdon)}} 
									{%if item.status == 'Sent'%} <br />
									Enviado el {{date('Y-m-d, g:i a', item.startedon)}}
									{%elseif item.status == 'Scheduled'%} <br />
									Programado {{date('Y-m-d, g:i a', item.scheduleDate)}}
									{%endif%}
								</div>
							</td>
							<td>
								{%if item.status == 'Sent'%}
								<dl class="dl-horizontal">
									<dt class="blue">Destinatarios</dt>
									<dd class="blue">{{item.totalContacts|numberf}}</dd>

									<dt class="green">Aperturas</dt>
									<dd class="green">{{item.uniqueOpens|numberf}} </dd>

									<dt class="gray">Clicks</dt> 
									<dd class="gray">{{item.clicks|numberf}} </dd>

									<dt class="red">Rebotes</dt>
									<dd class="red">{{item.bounced|numberf}} </dd>
								</dl>
								{%endif%}
							</td>
							<td class="text-right">
								<div class="">
									{%if item.status == 'Scheduled'%}
										<button class="ShowDialogEditScheduled btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple-stop" data-id="{{ url('mail/stop/index') }}/{{item.idMail}}">Pausar</button>
										
									{%endif%}
									{% for value in mail_options(item) %}
										<a class="btn btn-sm btn-default extra-padding" href="{{ url(value.url) }}{{item.idMail}}">{{value.text}} <span class="{{value.icon}}"></span></a>
									{% endfor %}
										<a href="{{ url('mail/clone/') }}{{item.idMail}}" class="btn btn-sm btn-default extra-padding">Duplicar</a>

										<button class="ShowDialog btn btn-sm btn-default btn-delete extra-padding" data-toggle="modal" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}">Eliminar </button>
									{% if item.type == 'Editor'%}
										<a class="ShowDialogTemplate btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}">Plantilla</a>

									{%endif%}
									{%if item.status == 'Sent'%}
										<a class="btn btn-sm btn-default extra-padding" href="{{url('statistic/mail')}}/{{item.idMail}}">Estadísticas</a>
										{#
										<button id="sharestats-{{item.idMail}}" type="button" class="btn btn-sm btn-default btn-add extra-padding" data-container="body" data-toggle="popover" data-placement="left" data-idmail="{{item.idMail}}">Compartir estadísticas</button>
										#}
									{%endif%}
								</div>
							</td>
						</tr>
						{%endfor%}
					</tbody>
				</table>
			</div>
			
			{#   parcial paginacion   #}	
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
