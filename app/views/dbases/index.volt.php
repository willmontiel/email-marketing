<?php echo $this->getContent(); ?>
<div class="alert-error"><h4><?php echo $this->flashSession->output(); ?></h4></div>
        <div class="row-fluid">
            <div class="modal-header">
                <h1>Bases de Datos</h1>
              <div class="text-right"> <a href="dbases/new"><h5>Crear Base de Datos</h5></a></div>
            </div>
        </div>
        <div class="row-fluid">
            <?php foreach ($dbases as $dbase) { ?>
                <div class="row-fluid">
                    <div class="span6" >
                        <h3><a href="dbases/show/<?php echo $dbase->idDbases; ?>"><?php echo $dbase->name; ?></a></h3>
                        <span><?php echo $dbase->description; ?></span>
                        <div class="">
                                Segmentos (icono)
                                Listas (icono)
                        </div>
                    </div>
                    <div class="span3">
                        <dl>
                            <dd><?php echo $dbase->Cactive; ?> Activos</dd>
							<dd><?php echo $dbase->Cinactive; ?> Inactivos</dd>
                            <dd><?php echo $dbase->Cunsubscribed; ?> Des-suscritos</dd>
                            <dd><?php echo $dbase->Cbounced; ?> Rebotados</dd>
							<dd><?php echo $dbase->Cspam; ?> Spam</dd>
                        </dl>
                    </div>
                    <div class="span2">
                        <dl>
                            <dd><a href="dbases/edit/<?php echo $dbase->idDbases; ?>">Editar</a></dd>
                            <dd><a href="#delete" data-toggle="modal">Eliminar</a></dd>
                            <dd><a href="#">Agregar Contacto</a></dd>
                        </dl>
                    </div>
                </div>
            <?php } ?>
         </div>

<div id="delete" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3>Seguro que Desea Eliminar</h3>
  </div>
  <div class="modal-body">
    <form action = "/emarketing/dbases/delete/<?php echo $dbase->idDbases; ?>", method="post">
      <p>Para eliminar escriba la palabra "DELETE"</p>
      <?php echo Phalcon\Tag::textField(array('delete')); ?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Cerrar</button>
    <button class="btn btn-primary">Eliminar</button>
  </div>
</div>
    