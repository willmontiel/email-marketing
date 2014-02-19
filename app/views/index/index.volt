{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-envelope-alt icon-2x"></i>Bienvenido a Mail Station{% endblock %}
{%block sectionsubtitle %}Su sistema de marketing digital{% endblock %}
{% block content %}
{{flashSession.output()}}
	<div class="row-fluid">
		<div class="box">
			<div class="box-content">
				<div class="box-section news with-icons">
					<div class="avatar green"><i class="icon-lightbulb icon-2x"></i></div>
					<div class="news-time">
						<span>{{ date('d',time())}}</span> {{ date('M',time())}}
					</div>
						
					<div class="news-content">
						<div class="news-title">
							{#Bienvenido(a) <a href="#"> {{userObject.firstName}} {{userObject.lastName}}</a>#}
						</div>
						<div class="news-text">
							Esta es la página principal de la aplicación aqui podrá encontrar, información relevante sobre
							la cuenta, contactos, envíos y demás actividades realizadas en los ultimos días, además de tener
							accesos directos a las funcionalidades más importantes
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8">
			<div class="box">
				<div class="box-header">
					<span class="title">Información ultima campaña realizada</span>
				</div>
				<div class="box-content padded">
					<div class="row-fluid separate-sections">
						<div class="span4">
							<div class="spark-pie">
								<canvas></canvas>
							</div>
						</div>
						<div class="span4">
							<div class="spark-mouse">
								<canvas></canvas>
							</div>
						</div>
						<div class="span4">
							<div class="spark-composite">
								<canvas></canvas>
							</div>
						</div>
					</div>
				 </div>
			</div>
		</div>
		<div class="span4">
			{% if confAccount.accountingMode == 'Contacto'%}
			<div class="box">
				<div class="box-header">
					<div class="title">
						Información de contactos
					</div>
				</div>
				<div class="box-content padded">
					<div class="dashboard-stats">
						<ul class="inline">
							<li class="glyph"><i class="icon-user icon-2x"></i></li>
							<li class="count"><span class="blue-label">{{currentActiveContacts}}</span></li>
						</ul>
						<div class="progress progress-striped progress-blue active"><div class="bar tip" title="" data-percent="{{(currentActiveContacts*100)/confAccount.contactLimit}}" data-original-title="{{(currentActiveContacts*100)/confAccount.contactLimit}}% de la capacidad de contactos"></div></div>
						<span class="stats-label">Contactos activos por cuenta</span>
					</div>	
					<br />
					<div class="row-fluid">
						<div class="span6">
							<div class="dashboard-stats small">
								<ul class="inline">
									<li class="glyph"><i class="icon-exclamation-sign"></i></li>
									<li class="count">{{confAccount.contactLimit}}</li>
								</ul>
								<span class="stats-label">Capacidad máxima</span>
							</div>	
						</div>
						<div class="span6">
							<div class="dashboard-stats small">
								<ul class="inline">
									<li class="glyph"><i class="icon-ok-sign"></i></li>
									<li class="count">{{confAccount.contactLimit-currentActiveContacts}}</li>
								</ul>
								<span class="stats-label">Capacidad disponible</span>
							</div>	
						</div>
					</div>

				</div>
			</div>
			{% else %}
			<div class="box">
				<div class="box-header">
					<div class="title">
						Información sobre mensajes
					</div>
				</div>
				<div class="box-content padded">
					<div class="dashboard-stats">
						<ul class="inline">
							<li class="glyph"><i class="icon-user icon-2x"></i></li>
							<li class="count"><span class="blue-label">{{currentActiveContacts}}</span></li>
						</ul>
						<span class="stats-label">Contactos activos por cuenta</span>
					</div>	
				</div>
			</div>
			{% endif %}
		</div>
	</div>
	<hr class="divider">
	<div class="row-fluid">
		<div class="span12">
			<div class="action-nav-normal">
				<div class="span3 action-nav-button">
					<a href="#" title="Mis envíos">
						<i class="icon-envelope-alt"></i>
						<span>Enviar correo</span>
					</a>
					<span class="triangle-button blue"><i class="icon-external-link"></i></span>
				</div>
				<div class="span3 action-nav-button">
					<a href="{{url('mail/setup')}}" title="Mis campañas">
						<i class="icon-envelope"></i>
						<span>Nuevo correo</span>
					</a>
					<span class="triangle-button green"><i class="icon-plus"></i></span>
				</div>
				<div class="span3 action-nav-button">
					<a href="{{url('contactlist#/lists/new')}}" title="Mis listas">
						<i class="icon-reorder"></i>
						<span>Nueva lista</span>
					</a>
					<span class="triangle-button green"><i class="icon-plus"></i></span>
				</div>
				<div class="span3 action-nav-button">
					<a href="{{url('dbase')}}" title="Configuración avanzada (Bases de datos)">
						<i class="icon-book"></i>
						<span>Bases de datos</span>
					</a>
					<span class="triangle-button red"><i class="icon-cogs"></i></span>
				</div>
			</div>
		</div>
	</div>
{% endblock %}