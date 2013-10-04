{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"></i>Bases de Datos{% endblock %}
{%block sectionsubtitle %}Configuración avanzada{% endblock %}
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
					Administre y configure las bases de datos de contactos
				</div>
				<div class="news-text">
					Rails 4.0 is still unfinished, but it is shaping up to become a great release ...
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row-fluid">
	<div class="span8">
	{{ flashSession.output() }}
	</div>
	<div class="span4 text-right"> 
		<a href="{{ url('dbase/new') }}" class="btn btn-default">
			<i class="icon-plus"></i> Crear Base de Datos
		</a>
	</div>
</div>
<br />
<div class="row-fluid">
		<!-- Lista de mis bases de datos -->
	<div class="box">
		<div class="box-header">
			<div class="title">
				Lista de bases de datos
			</div>
		</div>
		<div class="box-content">
	{%for item in page.items%}
			<table class="table table-normal">
				<thead></thead>
				<tbody>
					<tr>
						<td class="span5">
							<div class="box-section news with-icons">
								<div class="avatar purple">
									<i class="icon-book icon-2x"></i>
								</div>
								<div class="news-content">
									<div class="news-title">
										<a href="{{ url('dbase/show/') }}{{item.idDbase}}">{{item.name}}</a>
									</div>
									<div class="news-text">
										{{item.description}}
									</div>
								</div>
							</div>
						</td>
						<td class="span5">
							<ul class="inline pull-right sparkline-box">
								<li class="sparkline-row">
									<h4 class="green"><span>Activos</span> {{item.Cactive|numberf}}</h4>
								</li>

								<li class="sparkline-row">
									<h4 class="gray"><span>Inactivos</span> {{get_inactive(item)|numberf}}</h4>
								</li>

								<li class="sparkline-row">
									<h4 class="blue"><span>Des-suscritos</span> {{item.Cunsubscribed|numberf}}</h4>
								</li>

								<li class="sparkline-row">
									<h4 class="red"><span>Rebotados</span> {{item.Cbounced|numberf}}</h4>
								</li>

								<li class="sparkline-row">
									<h4 class="red"><span>Spam</span> {{item.Cspam|numberf}}</h4>
								</li>
							</ul>
						</td>
						<td class="span2">
							<div class="pull-right">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="{{ url('dbase/edit/') }}{{item.idDbase}}"><i class="icon-pencil"></i> Editar</a></li>
										<li><a class="ShowDialog" data-toggle="modal" href="#modal-simple" data-id="{{ url('dbase/delete/') }}{{item.idDbase}}"><i class="icon-trash"></i> Eliminar </a></li>
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
								<li class="previous active"><a href="{{ url('dbase/index') }}"><<</a></li>
								<li class="previous active"><a href="{{ url('dbase/index') }}?page={{ page.before }}"><</a></li>
							{% endif %}

							{% if page.current >= page.total_pages %}
								<li class="next"><a href="#" class="inactive">></a></li>
								<li class="next"><a href="#" class="inactive">>></a></li>
							{% else %}
								<li class="next active"><a href="{{ url('dbase/index') }}?page={{page.next}}">></a></li>
								<li class="next active"><a href="{{ url('dbase/index') }}?page={{page.last}}">>></a></li>		
							{% endif %}
						</ul>
					 </div>
				 </div>
				 <div class="span5">
					 <br />
					 Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
					 Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
				 </div>
				<div class="span2 text-right">
					<br>
					<a href="{{ url('') }}" class="btn btn-default"><i class="icon-reply"></i> Regresar</a>
				</div>
			</div>
		</div>
	</div>
		<!-- Fin de mi lista de bases de datos -->
</div>

<div id="modal-simple" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h6 id="modal-tablesLabel">Eliminar Base de Datos</h6>
	</div>
	<div class="modal-body">
		Esta seguro que desea eliminar esta Base de Datos.
		<br/>
		Recuerde que si elimina la Base de Datos se perderan todos los contactos, listas de contactos y segmentos que pertenezcan a ella
	</div>
	<div class="modal-footer">
	  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
	  <a href="" id="deleteDb" class="btn btn-danger" >Eliminar</a>
	</div>
</div>

<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteDb").attr('href', myURL );
	});
</script>

{% endblock %}
