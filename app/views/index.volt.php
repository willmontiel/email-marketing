<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <?php echo Phalcon\Tag::getTitle(); ?>
        <?php echo Phalcon\Tag::stylesheetLink('bootstrap/css/bootstrap.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('bootstrap/css/bootstrap-responsive.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/style.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/flat-ui.css'); ?>
        <?php echo Phalcon\Tag::stylesheetLink('css/bootstrap-modal.css'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery-1.8.3.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('bootstrap/js/bootstrap.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery-ui-1.10.3.custom.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.ui.touch-punch.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap.min.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-select.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-switch.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/flatui-checkbox.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/flatui-radio.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.tagsinput.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.placeholder.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/jquery.stacktable.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/application.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-modal.js'); ?>
        <?php echo Phalcon\Tag::javascriptInclude('js/bootstrap-modalmanager.js'); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Proyect">
        <meta name="author" content="Ivan">

        <style>
            .container { min-width: 960px; }
            .row-fluid input[type="text"], 
            .row-fluid input[type="password"], 
            .row-fluid input[type="datetime"], 
            .row-fluid input[type="datetime-local"], 
            .row-fluid input[type="date"], 
            .row-fluid input[type="month"], 
            .row-fluid input[type="time"], 
            .row-fluid input[type="week"], 
            .row-fluid input[type="number"], 
            .row-fluid input[type="email"], 
            .row-fluid input[type="url"], 
            .row-fluid input[type="search"], 
            .row-fluid input[type="tel"], 
            .row-fluid input[type="color"], 
            .row-fluid .uneditable-input {
                height: 16px;
            }
        </style>
    </head>
    <body>
<div class="container">
    <div class="row-fluid">
        <div class="span3">
                <a href="/eMarketing/"><?php echo Phalcon\Tag::image(array('src' => '/images/gorilla.jpg')); ?><a>
            <div class="row-fluid">
                <ul class="nav nav-list text-center">
                    <li>
                        <a href="/eMarketing/dbases"><label>Contactos</label></a>
                    </li>
                    <li>
                        <a href="#"><label>Campañas</label></a>
                    </li>
                    <li>
                        <a href="#"><label>Autorespuestas</label></a>
                    </li>
                    <li>
                        <a href="#"><label>Estadisticas</label></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="span9">
            <?php echo $this->getContent(); ?>
        </div>
    </div>
</div>
    

    </body>
</html>