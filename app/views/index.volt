<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('bootstrap/css/bootstrap-responsive.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
        {{ stylesheet_link ('css/prstyles.css') }}
        {{ javascript_include('js/jquery-1.8.3.min.js') }}
        {{ javascript_include('bootstrap/js/bootstrap.js') }}
        {{ javascript_include('js/bootstrap.min.js') }}
        {{ javascript_include('js/bootstrap-select.js') }}
        {{ javascript_include('js/bootstrap-switch.js') }}
        {{ javascript_include('js/flatui-checkbox.js') }}
        {{ javascript_include('js/flatui-radio.js') }}
        {{ javascript_include('js/bootstrap-modal.js') }}
        {{ javascript_include('js/bootstrap-modalmanager.js') }}
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
                <a href="/emarketing/">{{ image('src': '/images/gorilla.jpg') }}<a>
            <div class="row-fluid">
                <ul class="nav nav-list text-center">
                    <li>
                        <a href="/emarketing/dbases"><label>Contactos</label></a>
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