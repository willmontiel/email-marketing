<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('css/style.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
        {{ stylesheet_link ('css/prstyles.css') }}
		{{ stylesheet_link ('css/style.css') }}
		{{ stylesheet_link ('css/select2.css') }}
		{{ stylesheet_link ('css/emarketingstyle.css') }}
		
		{% block header_javascript %}
        {{ javascript_include('js/libs/jquery-1.9.1.js') }}
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
        {{ javascript_include('js/bootstrap-modal.js') }}
        {{ javascript_include('js/bootstrap-modalmanager.js') }}
		{{ javascript_include('js/select2.js') }}
		{% endblock %}
		
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Proyect">
        <meta name="author" content="Ivan">

        <style>
			select {
				width: 88%;
			}
        </style>
    </head>
    <body>
		<div class="container-fluid">
			<!-----Inicio de la Toolbar Flat ui  ------->
		<div class="row-fluid">
			<div class="span12">
				<div class="navbar navbar-inverse">
					<div class="navbar-inner">
						<div class="container">
							<div class="nav-collapse collapse" id="nav-collapse-01">
								<ul class="nav">
									<li>
										<a href="/emarketing/">
											Mail Station<span class="navbar-unread">1</span>
										</a>
									</li>
								</ul>
								<ul class="span8"></ul>
								<ul class="nav">
									<li>
										<a href="#fakelink">
											<span class="fui-user"></span> {{ userObject.firstName }} {{ userObject.lastName }} ({{ userObject.username }})
										</a>
										<ul>
											<li><a href="#fakelink"><span class="fui-eye"></span> Ver perfil</a></li>
											<li><a href="#fakelink"><span class="fui-new"></span> Cambio de contraseña</a></li>
											<li><a href="session/logout"><span class="fui-cross"></span></span> Cerrar Sesión</a></li>
										</ul>
									</li>
								</ul>	
							</div>	
						</div>
					</div>
				</div>
			</div>
		 </div>
	<!----- fin de la toolbar flat ui---->
			
				<div class="row-fluid">
					<div class="span2">
							<a href="/emarketing/">{{ image('src': '/images/email.png') }}<a>
						<div class="row-fluid">
							<ul class="nav nav-list text-left">
								<li>
									<a href="/emarketing/contactlist#/lists"><label><h4><span class="fui-user"></span> Contactos</h4></label> </a>
								</li>
								<li>
									<a href="#"><label><h4><span class="fui-list"></span> Campañas</h4></label></a>
								</li>
								<li>
									<a href="#"><label><h4><span class="fui-mail"></span> Autorespuestas</h4></label></a>
								</li>
								<li>
									<a href="#"><label><h4><span class="fui-check"></span> Estadisticas</h4></label></a>
								</li>
							</ul>

						</div>
					</div>
					<div class="span10">
						{{ content() }}
						<!-- Inicio de contenido -->
						{% block content %}
							<!-- Aqui va el contenido -->
						{% endblock %}
						<!-- Fin de contenido -->
					</div>		
				</div>
			</div>	
    </body>
</html>
