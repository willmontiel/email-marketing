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
							Editar o crear contenido
						</div>
						<div class="news-text">
							Rails 4.0 is still unfinished, but it is shaping up to become a great release ...
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
					<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
					<span class="breadcrumb-arrow"><span></span></span>
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
							Create beautiful, mobile-friendly
							emails with our new editor
						</p>
					</div>

					<div class="span3 action-nav-button">
						<a href="{{url('mail/predesigned')}}" title="Messages">
							<i class="icon-magic"></i>
							<span>Plantillas prediseñadas</span>
						</a>
						<span class="triangle-button green"><i class="icon-ok"></i></span>
						<br />
						<p>
							Create beautiful, mobile-friendly
							emails with our new editor
						</p>
					</div>

					<div class="span3 action-nav-button">
						<a href="#" title="Files">
							<i class="icon-pencil"></i>
							<span>Html desde cero (avanzado)</span>
						</a>
						<br />
						<p>
							Create beautiful, mobile-friendly
							emails with our new editor
						</p>
					</div>

					<div class="span3 action-nav-button">
						<a href="#" title="Users">
							<i class="icon-upload-alt"></i>
							<span>Importar desde una url</span>
						</a>
						<br />
						<p>
							Create beautiful, mobile-friendly
							emails with our new editor
						</p>
					</div>	
				</div>
			</div>
		<hr class="divider">
	</div>
{% endblock %}