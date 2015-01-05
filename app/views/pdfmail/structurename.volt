{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function sendData() {
			var val = document.querySelector('input[name="radios"]:checked').value;
			$('#loading').empty();
			$('#loading').append('<img src="{{url('')}}images/loading4.GIF" height="35" width="35"/>');
			$.ajax({
				url: "{{url('pdfmail/structurename')}}/{{mail.idMail}}",
				type: "POST",			
				data: {structure: val},
				error: function(msg){
					console.log(msg);
					$.gritter.add({class_name: 'error', title: '<i class="glyphicon glyphicon-warning"></i> Atención', text: "Error", sticky: false, time: 5000});
				},
				success: function(msg){
				console.log(msg);
					$.gritter.add({class_name: 'success', title: '<i class="glyphicon glyphicon-ok"></i> Atención', text: "Validación completada", sticky: false, time: 5000});
					
					$('#total').empty(); $('#totalM').empty(); $('#contacts').empty(); $('#contactsM').empty();
					
					$('#total').append(msg.result.totalfiles);
					$('#totalM').append(msg.result.totalfilematch);
					$('#contacts').append(msg.result.totalcontacts);
					$('#contactsM').append(msg.result.totalcontactsmatch);
					
					$("#resume").show('slow');
					$("#buttons").hide();
					$('#loading').empty();
				}
			});
		}
	</script>
{% endblock %}
{% block content %}
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<h1 class="sectiontitle">Seleccionar la estructuta del nombre de los PDF's</h1>
			<div class="bs-callout bs-callout-info">
				El siguiente paso es seleccionar la estructura que tiene el nombre de los PDF's, Ejemplo: <strong>NI_123456789_CC_123456789_1.pdf</strong>, el sistema validará automaticamente cuantos PDF's cumplen con la información suministrada
			</div>
		</div>
	</div>
	
	{% if structure is defined %}
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
				<div class="bs-callout bs-callout-success" style="padding-bottom: 24px !important;">
					Para refrescar la información, seleccione una estructura y haga clic en envia datos
					<span id="buttons" class="pull-right" style="display: block;">
						<a href="{{url('pdfmail/loadpdf')}}/{{mail.idMail}}" class="btn btn-sm btn-default">Atrás</a>
						<a href="{{url('pdfmail/terminate')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Siguiente</a>
					</span>
				</div>
			</div>
		</div>
	{% endif %}
	
	<div class="row" id="resume" style="display: none;">
		<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
			<div class="header-background">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Archivos encontrados en el servidor</th>
							<th>Archivos que coinciden con la estructura seleccionada</th>
							<th>Contactos totales en la lista</th>
							<th>Contactos totales que coinciden con al menos un pdf</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td id="total">0</td>
							<td id="totalM">0</td>
							<td id="contacts">0</td>
							<td id="contactsM">0</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 text-right">
			<a href="{{url('pdfmail/terminate')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Continuar</a>
		</div>
	</div>
	
	<div class="row">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<div class="small-space"></div>
			<div class="form-horizontal header-background">
				<div class="small-space"></div>
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="1" value="1" {% if mail.pdfstructure == 1%}checked{% endif %}>
							NI_12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="2" value="2" {% if mail.pdfstructure == 2%}checked{% endif %}>
							11523458.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="3" value="3" {% if mail.pdfstructure == 3%}checked{% endif %}>
							NI_12345678_CC_12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="4" value="4" {% if mail.pdfstructure == 4%}checked{% endif %}>
							NI12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
						<div style="display: inline-flex;">
							<button class="btn btn-sm btn-default" onClick="sendData();">Enviar datos</button>
							<div id="loading" style="padding-left: 20px;"></div>
						</div>
					</div>
				</div>
			 </div>
		</div>
	</div>
	
	<div class="space"></div>
{% endblock %}