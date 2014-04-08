<form class="form-horizontal" role="form">
	<div class="form-group">
		<label for="target" class="col-sm-2 control-label">Para: </label>
		<div class="col-sm-10">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#target" data-toggle="tab">Destinatarios</a></li>
				<li><a href="#filter" data-toggle="tab">Filtro</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="target">
					<br />
					{% if db == true%}
						{% if mail.targetName is empty AND mail.target is empty %}
							<input type="radio" name="radios" value="0" id="dbRadio" >
							<label for="dbRadio">Base de datos de contactos</label> <br />
							<div id="db" style="display: none;">
								<select multiple="multiple" name="dbases[]" class="form-control" id="dbSelect">
									{% for dbase in dbases %}
										<option value="{{dbase.idDbase}}">{{dbase.name}}</option>
									{% endfor %}
								</select>
							</div>
							<br />
							<input type="radio" name="radios" value="1" id="listRadio">
							<label for="listRadio">Lista de contactos </label>
							<div id="list" style="display: none;">
								<select multiple="multiple" name="contactlists[]" class="form-control" id="listSelect">
									{% for contactlist in contactlists %}
										<option value="{{contactlist.idContactlist}}">{{contactlist.name}},  {{contactlist.Dbase}}</option>
									{% endfor %}
								</select>
							</div>
							<br /><br />
							<input type="radio" name="radios" value="2" id="segmentRadio">
							<label for="segmentRadio">Segmentos</label>
							<br />
							<div id="seg" style="display: none;">
								<select multiple="multiple" name="segments[]" class="form-control" id="segSelect">
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
				<div class="tab-pane fade" id="filter">
					<br />
					{% if db == true%}
						<input type="radio" name="filter" id="byMail" value="byMail" />
						<label for="byMail">Enviar a contactos que tenga el siguiente correo: </label><br />
						<div id="mail" style="display: none;">
							<input type="email" name="sendByMail" id="sendMail" class="form-control">
						</div>

						<input type="radio" name="filter" id="byOpen" value="byOpen" />
						<label for="byOpen">Enviar a contactos que hayan abierto el siguiente correo electrónico: </label><br />
						<div id="open" style="display: none;">
							<select multiple="multiple" name="sendByOpen[]" id="sendOpen" class="form-control">
								{% if mails %}
									{% for m in mails%}
										<option value="{{m.idMail}}">{{m.name}}</option>
									{% endfor %}	
								{% endif%}
							</select>
						</div>

						<input type="radio" name="filter" id="byClick" value="byClick" />
						<label for="byClick">Enviar a contactos que hayan hecho click en el siguiente enlace: </label><br />
						<div id="click" style="display: none;">
							<select multiple="multiple" name="sendByClick[]" id="sendClick" class="form-control">
								{% if links %}
									{% for link in links %}
										<option value="{{link.idMailLink}}">{{link.link}}</option>
									{% endfor%}
								{% endif %}
							</select>
						</div>

						<input type="radio" name="filter" id="byExclude" class="icheck" value="byExclude" />
						<label for="byExclude">No enviar a aquellos contactos que hayan abierto el siguiente correo electrónico: </label>
						<div id="exclude" style="display: none;">
							<select multiple="multiple" name="excludeContact[]" id="sendExclude" class="form-control">
								{% if mails %}
									{% for m2 in mails%}
										<option value="{{m2.idMail}}">{{m2.name}}</option>
									{% endfor %}	
								{% endif%}
							</select>
						</div>
					{% else %}
						No existen bases de datos ni contactos en la cuenta, para poder enviar un correo debe haber al menos un contacto. Por favor
						haga click en contactos y siga las instrucciones
					{% endif %}
				</div>
			</div>
		</div>
	</div>
	<br />
	<div class="form-group">
		<div class="col-sm-6 col-md-offset-6 text-right">
			<a href="#" class="btn btn-default">Descartar cambios</a>
			<a href="#" class="btn btn-primary">Aplicar cambios</a>
		</div>
	</div>
	<br />
</form>