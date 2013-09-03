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
						<a class="brand" href="/emarketing/">Mail Station</a>
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
										<li><a href="#">Usuarios</a></li>
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
			<ul class="nav nav-collapse collapse nav-collapse-primary">
				<li class="active">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-dashboard icon-2x"></i>
						<span>Dashboard</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="{{ url('contactlist#/lists"') }}">
						<i class="icon-user icon-2x"></i>
						<span>Contactos</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-envelope icon-2x"></i>
						<span>Campañas</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-edit icon-2x"></i>
						<span>Autorespuestas</span>
					</a>
				</li>
				<li class="">
					<span class="glow"></span>
					<a href="{{ url('') }}">
						<i class="icon-bar-chart icon-2x"></i>
						<span>Estadísticas</span>
					</a>
				</li>
				<li class="dark-nav ">

					<span class="glow"></span>
					<a class="accordion-toggle collapsed " data-toggle="collapse" href="#zwz2Ux5SfP">
						<i class="icon-beaker icon-2x"></i>
						<span>UI Lab<i class="icon-caret-down"></i></span>

					</a>

					<ul id="zwz2Ux5SfP" class="collapse ">

						  <li class="">
							<a href="../ui_lab/buttons.html">
								<i class="icon-hand-up"></i> Buttons
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/general.html">
								<i class="icon-beaker"></i> General elements
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/icons.html">
								<i class="icon-info-sign"></i> Icons
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/grid.html">
								<i class="icon-th-large"></i> Grid
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/tables.html">
								<i class="icon-table"></i> Tables
							</a>
						  </li>

						  <li class="">
							<a href="../ui_lab/widgets.html">
								<i class="icon-plus-sign-alt"></i> Widgets
							</a>
						  </li>

					</ul>

				</li>


			</ul>

			<div class="hidden-tablet hidden-phone">
			  <div class="text-center" style="margin-top: 60px">
				<div class="easy-pie-chart-percent" style="display: inline-block" data-percent="89"><span>89%</span></div>
				<div style="padding-top: 20px"><b>CPU Usage</b></div>
			  </div>

			  <hr class="divider" style="margin-top: 60px">

			  <div class="sparkline-box side">

				<div class="sparkline-row">
				  <h4 class="gray"><span>Orders</span> 847</h4>
				  <div class="sparkline big" data-color="gray"><!--28,11,24,24,8,20,26,22,16,6,12,15--></div>
				</div>

				<hr class="divider">
				<div class="sparkline-row">
				  <h4 class="dark-green"><span>Income</span> $43.330</h4>
				  <div class="sparkline big" data-color="darkGreen"><!--16,20,6,19,25,22,9,13,7,10,15,4--></div>
				</div>

				<hr class="divider">
				<div class="sparkline-row">
				  <h4 class="blue"><span>Reviews</span> 223</h4>
				  <div class="sparkline big" data-color="blue"><!--20,18,21,17,5,7,29,9,8,14,23,8--></div>
				</div>

				<hr class="divider">
			  </div>
			</div>

		</div>

		<!-- content -->
		<div class="main-content">
			<div class="container-fluid">
				<div class="row-fluid">

					<div class="area-top clearfix">
						<div class="pull-left header">
							<h3 class="title">
								{% block sectiontitle %}<i class="icon-dashboard"></i>Dashboard{% endblock %}
						</h3>
						  </div>

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
		<!-- /content -->
    </body>
</html>
