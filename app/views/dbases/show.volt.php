<?php echo $this->getContent(); ?>
<div class="row-fluid">
	<div class="row-fluid" id="top-info">
		<div class="row-fluid" id="nombre-bd">
			<div class="span8">
				<div class="modal-header">
					<h1><?php echo $sdbase->name; ?></h1>
				</div>
			</div>
			<div class="span4">
				<a href="/emarketing/dbases"><h3>Regresar</h3></a>
			</div>
		</div>

		<div class="row-fluid" id="info-bd">
			<div class="span8" id="descripTotal-bd">
				<div class="row-fluid" id="descrip-db">
					Descripcion: <?php echo $sdbase->description; ?>
				</div>
				<div class="row-fluid" id="descrip-contact">
					Descripcion de Contactos: <?php echo $sdbase->Cdescription; ?>
				</div>
				<div class="row-fluid" id="fecha-add">
					Fecha
				</div>
			</div>
			<div class="span4" id="info-contact">
				<div class="row-fluid" id="total-contacts">
					<p><?php echo $sdbase->Ctotal; ?></p>
					<p>Contactos</p>
				</div>
				<div class="row-fluid" id="detalles-contacts">
					<p><?php echo $sdbase->Ctotal; ?></p>
					<p>Contactos Activos</p>
					<p><?php echo $sdbase->Ctotal; ?></p>
					<p>Contactos Inactivos</p>
					<p><?php echo $sdbase->Ctotal; ?></p>
					<p>Contactos Des-suscritos</p>
					<p><?php echo $sdbase->Ctotal; ?></p>
					<p>Contactos Rebotados</p>
					<p><?php echo $sdbase->Cspam; ?></p>
					<p>Contactos Spam</p>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="row-fluid">
    <div class="span12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Direccion de Correo</th>
                    <th>Nombre</th>
                    <th>Estado</th>
                    <th>Añadido en la Fecha</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>            
        </table>
    </div>    
</div>

