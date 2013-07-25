 <div class="row-fluid">
	<div id="specific" class="hero-unit">
		<h1>Mail gorilla</h1>
	</div>
 </div>
 <div class="row-fluid">
	 <div class="span12" >
		 <table class='table table-striped'>
			<tr>
				<th>Id</th>
				<th>Nombre de la cuenta</th>
				<th>Modo de uso</th>
				<th>Espacio para archivos</th>
				<th>Cuota de mensajes</th>
				<th>Modo de pago</th>
			</tr>
		 <?php foreach ($allAccount as $all) { ?>
			<tr>
				<td><?php echo $all->idAccount; ?></td>
				<td><a href="account/show/<?php echo $all->idAccount; ?>"><?php echo $all->companyName; ?></a></td>
				<td><?php echo $all->modeUse; ?></td>
				<td><?php echo $all->fileSpace; ?></td>
				<td><?php echo $all->messageQuota; ?></td>
				<td><?php echo $all->modeAccounting; ?></td>
				<td><a href="account/edit/<?php echo $all->idAccount; ?>">Editar</a></td>
			</tr>
		 <?php } ?>
	    </table>
	 </div>
 </div>

