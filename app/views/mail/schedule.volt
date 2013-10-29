{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
	$(function(){
        $("input[name=schedule]").on('ifChecked', function () { 
			$("#date").hide();
			$("#dateSchedule").val("");
			var val = $('input[name=schedule]:checked').val()
			if (val == "1") {
				$("#date").show();
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
							Programar el envío
						</div>
						<div class="news-text">
							Esta es la ultima parte del proceso, despues de haber seguido los pasos correctamente solo
							tendrá que programar el envío, puede enviar el correo ya o programar una fecha
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
		<div class="span12">
			<div class="box offset3 span6">
				<div class="box-header">
					<div class="title">
						Programar el envío
					</div>
				</div>
				<div class="box-content">
					<form>
						<div class="padded">
							<input type="radio" name="schedule" class="icheck" value="0" id="rightNow"/>
							<label for="rightNow">Enviar el correo de inmediato: </label><br />
							
							<input type="radio" name="schedule" class="icheck" value="1" id="inFuture"/>
							<label for="inFuture">Programar el correo para que se envíe en el futuro: </label><br />
							<div id="date" style="display: none;">
								<label>Seleccione fecha de envío: </label>
								<input class="datepicker fill-up" type="text" placeholder="Fecha de envío" name="dateSchedule" id="dateSchedule">
							</div>
						</div>
						<div class="form-actions">
							<a href="" class="btn btn-default">Anterior</a>
							<input type="submit" class="btn btn-blue" value="Siguiente">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
