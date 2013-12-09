{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url}}';
		$(function() { 
			$("#processes-table").tablesorter(); 
		}); 
	</script>
{% endblock %}
{% block sectiontitle %}<i class="icon-calendar icon-2x"></i>Programación de correos{% endblock %}
{%block sectionsubtitle %}Administre cuando se deben enviar los correos{% endblock %}
{% block content%}
	<div class="row-fluid">
		<div class="box">
			<div class="box-section news with-icons">
				<div class="avatar green">
					<i class="icon-lightbulb icon-2x"></i>
				</div>
				<div class="news-content">
					<div class="news-title">
						Administre la programación de envío de los correos
					</div>
					<div class="news-text">
						Aqui podrá administrar la programación de los correos, pausarlos, cancelarlos y tambien reprogramar fechas
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
			<a href="{{url('mail')}}" class="btn btn-blue"><i class="icon-envelope"></i> Todos los correos</a>
		</div>
	</div>
	<br />
	<div class="row-fluid">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Programación de correos
				</div>
			</div>
			<div class="box-content" >
				<table id="processes-table" class="tablesorter table table-normal">
					<thead>
						<tr>
							<th>Nombre</th>
							<th>Estado</th>
							<th>Destinatarios aprox.</th>
							<th>Programado para</th>
							<td>Acciones</td>
						</tr>
					</thead>
					<tbody id="resultado">
						 {% for item in page.items %}
							<tr>
								<td>{{item.name}}</td>
								<td>{{item.status}}</td>
								<td>{{item.totalContacts}}</td>
								<td>{{date('d-M-Y, g:i A', item.scheduleDate)}}</td>
								<td style="text-align: center;">
									<div class="btn-group">
										<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
										<ul class="dropdown-menu">
										{% for value in programming_options(item) %}
											{% if value.url == 'null'%}	
												&nbsp;<i class="icon-minus-sign"></i> No hay acciones disponibles&nbsp;
											{% else %}
												<li><a href="{{ url(value.url) }}index/{{item.idMail}}"><i class="{{value.icon}}"></i>{{value.text}}</a></li>
											{% endif %}
										{% endfor %}
										</ul>
									</div>
								</td>
							</tr>
						{% endfor %}
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
									<li class="previous active"><a href="{{ url('programmingmail/index') }}"><<</a></li>
									<li class="previous active"><a href="{{ url('programmingmail/index') }}?page={{ page.before }}"><</a></li>
								{% endif %}

								{% if page.current >= page.total_pages %}
									<li class="next"><a href="#" class="inactive">></a></li>
									<li class="next"><a href="#" class="inactive">>></a></li>
								{% else %}
									<li class="next active"><a href="{{ url('programmingmail/index') }}?page={{page.next}}">></a></li>
									<li class="next active"><a href="{{ url('programmingmail/index') }}?page={{page.last}}">>></a></li>		
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
	</div>
{% endblock %}