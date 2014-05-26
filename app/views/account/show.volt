{% extends "templates/index_b3.volt" %}
{% block content %}
	{#   navegacion botones pequeños   #}
	{{ partial('partials/small_buttons_menu_partial_for_tools', ['activelnk': 'account']) }}
	<div class="row">
		<div class="col-md-12">
			<h4 class="sectiontitle">Información de usuarios de cuentas</h4>
			<div class="bs-callout bs-callout-info">
				Aquí puede editar información de los usuarios de la cuenta, puede dar permisos y quitarlos. Puede
				crear nuevos usuarios como también eliminarlos.
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			{{ flashSession.output() }}
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12 text-right">
			<a href="{{url('account/newuser')}}/{{idAccount}}" class="btn btn-sm btn-default extra-padding"><span class="glyphicon glyphicon-plus"></span> Crear nuevo usuario</a>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<table class='table table-striped table-bordered'>
				<thead>
					<tr>
						<th>Username</th>
						<th>Tipo de usuario</th>
						<th>Fecha de registro</th>
						<th>Última actualización</th>
					</tr>
				</thead>
			{%for item in page.items%}
				<tbody>
					<tr>
						<td>
							<h5><strong>{{item.idUser}} - {{item.username}}</strong></h5>
							<p>{{item.firstName}} {{item.lastName}}</p>
							<p>{{item.email}}</p>
						</td>
						<td>{{item.userrole}}</td>
						<td>{{date('Y-m-d', item.createdon)}}</td>
						<td>{{date('Y-m-d', item.updatedon)}}</td>
						<td class="text-right">
							<a href="{{url('session/loginlikethisuser/')}}{{item.idUser}}" class="btn btn-sm btn-primary extra-padding">Ingresar como este usuario</a>
							<a href="{{url('account/edituser/')}}{{item.idUser}}" class="btn btn-sm btn-default extra-padding"><span class="glyphicon glyphicon-pencil"></span> Editar</a>
							<button data-toggle="modal"  data-target="#modal-simple" data-id="{{url('account/deleteuser/')}}{{item.idUser}}" class="ShowDialog btn btn-sm btn-default btn-delete extra-padding"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
						</td>
					</tr>
				</tbody>
			{%endfor%}
			</table>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 text-center">
			{{ partial('partials/pagination_static_partial', ['pagination_url': 'account/show']) }}
		</div>
	</div>

	<div class="modal fade" id="modal-simple" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Eliminar Usuario</h4>
				</div>
				<div class="modal-body">
					¿Está seguro que desea eliminar este usuario?
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<a href="" id="deleteUser" class="btn btn-sm btn-default btn-delete extra-padding" >Eliminar</a>
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
