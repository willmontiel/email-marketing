{% extends "templates/index_b3.volt" %}
{% block sectiontitle %}<i class="icon-group icon-2x"></i>Administración de usuarios{% endblock %}
{%block sectionsubtitle %}Cree, edite o proporcione permisos a los usuarios de su cuenta{% endblock %}

{% block content %}
<div class="row">
	{# Botones de navegacion #}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'user']) }}
		
	<h4 class="sectiontitle">Usuarios</h4>

	<div class="bs-callout bs-callout-info">
		Aqui puede editar información de los usuarios de la cuenta, puede dar permisos y quitarlos. Tambien puede
		crear nuevos usuarios como tambien eliminarlos.
	</div>

	{{ flashSession.output() }}
	
	<div class="row">
		<div class="col-md-2 col-md-offset-10">
			<a class="btn btn-default btn-sm extra-padding" href="{{ url('user/new') }}">
				<span class="glyphicon glyphicon-plus"></span> Nuevo Usuario</a>
		</div>
	</div>
	
	<table class="table table-striped">
		<thead>
			<tr>
				<td></td>
				<td>Tipo de usuario</td>
				<td>Fecha de creación</td>
				<td>Última actualización</td>
				<td></td>
			</tr>
		</thead>
		<tbody>
	{%for item in page.items%}
			<tr>
				<td>
					<div class="box-section news with-icons">
						<div class="avatar blue">
							<i class="icon-user icon-2x"></i>
						</div>
						<div class="news-content">
							<div class="news-title">
								{{item.username}}
							</div>
							<div class="news-text">
								{{item.firstName}} {{item.lastName}}<br />
								{{item.email}}
							</div>
						</div>
					</div>
				</td>
				<td>{{item.userrole}}</td>
				<td>{{ date('d/m/Y',item.createdon)}}</td>
				<td>{{ date('d/m/Y',item.updatedon)}}</td>
				<td>
					<div class="pull-right">
						<a href="{{url('user/edit/')}}{{item.idUser}}" class="btn btn-default btn-sm"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
						<a class="ShowDialog btn btn-default btn-delete btn-sm" data-toggle="modal" href="#modal-simple" data-id="{{url('user/delete/')}}{{item.idUser}}"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>
					</div>
				</td>
			</tr>
	{% endfor %}
		</tbody>
	</table>
	<div class="col-sm-12 text-center">
		{{ partial('partials/pagination_static_partial', ['pagination_url': 'user/index']) }}
	</div>
</div>


<div id="modal-simple" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			  <h6 id="modal-tablesLabel">Eliminar Usuario</h6>
			</div>
			<div class="modal-body">
				Esta seguro que desea eliminar este Usuario.
			</div>
			<div class="modal-footer">
			  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
			  <a href="" id="deleteUser" class="btn btn-danger" >Eliminar</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).on("click", ".ShowDialog", function () {
		var myURL = $(this).data('id');
		$("#deleteUser").attr('href', myURL );
	});
</script>

{% endblock %}