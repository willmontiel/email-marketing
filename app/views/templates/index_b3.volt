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
		<div id="sidebar-background-object" class="col-sm-3 col-md-2 hidden-xs"></div>

		<!-- nav bar -->
		<nav class="navbar navbar-default">
			<div class="container-fluid">
				<div class="navbar-header">
					<!-- Brand and toggle get grouped for better mobile display -->
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="{{url('')}}">Email Sigma</a>
				</div>

				<!-- <p class="navbar-text">{% block sectiontitle %}Titulo de pagina{% endblock %}</p> -->
				<div class="collapse navbar-collapse" id="nav-collapse-01">
					<ul id="top-nav" class="nav navbar-nav navbar-right">
						<li><a href="">Mi Cuenta</a></li>
						<li><a href="">Ayuda</a></li>
						<li><a href="">Cerrar sesión</a></li>
					</ul>					
				</div>	
			</div>
		</nav>
		<!-- /nav bar -->
<!-- ****** ELEMENTOS POR UBICAR ********* ->

<!--												{# Nombre del usuario #}
										<span>{{ userObject.firstName }} {{ userObject.lastName }}</span>

			
										<img class="menu-avatar" src="{{ url('images/avatars/avatar1.jpg')}}" /> <span> {{ userObject.username }} <i class="icon-caret-down"></i></span>
										{# Este es un indicador sobre numero de mensajes #}
										<span class="badge badge-dark-red">
											{% set messages = flashMessage.getMessages() %}
											{% if messages !== false%}
												{{messages|length}}
											{% else %}
												0
											{% endif %}
										</span>
										{# fin del indicador #}


						<ul class="nav pull-right">
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administrar <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="{{ url('account') }}">Cuentas</a></li>
									<li><a href="{{ url('user') }}">Usuarios</a></li>
									<li><a href="{{ url('process') }}">Procesos</a></li>
									<li><a href="{{ url('scheduledmail/manage') }}">Programación de correos</a></li>
									<li><a href="{{ url('flashmessage/index') }}">Mensajes administrativos</a></li>
									<li><a href="{{ url('socialmedia/index') }}">Cuentas de Redes Sociales</a></li>
								</ul>
							</li>
						</ul>
-->
<!-- ****** FIN ELEMENTOS POR UBICAR ********* -->
		
		<!-- Contenedor principal -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12 col-sm-3 col-md-2 sidebar" style="height: 100%;">
					<div>
						<!-- Main nav -->
						{{ partial("partials/menu_partial_b3") }}
					</div>
				</div>
				<div class="col-sx-12 col-sm-9 col-md-10">
					{# Zona de mensajes #}
					{% if messages !== false%}
						<div class="row">
							<div class="col-sm-12">
								{% for msg in messages%}
									<div class="alert alert-{{msg.type}}">
										<button type="button" class="close" data-dismiss="alert">×</button>
										<h4>Atención!</h4>
										{{msg.message}}
									</div>
								{% endfor %}
							</div>
						</div>
					{% endif %}
					{# Fin de zona de mensajes #}

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
