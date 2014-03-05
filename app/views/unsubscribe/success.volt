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
					<div class="box">
						<div class="box-header"></div>
						<div class="box-content padded">
							<ul class="chat-box timeline">
								<li class="arrow-box-left gray">
									<div class="avatar blue"><i class="icon-minus-sign icon-2x"></i></div>
									<div class="info">
										<span class="name" style="font-size: 14px;">
											El contacto ha sido des-suscrito exitosamente
										</span>
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