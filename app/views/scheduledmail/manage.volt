{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
{% endblock %}
{% block content%}
	<div class="row">
		<div class="col-md-12">
			{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'scheduledmail/manage']) }}
		</div>
	</div>
	
	<div class="row">
		<h4 class="sectiontitle">Programación de envío de correos de todas las cuentas</h4>
		<div class="bs-callout bs-callout-info">
			<p>Aqui podrá administrar la programación de todos los correos de todas las cuentas, pausarlos, cancelarlos y también reprogramar fechas.</p>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-12">
			{{ flashSession.output() }}
		</div>		
	</div>
	
	<div class="row">
		<div class="col-md-12">
			<h4>Programación de correos global</h4>
			<table class="table table-striped">
				<thead>
					<tr>
						<th>Cuenta</th>
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
							<td>{{item.idAccount}}</td>
							<td>{{item.name}}</td>
							<td>{{item.status}}</td>
							<td>{{item.totalContacts}}</td>
							<td>{{date('d-M-Y, g:i A', item.scheduleDate)}}</td>
							<td>	
							{% for value in programming_options(item) %}
								{% if value.url == 'null'%}	
									No hay acciones disponibles
								{% elseif value.text == 'Editar'%}

								{% else %}
									<a href="{{ url(value.url) }}manage/{{item.idMail}}" class="btn btn-sm btn-default extra-padding">{{value.text}}</a>
								{% endif %}
							{% endfor %}
							</td>
						</tr>
					{% endfor %}
				</tbody>
			</table>
		</div>		
	</div>
	
	<div class="row">
		<div class="col-sm-12 text-center">
			{{ partial('partials/pagination_static_partial', ['pagination_url': 'scheduledmail/manage']) }}
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
		$(function() {
			$('.ShowDialogTemplate').on('click', function() {
				var myURL = $(this).data('id');
				$("#temapletMail").attr('action', myURL );
			});
		});
	</script>
{% endblock %}