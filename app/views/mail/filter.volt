{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
	$(function(){
        $("input[name=filter]").on('ifChecked', function () { 
			$("#mail").hide();
			$("#open").hide();
			$("#click").hide();
			$("#exclude").hide();
			
			$("#sendMail").val('');
			$('#sendOpen').prop('selectedIndex',-1);
			$('#sendClick').prop('selectedIndex',-1);
			$('#sendExclude').prop('selectedIndex',-1);
			
			var val = $('input[name=filter]:checked').val();
			switch (val) {
				case "byMail":
					$("#mail").show();
					break;
				case "byOpen":
					$("#open").show();
					break;
				case "byClick":
					$("#click").show();
					break;
				case "byExclude":
					$("#exclude").show();
					break;
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
							Seleccione contactos por medio de filtros
						</div>
						<div class="news-text">
							Esta función le permite seleccionar contactos que cumplan ciertas condiciones
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
		<div class="span12">
			<div class="box offset2 span8">
				<div class="box-header">
					<div class="title">
						<i class="icon-cogs"></i> Avanzado
					</div>
				</div>
				<div class="box-content">
					<form action = "{{url('mail/filter')}}/{{mail.idMail}}" method="post">
						<div class="padded">
							<input type="radio" name="filter" id="byMail" class="icheck" value="byMail" />
							<label for="byMail">Enviar a contactos que tenga el siguiente correo: </label><br />
							<div id="mail" style="display: none;">
								<input type="email" name="sendByMail" id="sendMail" />
							</div>
							
							<input type="radio" name="filter" id="byOpen" class="icheck" value="byOpen" />
							<label for="byOpen">Enviar a contactos que hayan abierto el siguiente correo electrónico: </label><br />
							<div id="open" style="display: none;">
								<select multiple="multiple" name="sendByOpen[]" id="sendOpen" class="chzn-select">
									<option value="any">Cualquier correo enviado</option>
									<option value="week10">Boletin informativo semana 10</option>
									<option value="week11">Boletin informativo semana 11</option>
									<option value="week12">Boletin informativo semana 12</option>
								</select>
							</div>
							
							<input type="radio" name="filter" id="byClick" class="icheck" value="byClick" />
							<label for="byClick">Enviar a contactos que hayan hecho click en el siguiente enlace: </label><br />
							<div id="click" style="display: none;">
								<select multiple="multiple" name="sendByClick[]" id="sendClick" class="chzn-select">
									<option value="any">Cualquier correo enviado</option>
									<option value="week10">Boletin informativo semana 10</option>
									<option value="week11">Boletin informativo semana 11</option>
									<option value="week12">Boletin informativo semana 12</option>
								</select>
							</div>
							
							<input type="radio" name="filter" id="byExclude" class="icheck" value="byExclude" />
							<label for="byExclude">No enviar a aquellos contactos que hayan abierto el siguiente correo electrónico: </label>
							<div id="exclude" style="display: none;">
								<select multiple="multiple" name="excludeContact[]" id="sendExclude" class="chzn-select">
									<option value="any">Cualquier correo enviado</option>
									<option value="week10">Boletin informativo semana 10</option>
									<option value="week11">Boletin informativo semana 11</option>
									<option value="week12">Boletin informativo semana 12</option>
								</select>
							</div>
							
						</div>
						<div class="form-actions">
							<a class="btn btn-default" href="{{url('mail/target/')}}/{{mail.idMail}}" ><i class="icon-circle-arrow-left"></i> Anterior</a>
							<button class="btn btn-blue" name="direction" value="next">Siguiente <i class="icon-circle-arrow-right"></i></button>
						</div>	
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}