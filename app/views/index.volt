<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('bootstrap/css/bootstrap-responsive.css') }}
        {{ stylesheet_link('css/style.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ javascript_include('js/jquery-1.8.3.min.js') }}
        {{ javascript_include('bootstrap/js/bootstrap.js') }}
        {{ javascript_include('js/jquery-ui-1.10.3.custom.min.js') }}
        {{ javascript_include('js/jquery.ui.touch-punch.min.js') }}
        {{ javascript_include('js/bootstrap.min.js') }}
        {{ javascript_include('js/bootstrap-select.js') }}
        {{ javascript_include('js/bootstrap-switch.js') }}
        {{ javascript_include('js/flatui-checkbox.js') }}
        {{ javascript_include('js/flatui-radio.js') }}
        {{ javascript_include('js/jquery.tagsinput.js') }}
        {{ javascript_include('js/jquery.placeholder.js') }}
        {{ javascript_include('js/jquery.stacktable.js') }}
        {{ javascript_include('js/application.js') }}
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Proyect">
        <meta name="author" content="Ivan">

        <style>
            .container { min-width: 960px; }
        </style>
    </head>
    <body>
<div class="container">
    <div class="row-fluid">
        <div class="span3">
                {{ image('src': '/images/gorilla.jpg') }}
            <div class="row-fluid">
                <ul class="nav nav-list text-center">
                    <li>
                        <a href="#"><label>Contactos</label></a>
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
            {{ content() }}
        </div>
    </div>
</div>
    

    </body>
</html>