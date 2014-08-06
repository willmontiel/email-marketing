{% extends "templates/signin.volt" %}
{% block content %}
<div class="container-fluid">
	<br />
	<br />
	<br />
	<div class="row">
		<div class="text-center">
			{{theme.logo}}
		</div>
	</div>
	<br />
	<br />
	<div class="row">
		<div class="col-lg-4 col-md-6 col-sm-8 col-xs-12 col-sm-offset-2 col-md-offset-3 col-lg-offset-4">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="panel-title">Recuperar contraseña</div>
				</div>
				<div class="panel-body">
					{{ flashSession.output() }}
					<form class="form-horizontal" role="form" id="sessionlogin" action="{{ url('session/recoverpass') }}" method="post">
						<div class="form-group">
							<label for="email" class="col-sm-3 control-label">Email:</label>
							<div class="col-sm-9 ">
								<input type="email" class="form-control" id="username" name="email" placeholder="Dirección de correo eletrónico" required="required" autofocus="autofocus" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 ">
								{{ submit_button("Recuperar", 'class' : "btn btn-warning btn-block") }}
							</div>
						</div>					
					</form>
				</div>
			</div>
		</div>	
	</div>
</div>

{% endblock %}