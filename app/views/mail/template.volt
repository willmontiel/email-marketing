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
		<br />
	</div>
	<hr class="divider">
	<div class="row-fluid">
		<div class="span10">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Escoja una plantilla
					</div>
				</div>
				<div class="box-content">
					<div class="padded">
						<div class="tab-content">
							{% for category, template in arrayTemplate %}
								<div class="tab-pane {% if loop.first %}active{% else %}fade{% endif %}" id="{{category}}">
									<ul class="thumbnails padded">
										{% for t in template %}
										<li class="span3">
											<h5 style="text-align: center;">{{t['name']}}</h5>
											<a href="{{url('mail/editor')}}/{{mail.idMail}}/{{t['id']}}" class="thumbnail">
												<img src="{{url('template/thumbnail')}}/{{t['id']}}" alt="{{t['name']}}" title="{{t['name']}}">
											</a>
											<div class="btn-toolbar" style="margin-left: 40%; margin-top: 10%;">
												<div class="btn-group ">
													<button class="btn btn-default" title="Previsualizar"><i class="icon-eye-open"></i></button>
												</div>
											</div>
										</li>
										{% endfor %}
									</ul>
								</div>
							{% endfor %}
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="box">
				<div class="padded">
					<ul class="nav nav-pills nav-stacked nav-template">
						{% for category, template in arrayTemplate %}
							<li class="{% if loop.first %}active{% endif %}"><a href="#{{category}}" data-toggle="tab">{{category}}</a></li> 
						{% endfor %}
					</ul>
				</div>
			</div>
		</div>
	</div>
{% endblock %}