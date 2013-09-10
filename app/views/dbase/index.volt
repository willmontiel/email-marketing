{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"></i>Bases de Datos{% endblock %}
{%block sectionsubtitle %}Configuración avanzada{% endblock %}
{% block content %}
<!-- aqui inicia mi contenido 
<div class="alert  alert-error"><h4>{{ flashSession.output() }}</h4></div>
-->
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
			<div class="box-section news with-icons">
				<div class="row-fluid">
					<div class="avatar cyan">
						<i class="icon-book icon-2x"></i>
					</div>
					<div class="news-content span3">
						<div class="news-title">
							<a href="{{ url('dbase/show/') }}{{item.idDbase}}">{{item.name}}</a>
						</div>
						<div class="news-text">
							{{item.description}}
						</div>
					</div>
					<div class="news-content span3">
						<table>
							<tr>
								<td class="text-right">
									<dl>
										<dd>{{item.Cactive|numberf}}</dd>
										<dd>{{get_inactive(item)|numberf}}</dd>
										<dd>{{item.Cunsubscribed|numberf}}</dd>
										<dd>{{item.Cbounced|numberf}}</dd>
										<dd>{{item.Cspam|numberf}}</dd>
									</dl>
								</td>
								<td class="text-left">
									<dl>
										<dd>Activos</dd>
										<dd>Inactivos</dd>
										<dd>Des-suscritos</dd>
										<dd>Rebotados</dd>
										<dd>Spam</dd>
									</dl>
								</td>
							</tr>
						</table>
					</div>
					<div class="news-content span2">
						<div class="pull-right" style="margin-right: 10px;">
								<div class="btn-group">
									<button class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i> Acciones <span class="caret"></span></button>
									<ul class="dropdown-menu">
										<li><a href="{{ url('dbase/edit/') }}{{item.idDbase}}"><i class="icon-pencil"></i> Editar</a></li>
										<li><a href="{{ url('#delete') }}#delete{{item.idDbase}}" data-toggle="modal"><i class="icon-trash"></i> Eliminar</a></li>
									</ul>
								</div>
							</div>
					</div>
				</div>
			</div>
	{%endfor%}
	</div>
</div>
		<!-- Fin de mi lista de bases de datos -->
</div>


	<div class="row-fluid">
		<div class="span5">
			<div class="pagination">
				<ul>
					{% if page.current == 1 %}
						<li class="previous"><a href="#" class="inactive"><span class="fui-arrow-left"><span class="fui-arrow-left"></span></span></a></li>
						<li class="previous"><a href="#" class="inactive"><span class="fui-arrow-left"></span></a></li>
					{% else %}
						<li class="previous active"><a href="{{ url('dbase/index') }}"><span class="fui-arrow-left"><span class="fui-arrow-left"></span></span></a></li>
						<li class="previous active"><a href="{{ url('dbase/index') }}?page={{ page.before }}"><span class="fui-arrow-left"></span></a></li>
					{% endif %}
								
					{% if page.current >= page.total_pages %}
						<li class="next"><a href="#" class="inactive"><span class="fui-arrow-right"></span></a></li>
						<li class="next"><a href="#" class="inactive"><span class="fui-arrow-right"><span class="fui-arrow-right"></span></span></a></li>
					{% else %}
						<li class="next active"><a href="{{ url('dbase/index') }}?page={{page.next}}"><span class="fui-arrow-right"></span></a></li>
						<li class="next active"><a href="{{ url('dbase/index') }}?page={{page.last}}"><span class="fui-arrow-right"><span class="fui-arrow-right"></span></span></a></li>		
					{% endif %}
				</ul>
			 </div>
		 </div>
		 <div class="span3">
			 <br><br>
			 Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
			 Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
		 </div>
		<div class="span3">
		</div>
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
