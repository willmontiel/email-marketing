{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"></i>Bases de Datos{% endblock %}
{%block sectionsubtitle %}Configuración avanzada{% endblock %}
{% block content %}
<!-- aqui inicia mi contenido -->
{{ flashSession.output() }}
<div class="row-fluid">
	<div class="text-right"> 
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
										<li><a href="{{ url('dbase/delete/') }}{{item.idDbase}}"><i class="icon-trash"></i> Eliminar </a></li>
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


	
                
{%for item in page.items%}
<div id="delete{{item.idDbase}}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>Seguro que Desea Eliminar</h3>
        </div>
        <div class="modal-body">
                <form action = "{{url('dbase/delete')}}/{{item.idDbase}}" method="post">
                        <p>Para eliminar escriba la palabra "DELETE"</p>
                        {{text_field("delete")}}
        </div>
        <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
                <button class="btn btn-primary">Eliminar</button>
        </div>
    </form>
</div>
{%endfor%}
{% endblock %}
