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
			{{ partial("partials/wizard_partial") }}
		</div>
	</div>
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid">
		<div class="span12">
			<div class="box offset2 span8">
				<div class="box-header">
					<div class="title">
						Confirmar envío de correo
					</div>
				</div>
				<form>
					<div class="box-content">
						<div class="box-section news">
							<div class="news-content">
								<div class="news-time">
									<a href="{{url('mail/setup')}}/{{mail.idMail}}">
										<div class="relief">Editar</div>
									</a>
								</div>
									<strong>Nombre de la campaña: </strong>
								<div class="news-text">
									{{mail.name}}
								</div>
							</div>
							<br />
							<div class="news-content">
								<strong>Asunto:</strong>
								<div class="news-text">
									{{mail.subject}}
								</div>
							</div>
							<br />
							<div class="news-content">
								<strong>Nombre del remitente: </strong>
								<div class="news-text">
									{{mail.fromName}}
								</div>
							</div>
							<br />
							<div class="news-content">
								<strong>Dirección de correo del remitente: </strong>
								<div class="news-text">
									{{mail.fromEmail}}
								</div>
							</div>
							<br />
							<div class="news-content">
								<strong>Dirección de correo de respuesta:  </strong>
								<div class="news-text">
									{{mail.replyTo}}
								</div>
							</div>
						</div>
						<div class="box-section news">
							<div class="news-content">
								<div class="news-time">
									<a href="{{url('mail/target')}}/{{mail.idMail}}">
										<div class="relief">Editar</div>
									</a>
								</div>
								<strong>Nombre de la lista, base de datos o segmento a donde se envía: </strong>
								<div class="news-text">
									{{mail.targetName}}
								</div>
							</div>
							<br />
							<div class="news-content">
								<strong>Contactos totales aproximados: </strong>
								<div class="news-text">
									{{mail.totalContacts}}
								</div>
							</div>
						</div>
						<div class="box-section news">
							<div class="news-content">
								<div class="news-time">
									<a href="{{url('mail/schedule')}}/{{mail.idMail}}">
										<div class="relief">Editar</div>
									</a>
								</div>
								<strong>Fecha y hora de envío:  </strong>
								<div class="news-text">
									{{date('F j - Y,  g:i a', mail.dateSchedule)}}
								</div>
							</div>
						</div>
					</div>
					<div class="box-footer padded">
						<a href="{{url('mail/schedule')}}/{{mail.idMail}}" class="btn btn-default"><i class="icon-circle-arrow-left"></i> Anterior</a>
						<button class="btn btn-blue" name="direction" value="next">Confirmar <i class="icon-envelope"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}
