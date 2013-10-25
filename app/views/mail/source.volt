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
							Seleccionar como va a crear el contenido
						</div>
						<div class="news-text">
							Tenemos muchas opciones para crear contenido a los correos, podrá utilizar el editor
							avanzado que es una herramienta muy poderosa que hará mas facil crear contenido con estilo
							y bien estructurado en unos pocos minutos, y muchas opciones más.
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span8 offset2">
			<div id="breadcrumbs">
				<div class="breadcrumb-button">
					<a href="{{url('mail/setup')}}/{{idMail}}">
						<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</a>
				</div>
				<div class="breadcrumb-button blue">
					<span class="breadcrumb-label"><i class="icon-edit"></i> Editar/Crear contenido</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button">
					<span class="breadcrumb-label"><i class="icon-group"></i> Seleccionar destinatarios</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button">
					<span class="breadcrumb-label"><i class="icon-calendar"></i> Programar envío</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<hr class="divider">
			<div class="action-nav-normal">
				<div class="row-fluid">
					<div class="span3 action-nav-button">
						<a href="{{url('mail/editor/')}}{{idMail}}" title="New Project">
							<i class="icon-picture"></i>
							<span>Editor avanzado</span>
						</a>
						<span class="triangle-button green"><i class="icon-plus"></i></span>
						<br />
						<p>
							Cree contenido con estilo, bien estructurado y con los mejores estandares
							de forma muy sencilla en unos pocos minutos.
						</p>
					</div>

					<div class="span3 action-nav-button">
						<a href="{{url('mail/editor/')}}{{idMail}}" title="Messages">
							<i class="icon-magic"></i>
							<span>Plantillas prediseñadas</span>
						</a>
						<span class="triangle-button green"><i class="icon-ok"></i></span>
						<br />
						<p>
							Utilice alguna de nuestras plantillas prediseñadas, que le servirán de guía para 
							crear un contenido con estilo y llamativo a los correos.
						</p>
					</div>

					<div class="span3 action-nav-button">
						<a href="{{url('mail/html/')}}{{idMail}}" title="Files">
							<i class="icon-pencil"></i>
							<span>Html desde cero (avanzado)</span>
						</a>
						<span class="triangle-button blue"><i class="icon-align-left"></i></span>
						<br />
						<p>
							Utilice nuestro editor de código html, para crear contenido de correos desde código fuente
							(recomendado solo a usuarios avanzados).
						</p>
					</div>

					<div class="span3 action-nav-button">
						<a href="{{url('mail/import/')}}{{idMail}}" title="Users">
							<i class="icon-upload-alt"></i>
							<span>Importar desde una url</span>
						</a>
						<span class="triangle-button blue"><i class="icon-download-alt"></i></span>
						<br />
						<p>
							Importe contenido html desde un enlace externo
						</p>
					</div>	
				</div>
			</div>
		<hr class="divider">
	</div>
{% endblock %}