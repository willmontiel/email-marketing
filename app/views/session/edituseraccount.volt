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
					Cambiar contraseña
				</div>
				<div class="box-content padded">
					<form action="{{url('session/edituseraccount')}}/{{uniq}}" class="separate-sections">
						<label style="text-align: center;">Contraseña:</label>
						<div class="input-prepend">
							<span class="add-on" href="#">
								<i class="icon-key"></i>
							</span>
							{{ text_field("pass", 'type': "text", 'class': "span5", 'required': "required", 'autofocus': "autofocus", 'placeholder': "Contraseña" ) }}
						</div>
						<label style="text-align: center;">Repita la contraseña:</label>
						<div class="input-prepend">
							<span class="add-on" href="#">
								<i class="icon-key"></i>
							</span>
							{{ text_field("pass2", 'type': "text", 'class': "span5", 'required': "required", 'placeholder': "Repita la contraseña" ) }}
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