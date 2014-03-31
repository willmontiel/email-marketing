{% extends "templates/index_b3.volt" %}
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
			{{partial('partials/wizard_partial')}}
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
				<form action="{{url('mail/plaintext')}}/{{mail.idMail}}"  method="post">
					<div class="padded">
						{% autoescape false %}
						<textarea name="plaintext" class="span12" type="text" rows="10" required="required" autofocus="autofocus">{{plaintext}}</textarea>
						{% endautoescape %}
					</div>
					<div class="form-actions">
						<button class="btn btn-default" value="prev" name="direction"><i class="icon-circle-arrow-left"></i> Anterior</button>
						<button class="btn btn-blue" value="next" name="direction">Siguiente <i class="icon-circle-arrow-right"></i></button>
						<button class="btn btn-black" value="plaintext" name="direction">Generar desde contenido html <i class="icon-refresh"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="preview-modal" class="modal hide fade preview-modal">
	</div>
{% endblock %}
