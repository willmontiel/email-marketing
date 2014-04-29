{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
	<script type="text/javascript">
		{#var MyDbaseUrl = '{{urlManager.getApi_v1Url()}}';#}
		$(function() { 
			$("#processes-table").tablesorter(); 
		}); 
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-calendar icon-2x"></i>Programación de correos{% endblock %}
{%block sectionsubtitle %}Administre cuando se deben enviar los correos{% endblock %}
{% block content%}

{# Botones de navegacion #}
{{ partial('mail/partials/small_buttons_nav_partial', ['activelnk': 'list']) }}

	<div class="row">
		<h4 class="sectiontitle">Programación de envío de correos</h4>
		<div class="bs-callout bs-callout-info">
			<p>Aqui podrá administrar la programación de los correos, pausarlos, cancelarlos y también reprogramar fechas.</p>
		</div>
		{{ flashSession.output() }}

		<div class="">
			<a href="{{url('mail')}}" class="btn btn-default btn-sm extra-padding"><span class="glyphicon glyphicon-envelope"></span> Todos los correos</a>
		</div>
	</div>
	<div class="space"></div>
	<div class="row">
		<table id="processes-table" class="table table-striped">
			<thead>
				<tr>
					<th>Nombre</th>
					<th>Estado</th>
					<th>Destinatarios aprox.</th>
					<th>Programado para</th>
					<td></td>
				</tr>
			</thead>
			<tbody id="resultado">
				 {% for item in page.items %}
					<tr>
						<td>{{item.name}}</td>
						<td>
							{{item.status}}
								{%if item.deleted != 0%}
									( Eliminado {{date('d-M-Y, g:i A', item.deleted)}} )
								{% endif %}
						</td>
						<td>{{item.totalContacts}}</td>
						<td>{{date('d-M-Y, g:i A', item.scheduleDate)}}</td>
						<td style="text-align: center;">
							<div class="btn-group">
								{% for value in programming_options(item) %}
									{% if value.url == 'null'%}	
										&nbsp;<span></span> No hay acciones disponibles&nbsp;
									{% elseif value.text == 'Pausar'%}
										<a class="ShowDialogEditScheduled" data-backdrop="static" data-toggle="modal" href="#modal-simple-edit" data-id="{{url('mail/stop')}}/scheduledmail/{{item.idMail}}"><span class="{{value.icon}}"></span> Pausar</a>
									{% else %}
										<a href="{{url(value.url)}}index/{{item.idMail}}" class="btn btn-sm btn-default extra-padding" ><span class="{{value.icon}}"></span> {{value.text}}</a>
									{% endif %}
								{% endfor %}
							</div>
						</td>
					</tr>
				{% endfor %}
			</tbody>
		</table>
	</div>

	{#   Partial paginacion sin ember   #}
	<div class="col-sm-12 text-center">
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'scheduledmail/index']) }}
	</div>

{#			<div class="box-footer padded">
				<div class="row">
					<div class="span5">
						<div class="pagination">
							<ul>
								{% if page.current == 1 %}
									<li class="previous"><a href="#" class="inactive"><<</a></li>
									<li class="previous"><a href="#" class="inactive"><</a></li>
								{% else %}
									<li class="previous active"><a href="{{ url('scheduledmail/index') }}"><<</a></li>
									<li class="previous active"><a href="{{ url('scheduledmail/index') }}?page={{ page.before }}"><</a></li>
								{% endif %}

								{% if page.current >= page.total_pages %}
									<li class="next"><a href="#" class="inactive">></a></li>
									<li class="next"><a href="#" class="inactive">>></a></li>
								{% else %}
									<li class="next active"><a href="{{ url('scheduledmail/index') }}?page={{page.next}}">></a></li>
									<li class="next active"><a href="{{ url('scheduledmail/index') }}?page={{page.last}}">>></a></li>		
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
#}
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
	<script type="text/javascript">
		$(function() {
			$('.ShowDialogEditScheduled').click(function() {
				var myURL = $(this).attr('data-id');
				$("#editScheduledMail").attr('href', myURL );
			});
		});
	</script>
{% endblock %}