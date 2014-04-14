{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function verPreview(id) {
			$.post("{{url('mail/previewindex')}}/" + id, function(preview){
				var e = preview.preview;
				$( "#preview-modal" ).empty();
				$('#preview-modal').append('<span class="close-preview icon-remove icon-2x" data-dismiss="modal"></span>');
				$('<iframe frameborder="0" width="100%" height="100%"/>').appendTo('#preview-modal').contents().find('body').append(e);
			});
		}
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Correos{% endblock %}
{%block sectionsubtitle %}Administre sus correos{% endblock %}
{% block content %}
<!-- aqui inicia mi contenido -->
<div class="row-fluid">
	<div class="box">
		<div class="box-section news with-icons">
			<div class="avatar green">
				<i class="icon-lightbulb icon-2x"></i>
			</div>
			<div class="news-content">
				<div class="news-title">
					Administre sus correos
				</div>
				<div class="news-text">
					Esta es la página principal de los correos en la cuenta, aqui podrá encontrar información acerca de la configuración
					de cada correo enviado, programado, en borrador, etc. Además podrá ver las estadisticas de cada correo enviado.
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span6">
	{{ flashSession.output() }}
	</div>
	<div class="span6 text-right"> 
		<a href="{{ url('scheduledmail') }}" class="btn btn-default">
			<i class="icon-calendar"></i> Administrar Programación
		</a>
		<a href="{{ url('mail/setup') }}" class="btn btn-default">
			<i class="icon-plus"></i> Nuevo Correo
		</a>
		<a href="{{ url('template/index') }}" class="btn btn-default">
			<i class="icon-magic"></i> Administrar Plantillas
		</a>
	</div>
</div>
<br />
<div class="row-fluid">
		<!-- Lista de mis correos -->
	<div class="box">
		<div class="box-header">
			<div class="title">
				Lista de correos
			</div>
		</div>
		<div class="box-content">
			<table class="table table-bordered">
				<thead></thead>
				<tbody>
			{%for item in page.items%}
					<tr>
						<td class="span6">
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
							<div class="box-section news with-icons">
								<div class="news-content">
									<div class="news-title" style="padding-left: 40px;">
										{%if item.status == 'Sent'%}
											<a href="{{ url('statistic/mail') }}/{{item.idMail}}">{{item.name}}</a>
										{%elseif item.status == 'Draft'%}
											<a href="{{ url('mail/setup') }}/{{item.idMail}}">{{item.name}}</a>
										{%else%}
											<a href="{{ url('mail/#') }}{{item.idMail}}">{{item.name}}</a>
										{%endif%}
									</div>
									<div class="news-text" style="padding-left: 40px;">
										{{item.status}} <br /> 
										Creado el {{date('Y-m-d', item.createdon)}} 
										{%if item.status == 'Sent'%}
										- Enviado el {{date('Y-m-d, g:i a', item.startedon)}}
										{%elseif item.status == 'Scheduled'%}
										- Programado {{date('Y-m-d, g:i a', item.scheduleDate)}}
										{%endif%}
									</div>
								</div>
							</div>
						</td>
						<td class="span4">
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
						<td class="span2">
							<div class="offset3">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
									{%if item.status == 'Scheduled'%}
										<li><a class="ShowDialogEditScheduled" data-backdrop="static" data-toggle="modal" href="#modal-simple-edit" data-id="{{ url('mail/stop/index') }}/{{item.idMail}}"><i class="icon-pause"></i>Pausar </a></li>
									{%endif%}
									{% for value in mail_options(item) %}
										<li><a href="{{ url(value.url) }}{{item.idMail}}"><i class="{{value.icon}}"></i>{{value.text}}</a></li>
									{% endfor %}
										<li><a href="{{ url('mail/clone/') }}{{item.idMail}}"><i class="icon-copy"></i>Duplicar</a></li>
										<li><a class="ShowDialog" data-backdrop="static" data-toggle="modal" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}"><i class="icon-trash"></i>Eliminar </a></li>
									{% if item.type%}
										<li><a class="ShowDialogTemplate" data-backdrop="static" data-toggle="modal" href="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}"><i class="icon-magic"></i>Plantilla</a></li>
									{%endif%}
									{%if item.status == 'Sent'%}
										<li><a href="{{url('statistic/mail')}}/{{item.idMail}}"><i class="icon-bar-chart"></i> Estadisticas</a></li>
									{%endif%}
									</ul>
								</div>
							</div>
						</td>
					</tr>
			{%endfor%}
				</tbody>
			</table>
		</div>
		<div class="box-footer padded">
			<div class="row-fluid">
				<div class="span5">
					<div class="pagination">
						<ul>
							{% if page.current == 1 %}
								<li class="previous"><a href="#" class="inactive"><<</a></li>
								<li class="previous"><a href="#" class="inactive"><</a></li>
							{% else %}
								<li class="previous active"><a href="{{ url('mail/index') }}"><<</a></li>
								<li class="previous active"><a href="{{ url('mail/index') }}?page={{ page.before }}"><</a></li>
							{% endif %}

							{% if page.current >= page.total_pages %}
								<li class="next"><a href="#" class="inactive">></a></li>
								<li class="next"><a href="#" class="inactive">>></a></li>
							{% else %}
								<li class="next active"><a href="{{ url('mail/index') }}?page={{page.next}}">></a></li>
								<li class="next active"><a href="{{ url('mail/index') }}?page={{page.last}}">>></a></li>		
							{% endif %}
						</ul>
					 </div>
				 </div>
				 <div class="span5">
					 <br />
					 Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
					 Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
				 </div>
			</div>
		</div>
	</div>
		<!-- Fin de mi lista de correos -->
</div>

<div id="modal-simple" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h6 id="modal-tablesLabel">Eliminar correo y sus estadisticas</h6>
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
</div>

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

<div id="modal-simple-template" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h5>Crear Template</h5>
	</div>
	<div class="modal-body">
		<form id="temapletMail" method="post">
			<table><tr>
					<td style="padding-right: 10px;"><label for="nametemplate">Nombre del Template</label></td><td><input type="text" id="nametemplate" name="nametemplate"></td>
				</tr><tr>
					<td><label for="category">Categoria</label></td><td><input type="text" id="category" name="category" value="Mis Templates" readonly></td>
			</tr></table>
	</div>
	<div class="modal-footer">
	  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	  <input class="btn btn-blue" type="submit" value="Crear">
	</div>
		</form>
</div>


<div id="preview-modal" class="modal hide fade preview-modal">
	<div class="modal-header">
		Previsualización de correo
	</div>
	<div class="modal-body">
		<div id="content-template">
			un momento...
		</div>
	</div>
	<div class="modal-footer">
		<button class="btn btn-black" data-dismiss="modal">x</button>
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
