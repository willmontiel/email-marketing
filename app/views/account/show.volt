<div class="row-fluid">
	<div class="span12">
		<h1>Informacion de usuarios de cuentas</h1>
	</div>
		
	<div class="span12"></div>

	<div class="span12">
		<table class='table table-striped'>
			<tr>
				<th>Id</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Nombre de usuario</th>
				<th>E-Mail</th>
				<th>Tipo</th>
			</tr>
		{%for all in allUser%}
			<tr>
				<td>{{all.idUser}}</td>
				<td>{{all.firstName}}</td>
				<td>{{all.lastName}}</td>
				<td>{{all.username}}</td>
				<td>{{all.email}}</td>
				<td>{{all.type}}</td>		
			</tr>
		{%endfor%}
		</table>
	</div>
	
	<div class="span12">
		<p>
			{{link_to('account', 'class':"btn btn-inverse", "Regresar")}}
		</p>
	</div>
</div>