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
							Crear contenido texto
						</div>
						<div class="news-text">
							La aplicación automaticamente creará contenido textual a partir del contenido html que se haya
							creado.
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
		<div class="box">
			<div class="box-header">
				<div class="title">
					Mensaje de texto plano
				</div>
			</div>
			<div class="box-content">
				<form action="{{url('mail/plaintext')}}/{{idMail}}" class="fill-up" method="post">
					<div class="padded">
						{% autoescape false %}
						<textarea name="plaintext" type="text" rows="10" required="required" autofocus="autofocus">{{plaintext}}</textarea>
						{% endautoescape %}
					</div>
					<div class="form-actions">
						<a href="" class="btn btn-default">Anterior</a>
						<input type="submit" class="btn btn-blue" value="Siguiente" />
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}
