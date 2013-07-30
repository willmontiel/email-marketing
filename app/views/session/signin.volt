{% extends "templates/signin.volt" %}
{% block content %}
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
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
		<div class="span12" ></div>
	</div>
	<div class="row-fluid">
		<div class="span12"></div>
	</div>
		
	<div class="row-fluid">
		<div class="span4 offset4">
			<div class="text-center">
			
				{{ content() }}
					
				<div class="login-form">
					{{ form('session/login', 'id': 'sessionlogin') }}

						<p>   
							<strong>Usuario:</strong>
							<div class="control-group">
								{{ text_field("username", 'type': "text", 'class': "login-form", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Nombre de Usuario" ) }}
								<label class="login-field-icon fui-user" for="username"></label>
							</div>
						</p>

						<p> 
							<strong>Contraseña:</strong>
							<div class="control-group">
								{{ password_field('pass', 'type': "email", 'class':"login-form",  'required': "required", 'autofocus': "autofocus", 'placeholder': "Contraseña") }}
								<label class="login-field-icon fui-lock" for="pass"></label>
							</div>
						</p>

						<p>
							{{ submit_button("Ingresar", 'class' : "btn btn-primary btn-large btn-block") }}
						</p>
						<p>
							<label class="checkbox" for="checkbox1">
								<span class="icons">
									<span class="first-icon fui-checkbox-unchecked"></span>
									<span class="second-icon fui-checkbox-checked"></span>
								</span>
								<input type="checkbox" value="" id="checkbox1" data-toggle="checkbox">
								Recuerdame
							</label>
						</p>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
{% endblock %}