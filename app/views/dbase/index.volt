{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-book icon-2x"></i>Bases de Datos{% endblock %}
{%block sectionsubtitle %}Configuración avanzada{% endblock %}
{% block content %}

{# Menu de navegacion pequeño #}
{{ partial('contactlist/small_buttons_menu_partial', ['activelnk': 'dbase']) }}
{# /Menu de navegacion pequeño #}


<div class="row">
	<h4 class="sectiontitle">Bases de datos de contactos</h4>
	<div class="bs-callout bs-callout-info">
		Esta es la página principal de las bases de datos en la cuenta, aqui podrá encontrar información acerca de la configuración
		de cada base de datos, los contactos activos e inactivos, etc. Además podrá crear campos personalizados y configurar las bases
		dependiendo de las necesidades.
		
	</div>
	<div class="">
		{{ flashSession.output() }}
	</div>
	<div class="col-md-3 col-md-offset-8">
		<a href="{{ url('dbase/new') }}" class="btn btn-default btn-sm extra-padding">
			<i class="glyphicon glyphicon-plus"></i> Crear base de datos
		</a>
	</div>
	<div class="space"></div>
		<!-- Lista de mis bases de datos -->
	<table class="table table-strepead">
		<thead></thead>
		<tbody>
		{%for item in page.items%}
			<tr>
				<td>
					<a href="{{url('statistic/dbase')}}/{{item.idDbase}}"><i class="icon-bar-chart icon-2x"></i></a><br/>
					<a href="{{ url('dbase/show') }}/{{item.idDbase}}"><strong>{{item.name}}</strong></a>
					{{item.description}}
				</td>
				<td>
					<ul class="list-inline pull-right">
						<li>
							<h4 class="green"><span>Activos:</span> {{item.Cactive|numberf}}</h4>
						</li>

						<li>
							<h4 class="gray"><span>Inactivos:</span> {{get_inactive(item)|numberf}}</h4>
						</li>

						<li>
							<h4 class="blue"><span>Des-suscritos:</span> {{item.Cunsubscribed|numberf}}</h4>
						</li>

						<li>
							<h4 class="orange"><span>Rebotados:</span> {{item.Cbounced|numberf}}</h4>
						</li>

						<li>
							<h4 class="red"><span>Spam:</span> {{item.Cspam|numberf}}</h4>
						</li>
					</ul>
				</td>
				<td>
					<a href="{{ url('dbase/edit/') }}{{item.idDbase}}" class="btn btn-default btn-sm extra-padding"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
					<a data-toggle="modal" href="#modal-simple" data-id="{{ url('dbase/delete/') }}{{item.idDbase}}" class="btn btn-default btn-delete btn-sm extra-padding"><i class="glyphicon glyphicon-trash"></i> Eliminar </a>
				</td>
			</tr>
	{%endfor%}
		</tbody>
	</table>
	
	{{ partial("partials/pagination_partial") }}

	<div class="box-footer padded">
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
	 <div class="">
		 Registros totales: <span class="label label-filling">{{page.total_items}}</span>&nbsp;
		 Página <span class="label label-filling">{{page.current}}</span> de <span class="label label-filling">{{page.total_pages}}</span>
	 </div>
		<!-- Fin de mi lista de bases de datos -->
</div>
<div class="row">
	<div class="span5 text-right"> 
		<a href="{{ url('dbase/new') }}" class="btn btn-default">
			<i class="icon-plus"></i> Crear base de datos
		</a>
	</div>
</div>

<div id="modal-simple" class="modal hide fade" aria-hidden="false">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	  <h6 id="modal-tablesLabel">Eliminar Base de Datos</h6>
	</div>
	<div class="modal-body">
		<p>
			¿Esta seguro que desea eliminar esta base de datos?
		</p>
		<p>
			Recuerde que si elimina la base de datos se perderan todos los contactos, listas de contactos y segmentos que pertenezcan a ella
		</p>
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
