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
							Importar contenido html desde una url
						</div>
						<div class="news-text">
							Importe contenido html desde una url.
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
	<br />
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<div class="row-fluid">
		<div class="span6 offset3">
			<div class="box">
				<div class="box-header">
					<div class="title">
						Importar desde una URL
					</div>
				</div>
				<div class="box-content">
					<form action="{{url('mail/import/')}}{{mail.idMail}}" method="post" class="fill-up">
						<div class="padded">
							<label>Escriba o copie y pegue la dirección del enlace (url)</label>
							<input type="url" name="url" required="required" autofocus="autofocus">
							
							<div>
								<input type="checkbox" class="icheck" id="icheck1" name="image" value="load">
								<label for="icheck1">Importar imágenes</label>
							</div>
						</div>
						<div class="form-actions">
							<a href="{{url('mail/source')}}/{{mail.idMail}}" class="btn btn-default"><i class="icon-remove-sign"></i> Cancelar</a>
							<button class="btn btn-blue">Importar <i class="icon-cloud-download"></i></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}