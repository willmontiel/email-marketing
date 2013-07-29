{% extends "templates/signin.volt" %}
{% block content %}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="text-center">
				<h1>Mail Gorilla</h1>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
	<div class="row-fluid">
		<div class="span12" >
			<div class="text-center">
				<fieldset>
					<legend>
						<h3>Iniciar Sesión</h3>
					</legend>
				</fieldset>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
		
	<div class="row-fluid">
		<div class="span4 offset4">
			<div class="text-center">
			
				{{ content() }}

				{{ form('session/login', 'id': 'sessionlogin') }}

					<p>   
						<strong>Usuario:</strong>
						{{ text_field("username", 'type': "text", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Nombre de Usuario o E-mail") }}
					</p>

					<p> 
						<strong>Contraseña:</strong>
						{{ password_field('pass', 'type': "email", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Contraseña") }}
					</p>

				   <p>
						{{ submit_button("Ingresar", 'class' : "btn btn-success") }}
						{{ check_field('online') }} Recuerdame
					</p>
				</form>
			
			</div>
		</div>
	</div>
</div>
{% endblock %}