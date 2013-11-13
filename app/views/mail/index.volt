{% extends "templates/index_new.volt" %}
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
	<div class="span7">
	{{ flashSession.output() }}
	</div>
	<div class="span5 text-right"> 
		<a href="{{ url('mail/setup') }}" class="btn btn-default">
			<i class="icon-plus"></i> Nuevo Correo
		</a>
		<a href="{{ url('template/new') }}" class="btn btn-default">
			<i class="icon-plus"></i> Nueva Plantilla
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
	{%for item in page.items%}
			<table class="table table-normal">
				<thead></thead>
				<tbody>
					<tr>
						<td>
							<div class="box-section news with-icons">
								<div class="avatar blue">
									<i class="icon-envelope icon-2x"></i>
								</div>
								<div class="news-content">
									<div class="news-title">
										<a href="{{ url('mail/#') }}{{item.idMail}}">{{item.name}}</a>
									</div>
									<div class="news-text">
										{{item.status}} <br /> 
										Creado el {{date('Y-m-d', item.createdon)}} 
										{%if item.status != 'Draft' %}
										- Enviado el {{date('Y-m-d', item.startedon)}}
										{%endif%}
									</div>
								</div>
							</div>
						</td>
						{%if item.status == 'Sent'%}
						<td>
							<ul class="inline sparkline-box">
								<li class="sparkline-row">
									<h4 class="blue"><span>Suscriptores</span> 0 </h4>
								</li>
								
								<li class="sparkline-row">
									<h4 class="green"><span>Clickeados</span> 0 </h4>
								</li>

								<li class="sparkline-row">
									<h4 class="gray"><span>Abiertos</span> 0 </h4>
								</li>
							</ul>
						</td>
						{%endif%}
						<td class="span3">
							<div class="offset3">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
									{% for value in mail_options(item.status) %}
										<li><a href="{{ url(value.url) }}{{item.idMail}}"><i class="{{value.icon}}"></i>{{value.text}}</a></li>
									{% endfor %}
										<li><a href="{{ url('mail/clone/') }}{{item.idMail}}"><i class="icon-copy"></i>Duplicar</a></li>
										<li><a class="ShowDialog" data-toggle="modal" href="#modal-simple" data-id="{{ url('mail/delete/') }}{{item.idMail}}"><i class="icon-trash"></i>Eliminar </a></li>
										
									</ul>
								</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
	{%endfor%}
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
	  <h6 id="modal-tablesLabel">Eliminar Base de Datos</h6>
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

<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteMail").attr('href', myURL );
	});
</script>
{% endblock %}
