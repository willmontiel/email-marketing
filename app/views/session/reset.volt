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
			<div class="panel panel-warning">
				<div class="panel-heading">
					<div class="panel-title">Cambiar contraseña</div>
				</div>
				<div class="panel-body">
					{{ flashSession.output() }}
					<form class="form-horizontal" role="form" id="sessionlogin" action="{{ url('session/setnewpass') }}" method="post">
						<input type="hidden" name="uniq" value="{{uniq}}"/>
						<div class="form-group">
							<label for="pass" class="col-sm-3 control-label">Contraseña:</label>
							<div class="col-sm-9 ">
								<input type="password" class="form-control" id="pass" name="pass" placeholder="Contraseña" required="required" autofocus="autofocus" />
							</div>
						</div>
						<div class="form-group">
							<label for="pass2" class="col-sm-3 control-label">Contraseña:</label>
							<div class="col-sm-9 ">
								<input type="password" class="form-control" id="pass2" name="pass2" placeholder="Repita la contraseña" required="required" autofocus="autofocus" />
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9 ">
								{{ submit_button("Cambiar contraseña", 'class' : "btn btn-warning btn-block") }}
							</div>
						</div>					
					</form>
				</div>
			</div>
		</div>	
	</div>
</div>
{% endblock %}