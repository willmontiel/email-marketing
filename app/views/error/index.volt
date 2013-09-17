{% extends "templates/signin.volt" %}

{% block content %}
	<div class="navbar navbar-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="{{url('')}}">Mail Station</a>
				<ul class="nav pull-right">
					<li class="toggle-primary-sidebar hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-primary"><button type="button" class="btn btn-navbar"><i class="icon-th-list"></i></button></li>
					<li class="hidden-desktop" data-toggle="collapse" data-target=".nav-collapse-top"><button type="button" class="btn btn-navbar"><i class="icon-align-justify"></i></button></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="error-box">
					<div class="message-small">Ha accesado a una pagina restringida</div>
					<div class="message-big">404</div>
					<div class="message-small">Usted no tiene permiso de estar aqui</div>
					<br />
					<a class="btn btn-blue" href="{{url('')}}">
						<i class="icon-arrow-left"></i> Volver a la página principal
					</a>
				</div>
			</div>
		</div>
	</div>
{% endblock %}