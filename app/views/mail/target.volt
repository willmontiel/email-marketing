{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	<script type="text/javascript">
	$(function(){
        $("input[name=radios]").on('ifChecked', function () { 
			$("#db").hide();
			$("#list").hide();
			$("#seg").hide();
			
			
			$('#dbSelect').prop('selectedIndex',-1);
			$("#dbSelect").val('').trigger("liszt:updated");
			$('#listSelect').prop('selectedIndex',-1);
			$('#segSelect').prop('selectedIndex',-1);
			
			var val = $('input[name=radios]:checked').val();

			switch (val) {
				case "0":
					$("#db").show();
					break;
				case "1":
					$("#list").show();
					break;
				case "2":
					$("#seg").show();
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
					Seleccione destinatarios
				</div>
			</div>
			<div class="box-content">
				<form action="{{url('mail/target')}}/{{mail.idMail}}" method="post">
					<div class="padded">
						{% if db == true%}
							{% if mail.targetName is empty AND mail.target is empty %}
								<input type="radio" name="radios" class="icheck" value="0" id="dbRadio" >
								<label for="dbRadio">Base de datos de contactos</label> <br />
								<div id="db" style="display: none;">
									<select multiple="multiple" name="dbases[]"  id="dbSelect" class="chzn-select">
										{% for dbase in dbases %}
											<option value="{{dbase.idDbase}}">{{dbase.name}}</option>
										{% endfor %}
									</select>
								</div>
								<br />
								<input type="radio" name="radios" class="icheck" value="1" id="listRadio">
								<label for="listRadio">Lista de contactos </label>
								<div id="list" style="display: none;">
									<select multiple="multiple" name="contactlists[]" id="listSelect" class="chzn-select">
										{% for contactlist in contactlists %}
											<option value="{{contactlist.idContactlist}}">{{contactlist.name}},  {{contactlist.Dbase}}</option>
										{% endfor %}
									</select>
								</div>
								<br /><br />
								<input type="radio" name="radios" class="icheck" value="2" id="segmentRadio">
								<label for="segmentRadio">Segmentos</label>
								<br />
								<div id="seg" style="display: none;">
									<select multiple="multiple" name="segments[]" id="segSelect" class="chzn-select">
										{% for segment in segments %}
											<option value="{{segment.idSegment}}">{{segment.name}}</option>
										{% endfor %}
									</select>
								</div>
							{% else %}
								{{partial('partials/targetselect_partial')}}
							{% endif %}
						{% else %}
							No existen bases de datos ni contactos en la cuenta, para poder enviar un correo debe haber al menos un contacto. Por favor
							haga click en contactos y siga las instrucciones
						{% endif %}
					</div>
					<div class="form-actions">
					{% if db == true%}
						<button class="btn btn-blue" name="direction" value="prev"><i class="icon-circle-arrow-left"></i> Anterior</button>
						<button class="btn btn-blue" name="direction" value="next">Siguiente <i class="icon-circle-arrow-right"></i></button>
						<button class="btn btn-black" name="direction" value="filter"><i class="icon-cogs"></i> Avanzado</button>
						<a class="ShowDialogSendTest btn btn-default" data-backdrop="static" data-toggle="modal" href="#modal-simple" data-id="{{url('mail/sendtest')}}/{{mail.idMail}}"><i class="icon-thumbs-up"></i> Enviar prueba</a>
					{% else %}
						<a href="{{url('mail/track')}}/{{mail.idMail}}" class="btn btn-blue"><i class="icon-circle-arrow-left"></i> Anterior</a>
					{% endif %}
					</div>
				</form>
			</div>
		</div>
	</div>
	
	
	<div id="modal-simple" class="modal hide fade" aria-hidden="false">
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		  <h6 id="modal-tablesLabel">Enviar una prueba</h6>
		</div>
		<form id="testmail" method="post">
			<div class="modal-body">
				<p>
					Enviar una prueba a: <br />
					<input type="text" class="span12" id="target" name="target" required="required" autofocus="autofocus"/><br />
					Escriba las direcciones de correo a enviar la prueba separadas por comas
				</p>
				<br />
				<p>
					Incluir instrucciones o un mensaje personal (opcional) <br />
					<textarea class="span12" rows="3" cols="30" id="message" name="message" required="required"></textarea>
				</p>
			</div>
			<div class="modal-footer">
			  <button class="btn btn-default" data-dismiss="modal">Cancelar</button>
			  <input class="btn btn-blue" type="submit" value="Enviar">
			</div>
		</form>
	</div>
	<script type="text/javascript">
		$(function() {
			$('.ShowDialogSendTest').on('click', function() {
				var myURL = $(this).data('id');
				$("#testmail").attr('action', myURL );
			});
		});
	</script>
{% endblock %}
