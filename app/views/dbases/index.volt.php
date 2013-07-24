<?php echo $this->getContent(); ?>
        <div class="row-fluid">
            <div class="modal-header">
                <h1>Bases de Datos</h1>
            </div>
        </div>
        <div class="row-fluid">
            <?php foreach ($dbases as $dbase) { ?>
                <div class="row-fluid">
                    <div class="span6" >
                        <h3><a href="dbases/read?id=<?php echo $dbase->idDbases; ?>"><?php echo $dbase->name; ?></a></h3>
                        <span><?php echo $dbase->description; ?></span>
                        <div class="">
                                Segmentos (icono)
                                Listas (icono)
                        </div>
                    </div>
                    <div class="span3">
                        <dl>
                            <dd><?php echo $dbase->contact; ?> Contactos</dd>
                            <dd><?php echo $dbase->unsubscribed; ?> Desuscritos</dd>
                            <dd><?php echo $dbase->bounced; ?> Bounced</dd>
                        </dl>
                    </div>
                    <div class="span2">
                        <dl>
                            <dd><a href="dbases/edit?id=<?php echo $dbase->idDbases; ?>">Editar</a></dd>
                            <dd><a href="#">Eliminar</a></dd>
                            <dd><a href="#">Agregar Contacto</a></dd>
                        </dl>
                    </div>
                </div>
            <?php } ?>
         </div>
    