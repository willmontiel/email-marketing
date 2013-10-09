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
							Cree contenido desde codigo fuente HTML
						</div>
						<div class="news-text">
							Esta función le permite crear contendo html desde cero, es recomendada para usuarios
							avanzados
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
		<div class="span12">
			<div class="box">
				<div class="box-header">
				</div>
				<div class="box-content">
					<div class="padded">
						{{ form('mail/html', 'id': 'newMail', 'method': 'Post', 'class' : 'fill-up') }}
							<input type="hidden" name="idMail" value="{{idMail}}">
							<label>Cree su propio código HTML: </label>
							{{ MailForm.render('content') }}
						</form>
					</div>
					<div class="form-actions">
						<a href="" class="btn btn-default">Cancelar</a>
						<button class="btn btn-blue">Siguiente</button>
					</div>
				</div>
			</div>
		</div>
	</div>
{% endblock %}