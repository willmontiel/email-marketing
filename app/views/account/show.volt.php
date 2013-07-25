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
<?php foreach ($allUser as $all) { ?>
	<tr>
		<td><?php echo $all->idUser; ?></td>
		<td><?php echo $all->firstName; ?></td>
		<td><?php echo $all->lastName; ?></td>
		<td><?php echo $all->username; ?></td>
		<td><?php echo $all->email; ?></td>
		<td><?php echo $all->type; ?></td>		
	</tr>
<?php } ?>
</table>
