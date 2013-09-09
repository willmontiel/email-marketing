{% extends "templates/index_new.volt" %}
{% block sectiontitle %}Bienvenido a Mail Station{% endblock %}
{%block sectionsubtitle %}Su sistema de marketing digital{% endblock %}
{% block content %}
<h4>Aqui hay un dashboard</h4>
		<div class="box span5 offset6">
			<div class="box-header">
				<div class="title">
					Información de contactos
				</div>
			</div>
			<div class="box-content padded">
				<div class="dashboard-stats">
					<ul class="inline">
						<li class="glyph"><i class="icon-user icon-2x primary-label"></i></li>
						<li class="count"><span class="primary-label">1200</span></li>
					</ul>
					<div class="progress progress-striped progress-green active"><div class="bar tip" title="" data-percent="80" data-original-title="80%"></div></div>
					<div class="stats-label">Contactos activos por cuenta</div>
				</div>	
				<br />
				<div class="row-fluid">
					<div class="span6">
						<div class="dashboard-stats small">
							<ul class="inline">
								<li class="glyph"><i class="icon-user"></i></li>
								<li class="count">2000</li>
							</ul>
							<span>Capacidad máxima</span>
						</div>	
					</div>
					<div class="span6">
						<div class="dashboard-stats small">
							<ul class="inline">
								<li class="glyph"><i class="icon-user"></i></li>
								<li class="count">800</li>
							</ul>
							<span>Capacidad disponible</span>
						</div>	
					</div>
				</div>
				
			</div>
		</div>
{% endblock %}