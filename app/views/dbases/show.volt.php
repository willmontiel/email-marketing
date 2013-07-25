<?php echo $this->getContent(); ?>
<div class="row-fluid">
    <div class="modal-header">
        <h1><?php echo $sdbase->name; ?></h1>
    </div>
</div>
<div class="row-fluid">
    <div class="span12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th><span>Nombre</span></td>
                    <th><span>Descripcion</span></th>
                    <th><span>Descripcion Contactos</span></th>
                    <th><span>Contactos</span></th>
                    <th><span>Des-suscritos</span></th>
                    <th><span>Rebotados</span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?php echo $sdbase->name; ?>
                    </td>
                    <td>
                        <?php echo $sdbase->description; ?>
                    </td>
                    <td>
                        <?php echo $sdbase->descriptionContacts; ?>
                    </td>
                    <td>
                        <?php echo $sdbase->contact; ?>
                    </td>
                    <td>
                        <?php echo $sdbase->unsubscribed; ?>
                    </td>
                    <td>
                        <?php echo $sdbase->bounced; ?>
                    </td>
                </tr>
            </tbody>
        </table>
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

