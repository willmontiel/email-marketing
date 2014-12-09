{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
		function sendData() {
			var val = document.querySelector('input[name="radios"]:checked').value;
			
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
	
	<div class="row header-background" id="resume" style="display: none;">
		<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
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
		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 text-right">
			<a href="{{url('pdfmail/terminate')}}/{{mail.idMail}}" class="btn btn-sm btn-success">Siguiente</a>
		</div>
	</div>
	
	<div class="small-space"></div>
	
	<div class="row header-background">
		<div class="col-sm-12 col-xs-12 col-md-12 col-lg-12">
			<div class="space"></div>
			<div class="form-horizontal">
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="1" value="1" checked>
							NI_12345678_CC_12345678_1.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="2" value="2">
							NI_12345678_CC_12345678_1_12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="3" value="3">
							NI_12345678_CC_12345678_1_1.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="4" value="4">
							NI_12345678_CC_12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="5" value="5">
							NI_12345678_.._12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="6" value="6">
							12345678_PEPITO_PEREZ.pdf
						</label>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="7" value="7">
							12345678_pepito_perez.pdf
						</label>
					</div>
				</div>		
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="8" value="8">
							12345678_PEPITO_PEREZ.pdf o 12345678_PEPITO_.pdf
						</label>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="9" value="9">
							NI_12345678
						</label>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="10" value="10">
							12345678.pdf
						</label>
					</div>
				</div>
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="11" value="11">
							12345678_12345678_123456789_23456.pdf
						</label>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="12" value="12">
							CC_12345678.pdf ó CE_12345678.pdf ó CC_12345678_12345678.pdf
						</label>
					</div>
				</div>
				
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1 radio">
						<label>
							<input type="radio" name="radios" id="13" value="13">
							CC12345678.pdf
						</label>
					</div>
				</div>	
					
				<div class="form-group">
					<div class="col-xs-12 col-sm-12 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
					  <button class="btn btn-sm btn-default" onClick="sendData();">Enviar datos</button>
					</div>
				</div>
			 </div>
		</div>
	</div>
	
	<div class="space"></div>
{% endblock %}