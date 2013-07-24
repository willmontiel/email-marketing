<?php echo $this->getContent(); ?>
<div class="row-fluid">
    <div class="modal-header">
        <h1>Nombre Base de Datos</h1>
    </div>
    <div class="row-fluid">
        <?php echo Phalcon\Tag::form(array('Dbases/edit', 'method' => 'post')); ?>
        <div class="row-fluid">
            <div class="span3">
                <span>Nombre</span>
            </div>
            <div class="span4">
                <?php echo $editform->render('name'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <span>Descripcion</span>                
            </div>
            <div class="span4">
                <?php echo $editform->render('description'); ?>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span3">
                <span>Descripcion de los Contactos</span>
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