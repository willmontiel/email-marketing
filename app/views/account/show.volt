{% extends "templates/index.volt" %}

{% block content %}
{{ content() }}
<div class="row-fluid">
		<h1>Informacion de usuarios de cuentas</h1>
</div>
	
	<div class="text-right">
		<h3><a href="account/new" >Crear nuevo usuario</a></h3>
	</div>

<div class="row-fluid">
	<div class="span12">
		<table class='table table-striped'>
			<tr>
				<th>Id</th>
				<th>Nombres</th>
				<th>Apellidos</th>
				<th>Nombre de usuario</th>
				<th>E-Mail</th>
				<th>Role</th>
			</tr>
		{%for all in allUser%}
			<tr>
				<td>{{all.idUser}}</td>
				<td>{{all.firstName}}</td>
				<td>{{all.lastName}}</td>
				<td>{{all.username}}</td>
				<td>{{all.email}}</td>
				<td>{{all.userrole}}</td>
				<td>
					<a href="account/edit/{{all.idAccount}}">Editar</a><br>
					<a href="#" data-toggle="modal">Eliminar</a>
				</td>
			</tr>
		{%endfor%}
		</table>
	</div>
	
	<div class="span12"></div>
	<div class="text-right">
		<p>
			{{link_to('account', 'class':"btn btn-inverse", "Regresar")}}
		</p>
	</div>
</div>
{% endblock %}