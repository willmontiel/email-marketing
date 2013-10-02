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
		<div class="sidebar-background">
			<div class="primary-sidebar-background"></div>
		</div>
		<!-- /sidebar -->
		<div class="primary-sidebar">
			<!-- Main nav -->
			<ul id="currentPage" class="nav nav-collapse collapse nav-collapse-primary">
				<li class="" id="1">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-dashboard icon-2x"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li class="" id="2">
					<span class="glow"></span>
					<a href="{{ url('contactlist#/lists"') }}">
						<i class="icon-user icon-2x"></i>
						<span>Contactos</span>
					</a>
				</li>
				<li class="" id="3">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-envelope icon-2x"></i>
						<span>Campañas</span>
					</a>
				</li>
				<li class="" id="4">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-edit icon-2x"></i>
						<span>Autorespuestas</span>
					</a>
				</li>
				<li class="" id="5">
					<span class="glow"></span>
					<a href="{{ url('contactlist#/lists"') }}">
						<i class="icon-bar-chart icon-2x"></i>
						<span>Estadísticas</span>
					</a>
				</li>
			</ul>
		</div>
		<!-- content -->
		<div class="main-content">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="area-top clearfix">
						<div class="pull-left header">
							<h3 class="title">
								{% block sectiontitle %}<i class="icon-dashboard"></i>{% endblock %}
							</h3>
							<h5>{% block sectionsubtitle %}{% endblock %}</h5>
						</div>	
						{% block sectionContactLimit %}{% endblock %}
					</div>
				</div>
			</div>
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
		<script type="text/javascript">
			var current = "{{ router.getRewriteUri()}}";

			if (current == "/") {
				document.getElementById('1').className = "active";
			}
			else if (current.indexOf("/contactlist") !== -1) {
				document.getElementById('2').className = "active";
			}
		</script>
		<!-- /content -->
    </body>
</html>
