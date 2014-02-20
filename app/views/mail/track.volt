{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		$(function(){
			$("#allLinks").hide();
			var a = {{x}};
			{% set checked = null %}
			{% if analytics !== null %}
				{% set checked = 'checked' %}
			{% endif %}
				
			if (a !== null) {
				$("#allLinks").show();
				console.log('1')
			}
			$("#googleAnalytics").on('click', function () {
				console.log('2')
				if ($(this)[0].checked) {
					$("#allLinks").show();
					console.log('3')
				}
				else {
					$("#allLinks").hide();
				}
				
			 });
		});
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
			{{partial('partials/wizard_partial')}}
		</div>
	</div>
	<br />
	<div class="row-fluid">
		{{ flashSession.output()}}
	</div>
	<br />
	<div class="row-fluid offset3 span5">
		<div class="box">
			<div class="box-header">
				<div class="title">
					Agregue seguimiento de Google Analytics
				</div>
			</div>
			<div class="box-content">
				<form action="{{url('mail/track')}}/{{mail.idMail}}" method="post">
					<div class="padded">
						{% if links|length !== 0 %}
							<input type="checkbox" name="googleAnalytics" value="googleAnalytics" {{checked}} id="googleAnalytics">
							Agregar seguimiento de Google Analytics a los siguientes enlaces: <br /><br />
							{% if analytics !== null %}
								<div id="allLinks" style="display: none;">
									<label>Nombre de campaña: </label>
									<input type="text" name="campaignName" autofocus="autofocus" value="{{campaignName}}" class="span10"> <br />

									<label>Enlaces: </label>
									<select multiple="multiple" name="links[]"  id="links" class="chzn-select">
										{% for link in links%}
											 <option value="{{link}}" {% for analytic in analytics%}{% if analytic == link %}selected{% endif %}{% endfor %}>{{link}}</option>
										{% endfor %}
									</select>
								</div>
							{% else %}
								<div id="allLinks" style="display: none;">
									<label>Nombre de campaña: </label>
									<input type="text" name="campaignName" autofocus="autofocus" class="span10"> <br />

									<label>Enlaces: </label>
									<select multiple="multiple" name="links[]"  id="links" class="chzn-select">
										{% for link in links%}
											<option value="{{link}}">{{link}}</option>
										{% endfor %}
									</select>
								</div>
							{% endif%}
						{% else %}
							No se encontrarón enlaces, si desea agregar seguimiento de Google Analytics, por favor agregue por lo menos uno.
						{% endif %}
					</div>
					<div class="form-actions">
						<button button class="btn btn-blue" name="direction" value="prev"><i class="icon-circle-arrow-left"></i> Anterior</button>
						<button class="btn btn-blue" name="direction" value="next">Siguiente <i class="icon-circle-arrow-right"></i></button>
					</div>
				</form>
			</div>
		</div>
	</div>
{% endblock %}