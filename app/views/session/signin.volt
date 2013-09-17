{% extends "templates/signin.volt" %}
{% block content %}
<div class="container-fluid">
	<br /><br />
	<div class="row-fluid">
		<div class="span12">
			<div class="text-center">
				<font face="ArdleysHand" size="20">
					Mail Station
				</font>
			</div>
		</div>
	</div>
	<br />
	{{ flashSession.output() }}
	<div class="row-fluid">
		<div class="padded span4 offset4">
			<div class="login box">
				<div class="box-header">
					<span class="title">Iniciar sesión</span>
				</div>
				<div class="box-content padded">
					{{ form('session/login', 'id': 'sessionlogin', 'class': "separate-sections" ) }}
					<input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}"/>
						<label style="text-align: center;">Usuario:</label>
						<div class="input-prepend">
							<span class="add-on" href="#">
								<i class="icon-user"></i>
							</span>
							{{ text_field("username", 'type': "text", 'class': "span5", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Nombre de Usuario" ) }}
						</div>
						<label style="text-align: center;">Contraseña:</label>
						<div class="input-prepend">
							<span class="add-on" href="#">
								<i class="icon-key"></i>
							</span>
							{{ password_field('pass', 'type': "password", 'class':"span5",  'required': "required", 'placeholder': "Contraseña") }}
						</div>

						<div>
							{{ submit_button("Ingresar", 'class' : "btn btn-blue btn-block") }}
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
{% endblock %}