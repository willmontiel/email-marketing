{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-user-md"></i> Informacion de usuarios de cuentas{%endblock%}
{% block content %}
{{ content() }}
<div class="container-fluid padded">
	<div class="text-right">
		<a href="#" class="btn btn-default"><i class="icon-plus"></i> Crear nuevo usuario</a>
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-content">
					<table class='table table-normal'>
						<thead>
							<tr>
								<td>Id</td>
								<td>Nombres</td>
								<td>Apellidos</td>
								<td>Nombre de usuario</td>
								<td>E-Mail</td>
								<td>Tipo de usuario</td>
								<td>Fecha de registro</td>
								<td>Última actualización</td>
							</tr>
						</thead>
					{%for all in allUser%}
						<tbody>
							<tr>
								<td>{{all.idUser}}</td>
								<td>{{all.firstName}}</td>
								<td>{{all.lastName}}</td>
								<td>{{all.username}}</td>
								<td>{{all.email}}</td>
								<td>{{all.userrole}}</td>
								<td>{{date('Y-m-d', all.createdon)}}</td>
								<td>{{date('Y-m-d', all.updatedon)}}</td>
								<td>
									<a href="#">Editar</a><br>
									<a href="#" data-toggle="modal">Eliminar</a>
								</td>
							</tr>
						</tbody>
					{%endfor%}
					</table>
				</div>
			</div>

		</div>

		<div class="span12"></div>
		<div class="text-right">
			<p>
				<a href="{{ url('account') }}" class="btn btn-inverse">Regresar</a>
			</p>
		</div>
	</div>
</div>
{% endblock %}