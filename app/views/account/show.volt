<div id="specific" class="hero-unit">
  <h1>Mail gorilla</h1>
 </div>
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
