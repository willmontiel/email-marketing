{# Nuevo template usando CORE TEMPLATE #} 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
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
		
		{% block header_javascript %}
		<script type="text/javascript">
			var MyBaseURL = '{{url('')}}';
		</script>
		{{ javascript_include('javascripts/application.js') }}
		{% endblock %}

        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<!-- nav bar -->
		<div class="navbar navbar-inverse">
			<div class="navbar-inner">
				<div class="container-fluid">
					<div class="nav-collapse collapse" id="nav-collapse-01">
						<a class="brand" href="{{url('')}}">Mail Station</a>
						<ul class="nav pull-right">
							<li class="toggle-primary-sidebar hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-primary">
								<button type="button" class="btn btn-navbar"><i class="icon-th-list"></i></button>
							</li>
							<li class="hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-top"><button type="button" class="btn btn-navbar"><i class="icon-align-justify"></i></button></li>
						</ul>

						
						<div class="nav-collapse nav-collapse-top collapse">
							<ul class="nav full pull-right">
								<li class="dropdown user-avatar">
									<!-- the dropdown has a custom user-avatar class, this is the small avatar with the badge -->
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
										<span>
											<img class="menu-avatar" src="{{ url('images/avatars/avatar1.jpg')}}" /> <span> {{ userObject.username }} <i class="icon-caret-down"></i></span>
											{# Este es un indicador sobre numero de mensajes #}
											<span class="badge badge-dark-red">0</span>
											{# fin del indicador #}
										</span>
									</a>
									<!-- Menu desplegable del usuario -->
									<ul class="dropdown-menu">
										<!-- imagen del usuario -->
										<li class="with-image">
											<div class="avatar">
												<img src="{{ url('images/avatars/avatar1.jpg')}}" />
											</div>
											{# Nombre del usuario #}
											<span>{{ userObject.firstName }} {{ userObject.lastName }}</span>
										</li>
										<li class="divider"></li>

										<li><a href="#"><i class="icon-cog"></i> <span>Configuración</span></a></li>
										<li>
											<a href="#"><i class="icon-envelope"></i><span>Mensajes</span>
												{# Mensajes para el usuario #}
												<span class="label label-dark-red pull-right">0</span>
											</a>
										</li>
										<li><a href="{{ url('session/logout') }}"><i class="icon-off"></i> <span>Logout</span></a></li>
									</ul>
								</li>
							</ul>
							<ul class="nav pull-right">
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Administrar <b class="caret"></b></a>
									<ul class="dropdown-menu">
										<li><a href="{{ url('account') }}">Cuentas</a></li>
										<li><a href="{{ url('user') }}">Usuarios</a></li>
										<li><a href="{{ url('sendingprocess') }}">Procesos de envío</a></li>
										<li><a href="{{ url('scheduledmail/manage') }}">Programación de correos</a></li>
										<li><a href="{{ url('flashmessage/index') }}">Mensajes administrativos</a></li>
										<li><a href="{{ url('socialmedia/index') }}">Cuentas de Redes Sociales</a></li>
									</ul>
								</li>
							</ul>
						</div>
						
					</div>	
				</div>
			</div>
		</div>
		<!-- /nav bar -->
			

		<!-- sidebar -->
		<div class="sidebar-background sidebar-background-editor">
			<div class="primary-sidebar-background primary-sidebar-background-editor"></div>
		</div>
		<!-- /sidebar -->
		<div class="primary-sidebar">
			<!-- Main nav -->
			{{ partial("partials/editormenu_partial") }}
		</div>
		<!-- content -->
		<div class="main-content main-content-editor">
			<div class="container-fluid padded">
				<div class="row-fluid">
					<!-- Inicio de contenido -->
					{% block content %}
						<!-- Aqui va el contenido -->
					{% endblock %}
					<!-- Fin de contenido -->
				</div>
				
			</div>		
		</div>
		<!-- /content -->
    </body>
</html>
