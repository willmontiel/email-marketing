{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-envelope"></i>Correos{% endblock %}
{% block sectionsubtitle %}Envíe un correo a multiples contactos{% endblock %}
{% block content %}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar green">
						<i class="icon-lightbulb icon-2x"></i>
					</div>
					<div class="news-content">
						<div class="news-title">
							Selección de plantilla prediseñada
						</div>
						<div class="news-text">
							Aqui encontrará muchas plantillas con muchos estilos diferentes, que podrá elegir como
							base para el contenido de su correo.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			{{partial('partials/wizard_partial')}}
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<hr class="divider">
				<div class="accordion" id="accordion2">
					{% for category, template in arrayTemplate %}
						<div class="accordion-group">
							<div class="accordion-heading">
								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#{{category}}">
									{{category}}
								</a>
							</div>
							<div id="{{category}}" class="accordion-body collapse">
								<div class="accordion-inner">
									<ul class="thumbnails padded">
										{% for t in template %}
										<li class="span3">
											<h5 style="text-align: center;">{{t['name']}}</h5>
											<a href="{{url('mail/editor')}}/{{mail.idMail}}/{{t['id']}}" class="thumbnail">
												<img src="http://localhost/emarketing/templates/1/images/{{t['id']}}.JPG" alt="{{t['name']}}" title="{{t['name']}}">
											</a>
										</li>
										{% endfor %}
									</ul>
								</div>
							</div>
						</div>
					{% endfor %}
				</div>
			<hr class="divider">
		</div>
	</div>
{% endblock %}