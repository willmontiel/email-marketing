{% extends "templates/editor_template.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('tablesorter/jquery-latest.js')}}
	{{ javascript_include('tablesorter/jquery.tablesorter.js')}}
	<script type="text/javascript">
		$(document).ready(function() { 
			$("#processes-table").tablesorter(); 
		}); 
	</script>
{% endblock %}
{% block content %}
	<div class="area-top clearfix">
		<div class="pull-left header">
			<h3 class="title">
				<i class="icon-spinner icon-spin"></i> Monitor de procesos de envío
			</h3>
			<h5>
				Aqui podrá encontrar toda la información relacionada con los envíos de correos eléctronicos,
				como la programación, estados y que se estan enviando actualmente
			</h5>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12 padded">
			<div class="box">
				<div class="box-header padded">
					<div class="input-prepend span3">
						<a class="add-on" href="#">
							<i class="icon-search"></i>
						</a>
						<input type="text" placeholder="Buscar...">
					</div>
				</div>
				<div class="box-content">
					<table id="processes-table" class="tablesorter table table-normal">
						<thead>
							<tr>
								<th>Id de correo</th>
								<th>Cuenta</th>
								<th>Estado</th>
								<th>Tiempo estimado</th>
							</tr>
						</thead>
						<tbody>
							{%for item in page.items%}
								<tr>
									<td>{{item.idMail}}</td>
									<td>{{item.idAccount}}</td>
									<td>{{item.status}}</td>
									<td class="center">1.7</td>
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
		</div>
	</div>	
{% endblock %}