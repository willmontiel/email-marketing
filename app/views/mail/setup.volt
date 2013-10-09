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
							Información de correo
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
				<div class="breadcrumb-button blue">
					<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button">
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
	<br />
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Nuevo correo
				</div>
			</div>
			<div class="box-content">
				{{ form('mail/setup', 'id': 'newMail', 'method': 'Post', 'class' : 'fill-up') }}
					<div class="padded">
						<label>*Nombre: </label>
						{{ MailForm.render('name') }}
						
						<label>*Asunto: </label>
						{{ MailForm.render('subject') }}
										
						<label>*Enviar desde este nombre: </label>
						{{ MailForm.render('fromName') }}
						
						<label>*Enviar desde este correo: </label>
						{{ MailForm.render('fromEmail') }}
						
						<label>*Responder a este correo: </label>
						{{ MailForm.render('replyTo') }}
					</div>
					<div class="form-actions">
						<a href="{{url('')}}" class="btn btn-default">Cancelar</a>
						<button class="btn btn-blue">Siguiente</button>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}