{% extends "templates/signin.volt" %}
{% block content %}
<div class="container-fluid">
	<div class="row">
		<div class="text-center">
			<h2>Sigma Email<br/><small>Bienvenido a Email Marketing de Sigma Movil</small></h2>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<div class="panel-title">Inicio de sesión</div>
				</div>
				<div class="panel-body">
					{{ flashSession.output() }}
					<form class="form-horizontal" role="form" id="sessionlogin" action="{{ url('session/login') }}" method="post">
						<input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}"/>
						<div class="form-group">
							<label for="username" class="col-sm-3 control-label">Usuario</label>
							<div class="col-sm-9 ">
								<input type="text" class="form-control" id="username" name="username" placeholder="Usuario" required="required" autofocus="autofocus" />
							</div>
						</div>
						<div class="form-group">
							<label for="pass" class="col-sm-3 control-label">Contraseña</label>
							<div class="col-sm-9">
								<input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña">
							</div>
						</div>						
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 ">
								{{ submit_button("Ingresar", 'class' : "btn btn-primary btn-block") }}
							</div>
						</div>					
						<a href="{{url('session/recoverpass')}}" style="text-decoration: underline; text-align: center;">¿Ha olvidado su contraseña?</a>
					</form>
				</div>
			</div>
		</div>	
	</div>
</div>
{% endblock %}