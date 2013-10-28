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
							Pre-visualización
						</div>
						<div class="news-text">
							Este es el resumen del correo
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
				<div class="breadcrumb-button">
					<span class="breadcrumb-label"><i class="icon-edit"></i> Editar/Crear contenido</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button">
					<span class="breadcrumb-label"><i class="icon-group"></i> Seleccionar destinatarios</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
				<div class="breadcrumb-button blue">
					<span class="breadcrumb-label"><i class="icon-calendar"></i> Programar envío</span>
					<span class="breadcrumb-arrow"><span></span></span>
				</div>
					<div class="breadcrumb-button"></div>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box offset3 span6">
				<div class="box-header">
					<div class="title">
						Enviar correo
					</div>
				</div>
				<div class="box-content">
					<form class="fill-up">
						<div class="padded">
							<label>Nombre de la campaña: </label>
							{{ MailForm.render('name') }}
							
							<label>Asunto: </label>
							{{ MailForm.render('subject') }}

							<label>Nombre del remitente: </label>
							{{ MailForm.render('fromName') }}

							<label>Dirección de correo del remitente: </label>
							{{ MailForm.render('fromEmail') }}

							<label>Dirección de correo de respuesta: </label>
							{{ MailForm.render('replyTo') }}

							<label>Nombre de la lista, base de datos o segmento a donde se envía: </label>
							<input type="text" value="Mi lista" class="span12"/>

							<label>Cantidad de contactos totales: </label>
							<input type="text" value="1200" class="span12"/>

							<label>Fecha y hora de envío: </label>
							<input type="text" value="Lunes, 28 de octubre de 2013, 5:07 pm" class="span12"/>
						</div>
						<div class="form-actions">
							<a href="" class="btn btn-default">Anterior</a>
							<button class="btn btn-blue">Siguiente</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
