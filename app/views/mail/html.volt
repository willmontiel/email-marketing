{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('js/preview.js')}}
	{{ javascript_include('js/stoperror.js')}}
	{{ javascript_include('redactor/redactor.js')}}
	{{ stylesheet_link('redactor/redactor.css') }}
	<script type="text/javascript">
	$(document).ready(
		function()
		{
			try {
				$('#redactor_content').redactor({fullpage: true});
			}
			catch (e) {}
		}
	);
	</script>
{% endblock %}
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
					<a href="{{url('mail/setup')}}/{{idMail}}">
						<span class="breadcrumb-label"><i class="icon-check"></i> Información de correo</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</a>
				</div>
				<div class="breadcrumb-button blue">
					<a href="{{url('mail/source')}}/{{idMail}}">
						<span class="breadcrumb-label"><i class="icon-edit"></i> Editar/Crear contenido</span>
						<span class="breadcrumb-arrow"><span></span></span>
					</a>
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
	<div class="row-fluid">
		<div class="span12">
			<div class="box">
				<div class="box-header">
				</div>
				<div class="box-content">
					<form action = "{{url('mail/html')}}/{{idMail}}" method="post">
						<div class="padded">
							<!---<input type="hidden" name="idMail" value="">-->
							<label>Cree su propio código HTML: </label>
							{{ MailForm.render('content') }}
						</div>
						<div class="form-actions">
							<a href="{{url('mail/source')}}/{{idMail}}" class="btn btn-default">Anterior</a>
							{{ submit_button("Siguiente", 'class' : "btn btn-blue") }}
							<input onclick="verHTML(this.form)" type="button" value="Visualizar" class="btn btn-black">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}