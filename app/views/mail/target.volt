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
							Seleccione los destinatarios del correo
						</div>
						<div class="news-text">
							Esta es una parte muy importante, aqui decidirá quien debe recibir el correo, podrá seleccionar desde listas de contactos, segmentos hasta bases de
							datos, en un solo paso.
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
				<div class="breadcrumb-button blue">
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
	<div class="row-fluid span4">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Seleccione destinatarios
				</div>
			</div>
			<div class="box-content">
				<form action="{{url('mail/schedule/')}}{{idMail}}" method="post">
					<div class="padded">
						<div>
							<input type="radio" name="optionsRadios[abc]" class="icheck" checked id="iradio1">
							<label for="iradio1">Base de datos de contactos </label>
						</div>
						<select class="chzn-select" name="dbase">
							{% for dbase in dbases %}
								<option value="{{dbase.idDbase}}">{{dbase.name}}</option>
							{% endfor %}
						</select>
						<br /><br />
						<div>
							<input type="radio" name="optionsRadios[abc]" class="icheck" checked id="iradio1">
							<label for="iradio1">Lista de contactos </label>
						</div>
						<select class="chzn-select" name="contactlist">
							{% for contactlist in contactlists %}
								<option value="{{contactlist.idContactlist}}">{{contactlist.name}}</option>
							{% endfor %}
						</select>
						<br /><br />
						<div>
							<input type="radio" name="optionsRadios[abc]" class="icheck" checked id="iradio1">
							<label for="iradio1">Segmentos </label>
						</div>
						<select class="chzn-select" name="segments">
							{% for segment in segments %}
								<option value="{{segment.idSegment}}">{{segment.name}}</option>
							{% endfor %}
						</select>
					</div>
					<div class="form-actions">
						<a href="" class="btn btn-default">Cancelar</a>
						{{submit_button('Siguiente', 'class' : "btn btn-blue")}}
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}
