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
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Correos{% endblock %}
{%block sectionsubtitle %}Administre sus correos{% endblock %}
{% block content %}
<!-- aqui inicia mi contenido -->

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
				<span class="glyphicon glyphicon-magic"></span> Administrar Plantillas</a></li>
		</ul>
	</div>
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
									{% if item.previewData == null%}
										<div class="not-available"></div>
									{% else %}
										<a href="#preview-modal" data-toggle="modal" onClick="verPreview({{item.idMail}})">
											<img src="data: image/png;base64, {{item.previewData}}" />
											<div class="img-info">
												<p>Previsualizar</p>
											</div>
										</a>
									{% endif %}	
								</div>
							</td>
							<td>
								<div class="news-title">
									{%if item.status == 'Sent'%}
										<a href="{{ url('statistic/mail') }}/{{item.idMail}}">{{item.name}}</a>
									{%elseif item.status == 'Draft'%}
										<a href="{{ url('mail/compose') }}/{{item.idMail}}">{{item.name}}</a>
									{%else%}
										<a href="{{ url('mail/#') }}{{item.idMail}}">{{item.name}}</a>
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
							<td>
								<div class="">
									{%if item.status == 'Scheduled'%}
										<button class="ShowDialogEditScheduled btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple-stop" data-id="{{ url('mail/stop/index') }}/{{item.idMail}}">
											Pausar
										</button>
									{%endif%}
									{% for value in mail_options(item) %}
										<a class="btn btn-sm btn-default extra-padding" href="{{ url(value.url) }}{{item.idMail}}">{{value.text}}</a>
									{% endfor %}
										<a href="{{ url('mail/clone/') }}{{item.idMail}}" class="btn btn-sm btn-default extra-padding">Duplicar</a>

										<button class="ShowDialog btn btn-sm btn-default btn-delete extra-padding" data-toggle="modal" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}">Eliminar </button>
									{% if item.type%}
										<a class="ShowDialogTemplate btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}">Plantilla</a>

									{%endif%}
									{%if item.status == 'Sent'%}
										<a class="btn btn-sm btn-default extra-padding" href="{{url('statistic/mail')}}/{{item.idMail}}">Estadísticas</a>
										<button id="sharestats-{{item.idMail}}" type="button" class="btn btn-sm btn-default btn-add extra-padding" data-container="body" data-toggle="popover" data-placement="left" data-idmail="{{item.idMail}}">Compartir estadísticas</button>
									{%endif%}
								</div>
							</td>
						</tr>
						{%endfor%}
					</tbody>
				</table>
			</div>
			<div class="col-sm-12 text-center">
				{{ partial('partials/pagination_static_partial', ['pagination_url': 'mail/list']) }}
			</div>
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
				<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
				<a href="" id="deleteMail" class="btn btn-danger" >Eliminar</a>
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
