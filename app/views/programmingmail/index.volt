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
{% block sectiontitle %}<i class="icon-envelope icon-2x"></i>Programción de correos{% endblock %}
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
						Administre el envío de los correos
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
	</div>
	<div class="row-fluid">
		<div class="span12 padded">
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
									<td style="text-align: center;">{{item.status}}</td>
									<td style="text-align: center;">{{item.totalContacts}}</td>
									<td style="text-align: center;">{{date('d-M-Y, g:i A', item.scheduleDate)}}</td>
									<td style="text-align: center;">
										<div class="btn-group">
											<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
											<ul class="dropdown-menu">
											{% for value in mail_options(item) %}
												<li><a href="{{ url(value.url) }}{{item.idMail}}"><i class="{{value.icon}}"></i>{{value.text}}</a></li>
											{% endfor %}
												<li><a href="{{ url('mail/clone/') }}{{item.idMail}}"><i class="icon-copy"></i>Duplicar</a></li>
											{% if item.type%}
												<li><a class="ShowDialogTemplate" data-backdrop="static" data-toggle="modal" href="#modal-simple-template" data-id="{{ url('mail/converttotemplate/') }}{{item.idMail}}"><i class="icon-magic"></i>Plantilla</a></li>
											{%endif%}
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
	</script>
{% endblock %}