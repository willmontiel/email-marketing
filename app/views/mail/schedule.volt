{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ javascript_include('bootstrap/datepicker/js/bootstrap-datetimepicker.min.js')}}
	{{ stylesheet_link('bootstrap/datepicker/css/bootstrap-datetimepicker.min.css') }}
	<script type="text/javascript">
	$(function(){
        $("input[name=schedule]").on('ifChecked', function () { 
			$("#date").hide();
			$("#dateSchedule").val("");
			var val = $('input[name=schedule]:checked').val()
			if (val == "after") {
				$("#date").show();
			}
         });
		
		var nowTemp = new Date();
		var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), nowTemp.getHours(), nowTemp.getMinutes(), nowTemp.getSeconds(), 0);
	 //HH:mm PP
		$('#scheduleArea').datetimepicker({
			language: 'en',
			maskInput: true,
			pickTime: false,
			format: "dd/MM/yyyy hh:mm",
			//pick12HourFormat: true,
			pickSeconds: false,
			startDate: now
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
					<form action="{{url('mail/schedule')}}/{{mail.idMail}}" method="post">
						<div class="padded">
							<input type="radio" name="schedule" class="icheck" value="rightNow" id="rightNow"/>
							<label for="rightNow">Enviar el correo de inmediato: </label><br />
							
							<input type="radio" name="schedule" class="icheck" value="after" id="inFuture"/>
							<label for="inFuture">Programar el correo para que se envíe en la siguiente fecha: </label><br />
							<div id="date" style="display: none;">
								<label>Seleccione fecha de envío: </label>
								<div id="scheduleArea" class="input-append date" class="span5">
									<input type="text" class="add-on input-date-picker" name="dateSchedule" id="dateSchedule"/>
								</div>
							</div>
						</div>
						<div class="form-actions">
							<button class="btn btn-default" name="direction" value="prev"><i class="icon-circle-arrow-left"></i> Anterior</button>
							<button class="btn btn-blue" name="direction" value="next">Siguiente <i class="icon-circle-arrow-right"></i></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
{% endblock %}
