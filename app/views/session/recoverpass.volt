{% extends "templates/signin.volt" %}
{% block content %}
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="text-center">
				<font face="ArdleysHand" size="20">
					Mail Station
				</font>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4 offset4">
			{{ flashSession.output() }}
			<div class="box">
				<div class="box-header padded">
					Recuperar datos de sesión
				</div>
				<div class="box-content padded">
					{{ form('session/recoverpass', 'class': "separate-sections" ) }}
						<label style="text-align: center;">Dirección de correo electrónico:</label>
						<div class="input-prepend">
							<span class="add-on" href="#">
								<i class="icon-home"></i>
							</span>
							{{ text_field("email", 'type': "text", 'class': "span5", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Dirección de correo eletrónico" ) }}
						</div>
						<div>
							{{ submit_button("Enviar", 'class' : "btn btn-blue btn-block") }}
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}