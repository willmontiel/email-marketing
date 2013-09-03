{% extends "templates/index.volt" %}

{% block content %}

<!-- aqui inicia mi contenido -->
<div class="alert-error"><h4>{{ flashSession.output() }}</h4></div>
<div class="row-fluid">
	<div class="modal-header">
		<h1>Bases de Datos</h1>
		<div class="text-right"> <a href="{{ url('dbase/new') }}"><h5>Crear Base de Datos</h5></a></div>
	</div>
</div>
	<div class="row-fluid">
		<!-- Lista de mis bases de datos -->
		{%for item in page.items%}
			<div class="row-fuid">
				<div class="span6" >
					<div class="row-fluid break-word">
							<h3><a href="{{ url('dbase/show/') }}{{item.idDbase}}">{{item.name}}</a></h3>
							<span>{{item.description}}</span>
					</div>
					<div class="row-fluid">
							<div class="span3 text-center">
									<div class="row-fluid">
											<span class="number-medium text-gray-color text-center">26</span>
									</div>
									<div class="row-fluid">
											<span class="fui-radio-checked"></span> Segmentos
									</div>  
							</div>
							<div class="span3 text-center">
									<div class="row-fluid">
											<span class="number-medium text-gray-color ">12</span>
									</div>
									<div class="row-fluid">
											<span class="fui-list"></span> Listas
									</div>  
							</div>
					</div>
				</div>
				<div class="span3 ">
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
				<div class="span2">
					<dl>
							<dd><a href="{{ url('dbase/edit/') }}{{item.idDbase}}">Editar</a></dd>
							<dd><a href="{{ url('#delete') }}#delete{{item.idDbase}}" data-toggle="modal">Eliminar</a></dd>
							<dd><a href="#">Agregar Contacto</a></dd>
					</dl>
				</div>
			</div>
		{%endfor%}
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
                <form action = "{{ url('dbase/delete/') }}{{item.idDbase}}", method="post">
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
