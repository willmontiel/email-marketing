{# Nuevo template usando Bootstrap 3 #}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,800">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

        {{ get_title() }}

        {{ stylesheet_link('b3/css/bootstrap.css') }}
        {{ stylesheet_link('b3/css/font-awesome.css') }}
        {{ stylesheet_link('css/prstyles.css') }}
        {{ stylesheet_link('b3/css/sm-email-theme.css') }}
        {{ stylesheet_link('b3/vendors/css/bootstrap-editable.css') }}
        {{ stylesheet_link('b3/vendors/css/jquery.gritter.css') }}

		<!--[if lt IE 9]>
		{{ javascript_include('javascripts/vendor/html5shiv.js') }}
		{{ javascript_include('javascripts/vendor/excanvas.js') }}
		<![endif]-->
		
		{% block header_javascript %}
		<script type="text/javascript">
			var MyBaseURL = '{{url('')}}';
		</script>
		{{ javascript_include('b3/js/jquery-1.9.1.js') }}
		{{ javascript_include('b3/js/bootstrap.js') }}
		{{ javascript_include('b3/vendors/js/jquery.sparkline.js') }}
		{{ javascript_include('b3/vendors/js/spark_auto.js') }}
		{{ javascript_include('b3/vendors/js/bootstrap-editable.js') }}
		{{ javascript_include('b3/vendors/js/jquery.gritter.js') }}
		{% endblock %}

        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<div class="container-fluid">
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
