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
		Esta son las bases de datos de la cuenta, aqui podrá encontrar información acerca de la configuración
		de cada base de datos, los contactos activos e inactivos, etc. Además podrá crear campos personalizados y configurar las bases de datos.
		
	</div>
	<div class="row">
		{{ flashSession.output() }}
	</div>
	<div class="col-md-3 col-md-offset-8">
		<a href="{{ url('dbase/new') }}" class="btn btn-default btn-sm extra-padding">
			<i class="glyphicon glyphicon-plus"></i> Crear base de datos
		</a>
		<div class="space"></div>
	</div>
	
		<!-- Lista de mis bases de datos -->
	<table class="table table-striped">
		<thead></thead>
		<tbody>
		{%for item in page.items%}
			<tr style="border-left: solid 10px {{item.color}}">
				<td>
					
					<a href="{{ url('dbase/show') }}/{{item.idDbase}}"><strong>{{item.name}}</strong></a><br>
						{{item.description}}
				</td>
				<td>
					<ul class="list-inline pull-right">
						<li class="card cont">
							<span class="number">{{item.Cactive|numberf}}</span>
							<img src="{{url('')}}b3/images/icon-user.png" class="center-block" />
							<p>Contactos</p>
						</li>
						<li class="card list">
							<span class="number"> {{item.Cactive|numberf}} </span>
							<img src="{{url('')}}b3/images/icon-list.png" class="center-block" />
							<p>Listas </p> {#  Cambiar porque esta mostrando activos  #}
						</li>
						<li class="card seg">
							<span class="number"> {{item.Cactive|numberf}} </span>
							<img src="{{url('')}}b3/images/icon-pie.png" class="center-block" />
							<p>Segmentos </p> {#  Cambiar porque esta mostrando activos  #}
						</li>

{#
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
#}
					</ul>
				</td>
				<td>
					<a href="{{ url('dbase/edit/') }}{{item.idDbase}}" class="btn btn-default btn-sm extra-padding"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
					<a data-toggle="modal" href="#modal-simple" data-id="{{ url('dbase/delete/') }}{{item.idDbase}}" class="btn btn-default btn-delete btn-sm extra-padding ShowDialog"><i class="glyphicon glyphicon-trash"></i> Eliminar </a>
					<a href="{{url('statistic/dbase')}}/{{item.idDbase}}" class="btn btn-default btn-sm extra-padding"> <span class="glyphicon glyphicon-stats"> </span></a>
				</td>
			</tr>
	{%endfor%}
		</tbody>
	</table>
	<div class="space"></div>
	<div class="col-sm-12 text-center">
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'dbase/index']) }}
	</div>

	<!-- Fin de mi lista de bases de datos -->
</div>

{#  Este es el modal (lightbox) que se activa al hacer clic en el boton eliminar   #}
<div id="modal-simple" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title">Eliminar Base de Datos</h4>
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
	</div>
</div>

<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteDb").attr('href', myURL );
	});
</script>

{% endblock %}
