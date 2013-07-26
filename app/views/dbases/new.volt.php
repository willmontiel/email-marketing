<?php echo $this->getContent(); ?>
<div class="row-fluid">
    <div class="modal-header">
        <h1>Nueva Base de Datos</h1>
    </div>
    <div class="row-fluid">
        <form action = "/eMarketing/dbases/new", method="post">
        <div class="row-fluid">
            <div class="span3">
                <label for="name">Nombre</label>
            </div>
            <div class="span4">
                <?php echo $editform->render('name'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="description">Descripcion</label>                
            </div>
            <div class="span4">
                <?php echo $editform->render('description'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <label for="descriptionContacts">Descripcion de los Contactos</label>
            </div>
            <div class="span4">
                <?php echo $editform->render('descriptionContacts'); ?>
            </div>
        </div>
    </div>
    <div class="row-fluid">
       <?php echo Phalcon\Tag::submitButton(array('Guardar', 'class' => 'btn btn-success')); ?>
    </div>
    </form>
</div>