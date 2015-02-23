{# Nuevo template usando Bootstrap 3 #}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=1">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
		<link rel="shortcut icon" type="image/x-icon" href="{{url('')}}images/favicon48x48.ico">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

        {{ get_title() }}

        {{ stylesheet_link('vendors/bootstrap_v3/css/bootstrap.css') }}
        {{ stylesheet_link('vendors/bootstrap_v3/css/font-awesome.css') }}
        

        {# Para cambiar el tema modificar la ruta en el siguiente enlace#}
        {{ stylesheet_link('themes/' ~ theme.name ~ '/css/styles.css') }}
        {##}

		<!--[if lt IE 9]>
		{{ javascript_include('javascripts/vendor/html5shiv.js') }}
		{{ javascript_include('javascripts/vendor/excanvas.js') }}
		<![endif]-->
		
		{% block header_javascript %}
		<script type="text/javascript">
			var MyBaseURL = '{{url('')}}';
		</script>
		{{ javascript_include('vendors/bootstrap_v3/js/jquery-1.9.1.js') }}
		{{ javascript_include('vendors/bootstrap_v3/js/bootstrap.js') }}
		{% endblock %}
		{{ stylesheet_link('css/prstyles.css') }}
        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<!-- nav bar -->
		<nav class="navbar navbar-default" role="navigation">
			<div class="container-fluid">
			  <div class="navbar-header">
				  <a class="navbar-brand" href="{{url('')}}">{{theme.logo}}</a>
			  </div>	
			</div>
		</nav>
		
		<!-- Contenedor principal -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-sx-12 col-sm-12 col-md-12 col-lg-12">
					<div class="container-fluid">
						<!-- Inicio de contenido -->
						{% block content %}
							<!-- Aqui va el contenido -->
						{% endblock %}
						<!-- Fin de contenido -->
					</div>
				</div>

			</div>
		</div>
    </body>
</html>
