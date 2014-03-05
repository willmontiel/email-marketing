{% extends "templates/signin.volt" %}
{% block content %}
	{{ stylesheet_link('css/prstyles.css') }}
	<div class="navbar navbar-top navbar-inverse">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="{{url('')}}">Mail Station</a>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row-fluid">
			<div class="span8 offset2">
				<div class="padded">
					<div class="login box" style="margin-top: 80px;">
						<div class="box-header">
							<span class="title">Des-suscribirse</span>
						</div>
						<div class="box-content padded">
							<ul class="chat-box timeline">
								<li class="arrow-box-left gray">
									<div class="avatar blue"><i class="icon-circle-blank icon-2x"></i></div>
									<div class="info">
										<span class="name" style="font-size: 12px;">
										  El contacto indicado ya no existe en la base de datos
										</span>
										<span class="time" style="font-size: 12px;"><i class="icon-briefcase"></i> Compañia <strong>Arsenal FC</strong></span>
									</div>
								</li>
							 </ul>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
{% endblock %}