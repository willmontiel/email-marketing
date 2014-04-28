{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function verPreview(id) {
			$.post("{{url('mail/previewindex')}}/" + id, function(preview){
				var e = preview.preview;
				$( "#preview-modal-content" ).empty();
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal-content').contents().find('body').append(e);
				$('#preview-modal').modal('show');
			});
		}
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Correos{% endblock %}
{%block sectionsubtitle %}Administre sus correos{% endblock %}
{% block content %}
<!-- aqui inicia mi contenido -->

{# Botones de navegacion #}
{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'list']) }}

<div class="row">
	<h4 class="sectiontitle">Listas de correos</h4>

	<div class="bs-callout bs-callout-info">
		Esta es la página principal de los correos en la cuenta, aqui podrá encontrar información acerca de la configuración
		de cada correo enviado, programado, en borrador, etc. Además podrá ver las estadísticas de cada correo enviado.
	</div>

	{{ flashSession.output() }}

	<div class="col-md-6 pull-right">
		<a class="btn btn-default btn-sm extra-padding" href="{{ url('scheduledmail') }}">
			<span class="glyphicon glyphicon-calendar"></span> Administrar Programación</a>
		<a class="btn btn-default btn-sm extra-padding" href="{{ url('mail/compose') }}">
			<span class="glyphicon glyphicon-plus"></span> Nuevo Correo</a>
		
		<a class="btn btn-default btn-sm extra-padding" href="{{ url('template/index') }}" class="btn btn-default btn-sm extra-padding">
			<span class="glyphicon glyphicon-magic"></span> Administrar Plantillas</a>
		
	</div>
		<!-- Lista de mis correos -->
	<table class="table table-bordered">
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
									<p><i class="icon-search"></i> Previsualizar</p>
								</div>
							</a>
						{% endif %}	
					</div>
					<div class="news-title" style="padding-left: 40px;">
						{%if item.status == 'Sent'%}
							<a href="{{ url('statistic/mail') }}/{{item.idMail}}">{{item.name}}</a>
						{%elseif item.status == 'Draft'%}
							<a href="{{ url('mail/compose') }}/{{item.idMail}}">{{item.name}}</a>
						{%else%}
							<a href="{{ url('mail/#') }}{{item.idMail}}">{{item.name}}</a>
						{%endif%}
					</div>
					<div class="news-text" style="padding-left: 40px;">
						{{item.status}} <br /> 
						Creado el {{date('Y-m-d', item.createdon)}} 
						{%if item.status == 'Sent'%} <br />
						Enviado el {{date('Y-m-d, g:i a', item.startedon)}}
						{%elseif item.status == 'Scheduled'%} <br />
						Programado {{date('Y-m-d, g:i a', item.scheduleDate)}}
						{%endif%}
					</div>
				</td>
				<td class="">
					{%if item.status == 'Sent'%}
					<ul class="inline sparkline-box">
						<li class="sparkline-row">
							<h4 class="blue"><span>Destinatarios</span> {{item.totalContacts}} </h4>
						</li>
						
						<li class="sparkline-row">
							<h4 class="green"><span>Aperturas</span> {{item.uniqueOpens}} </h4>
						</li>

						<li class="sparkline-row">
							<h4 class="gray"><span>Clicks</span> {{item.clicks}} </h4>
						</li>
						
						<li class="sparkline-row">
							<h4 class="red"><span>Rebotes</span> {{item.bounced}} </h4>
						</li>
					</ul>
					{%endif%}
				</td>
				<td class="">
					<div class="">
						{%if item.status == 'Scheduled'%}
							<a class="ShowDialogEditScheduled btn btn-sm extra-padding" data-backdrop="static" data-toggle="modal" href="#modal-simple-edit" data-id="{{ url('mail/stop/index') }}/{{item.idMail}}">Pausar </a>
						{%endif%}
						{% for value in mail_options(item) %}
							<a class="btn btn-sm btn-default extra-padding" href="{{ url(value.url) }}{{item.idMail}}">{{value.text}}</a>
						{% endfor %}
							<a href="{{ url('mail/clone/') }}{{item.idMail}}" class="btn btn-sm btn-default">Duplicar</a>

							<button class="ShowDialog btn btn-sm btn-default btn-delete extra-padding" data-toggle="modal" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}">Eliminar </button>
						{% if item.type%}
							<a class="ShowDialogTemplate btn btn-sm btn-default extra-padding" data-toggle="modal" data-target="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}">Plantilla</a>
							
						{%endif%}
						{%if item.status == 'Sent'%}
							<a class="btn btn-sm btn-default extra-padding" href="{{url('statistic/mail')}}/{{item.idMail}}">Estadísticas</a>
						{%endif%}
					</div>
				</td>
			</tr>
			{%endfor%}
		</tbody>
	</table>
	<div class="col-sm-12 text-center">
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'mail/list']) }}
	</div>
</div>


<div id="modal-simple" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Eliminar correo y sus estadisticas</h4>
			</div>
			<div class="modal-body">
				<p>
					¿Esta seguro que desea eliminar este correo?
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

<div id="modal-simple-edit" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h6 id="modal-tablesLabel">Pausar Correo Programado</h6>
	</div>
	<div class="modal-body">
		<p>
			¿Esta seguro que desea pausar este correo?
		</p>
		<p>
			Recuerde que si pausa este correo debera programarlo de nuevo.
		</p>
	</div>
	<div class="modal-footer">
	  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	  <a href="" id="editScheduledMail" class="btn btn-blue" >Pausar</a>
	</div>
</div>

<div class="modal fade" id="modal-simple-template" aria-hidden="false">
	<div class="modal-dialog">
		<div class="modal-content">
			<form id="temapletMail" method="post">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Crear Template</h4>
				</div>
				<div class="modal-body">
					<table>
						<tr>
							<td style="padding-right: 10px;"><label for="nametemplate">Nombre del Template</label></td><td><input type="text" id="nametemplate" name="nametemplate"></td>
						</tr>
						<tr>
							<td><label for="category">Categoria</label></td><td><input type="text" id="category" name="category" value="Mis Templates" readonly></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer">
					<button class="btn btn-default" data-dismiss="modal">Cancelar</button>
					<input class="btn btn-blue" type="submit" value="Crear">
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
<div id="preview-modal" class="modal fade preview-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Previsualización de correo</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div id="preview-modal-content" class="modal-body">
				<div id="content-template">
					un momento...
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-black" data-dismiss="modal">x</button>
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
