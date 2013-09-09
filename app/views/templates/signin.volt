{# Nuevo template usando CORE TEMPLATE #} 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Proyect">
        <meta name="author" content="Will">
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">

		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

        {{ get_title() }}

        {{ stylesheet_link('stylesheets/application.css') }}
        {{ stylesheet_link('css/prstyles.css') }}
		
		<!--[if lt IE 9]>
		{{ javascript_include('javascripts/vendor/html5shiv.js') }}
		{{ javascript_include('javascripts/vendor/excanvas.js') }}
		<![endif]-->

        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<div class="container-fluid padded">
			<div class="row-fluid">
				<!-- Inicio de contenido -->
				{% block content %}
					<!-- Aqui va el contenido -->
				{% endblock %}
				<!-- Fin de contenido -->
			</div>

		</div>	
		<!-- /content -->
    </body>
</html>
