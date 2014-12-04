{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	{# Time picker #}
	{{ javascript_include('vendors/time-picker/js/bootstrap-timepicker.min.js')}}
	{{ stylesheet_link('vendors/time-picker/css/bootstrap-timepicker.min.css') }}
	
	{# Date picker #}
	{{ stylesheet_link('vendors/bootstrap_v3/datetimepickerb3/bootstrap-datetimepicker.min.css') }}
	{{ javascript_include('vendors/bootstrap_v3/datetimepickerb3/bootstrap-datetimepicker.js')}}
	{{ javascript_include('vendors/bootstrap_v3/datetimepickerb3/bootstrap-datetimepicker.es.js')}}

	{# Moment.js #}
	{{ javascript_include('js/pluggins-editor/moment/moment-with-langs.min.js')}}

	<script type="text/javascript">
		$(function () {
			{% if mail.scheduleDate is not null %}
				var date = moment.unix({{mail.scheduleDate}}).lang('es');
				
				var day = getDay(date);
				var month = getNumberMonth(date);
				var year = getYear(date);
				var d = day + '/' + month + '/' + year;
				$('#date').val(d);
				var time = getTime(date);
				$('#time').val(time);
			{% endif %}
		
			$("input[name=radios]:radio").change(function () {
				if ($(this).val() === 'later') {
					$('#programmer').show('slow');
				}
				else {
					$('#programmer').hide('slow');
				}
			});
		
		
			$('.time-picker').timepicker({
				showMeridian: false,
				defaultTime: false,
				showInputs: false
			});

			var now = moment().format('D/M/YYYY');
			$('.date-picker').datetimepicker({
				language: 'es',
				autoclose: true,
				weekStart: false,
				todayBtn: true,
				startDate: now,
				format: "dd/mm/yyyy",
				todayHighlight: true,
				showMeridian: false,
				startView: 2,
				minView: 2,
				forceParse: 0
			});
		});
		
		function sendMail() {
			$.ajax({
				url: "{{url('mail/confirmmail')}}/{{mail.idMail}}",
				type: "POST",			
				data: {},
				error: function(msg){
					var obj = $.parseJSON(msg.responseText);
					$.gritter.add({class_name: 'gritter_error', title: '<i class="glyphicon glyphicon-exclamation-sign"></i> Atención', text: obj.error, sticky: false, time: 30000});
				},
				success: function(msg){
					$(location).attr('href', "{{url('mail/list')}}"); 
				}
			});
		}
		
		function saveData() {
			var val = document.querySelector('input[name="radios"]:checked').value;
			var schedule;
			
			if (val === 'now') {
				schedule = val;
			}
			else if (val) {
				var date = $('#date').val();
				var time = $('#time').val();
				schedule = date + ' ' + time;
			}
			
			$.ajax({
				url: "{{url('pdfmail/terminate')}}/{{mail.idMail}}",
				type: "POST",			
				data: {schedule: schedule},
				error: function(msg){
					var obj = $.parseJSON(msg.responseText);
					$.gritter.add({class_name: 'gritter_error', title: '<i class="glyphicon glyphicon-exclamation-sign"></i> Atención', text: obj.error, sticky: false, time: 5000});
				},
				success: function(msg){
					$.gritter.add({class_name: 'gritter_error', title: '<i class="glyphicon glyphicon-ok"></i> Atención', text: "Se ha programado el envío exitosamente, Recuerde que debe confirmar el envío para terminar", sticky: false, time: 30000});
					$("#confirm").show('slow');
				}
			});
		}
		
		function getTime(date) {
			var hour = '' + date.hour();
			hour = (hour.length === 1)? '0' + hour: hour;
			var minutes = '' + date.minute();
			minutes = (minutes.length === 1)? '0' + minutes: minutes;

			var time = hour + ':' + minutes;
			return time;
		}

		function getMonth(date) {
			var m = date.month() + 1;
			var month = moment('' + m).lang('es').format('MMMM');	
			return month;	
		}

		function getNumberMonth(date) {
			var m = date.month() + 1;	
			return m;
		}

		function getDay(date) {
			var day = date.date();
			return day;
		}

		function getYear(date) {
			var year = date.year();
			return year;
		}
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Seleccionar fecha, hora y confirmar envío</h1>
			<div class="bs-callout bs-callout-info">
				El siguiente paso es seleccionar la fecha y hora del envío. también se puede hacer un envío de prueba para verificar que 
				todo este en orden. Una vez realizado lo anterior se puede confirmar el envío del correo para que quede programado.
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			{{flashSession.output()}}
		</div>
	</div>
	
	<div class="row header-background">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h4 class="paneltitle">Programación del envío</h4><br />
			<div class="form-horizontal">
				<div class="form-group">
					<label class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						Envíar correo: 
					</label>

					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<label>
							<input type="radio" name="radios" id="now" value="now">
							Ahora mismo
						</label>
					</div>

					<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
						<label>
							<input type="radio" name="radios" id="later" value="later" {% if mail.scheduleDate is not null %}checked{% endif %}>
							Seleccionar fecha
						</label> <br />

						<div id="programmer" style="display: {% if mail.scheduleDate is null %}none{% else %}block{% endif %}; margin-top: 20px; margin-bottom: 20px;">
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<div class="input-group date date-picker group-datepicker">
									<input type="text" class="form-control" readonly="readonly" name="date" id="date" />
									<span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
									<span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
								</div>	
							</div>

							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<div class="input-append bootstrap-timepicker">
									<input type="text" class="time-picker input-small" readonly="readonly" name="time" id="time" />
									<span class="add-on">
										<i class="glyphicon glyphicon-time" style="top: 4px !important;"></i>
									</span>
								</div>	
							</div>
						</div>
					</div>
				</div>
					
				<div class="small-space"></div>
				
				<div class="form-group">
					<div class="col-sm-12">
						<button class="btn btn-default btn-sm" onClick="saveData();">Programar</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row header-background" id="confirm" style="display: {% if mail.scheduleDate is not null %}block{% endif %};">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<button class="ShowDialogSendTest btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Enviar prueba</button>
			<button class="btn btn-sm btn-success" onClick="sendMail();">Confirmar</button>
		</div>
	</div>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h1 class="modal-title" id="myModalLabel">Enviar una prueba</h1>
				</div>
			<form {% if mail is defined %} action="{{url('pdfmail/sendtest')}}/{{mail.idMail}}" {% endif %} id="testmail" method="post" role="form">
				<div class="modal-body">
					<div class="form-group">
						<label for="target">Enviar una prueba a:</label>
						<input type="text" class="form-control" id="target" name="target" autofocus="autofocus" placeholder="Escriba la dirección de correo"/>
					</div>
						
					<div class="form-group">
						<label for="id">Número de identificación:</label>
						<input type="text" class="form-control" id="target" name="id" placeholder="Escriba un número de identificación cualquiera para hacer la prueba"/>
					</div>
						
					<div class="form-group">
						<label for="message">Incluír instrucciones o un mensaje personal (opcional)</label>
						<textarea class="form-control" rows="3" cols="30" id="message" name="message"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn btn-sm btn-default extra-padding" data-dismiss="modal">Cancelar</button>
					<input class="btn btn-sm btn-primary extra-padding" type="submit" value="Enviar">
				</div>
			</form>
			</div>
		</div>
	</div>
{% endblock %}