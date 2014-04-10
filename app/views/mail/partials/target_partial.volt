{% if db == true%}
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
						{% if mail.targetName is empty AND mail.target is empty %}
							<input type="radio" name="radios" value="dataBase" id="dbRadio">
							<label for="dbRadio">Base de datos de contactos</label> <br />
							<div id="db" style="display: none;">
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.dbs"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="dbaselist"
										id="dbases"
										class="form-control"}}'
								 }}
							</div>
							<br />
							<input type="radio" name="radios" value="contactList" id="listRadio">
							<label for="listRadio">Lista de contactos </label>
							<div id="list" style="display: none;">
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.lists"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="list"
										id="contactlists"
										class="form-control"}}'
								 }}
							</div>
							<br /><br />
							<input type="radio" name="radios" value="segment" id="segmentRadio">
							<label for="segmentRadio">Segmentos</label>
							<br />
							<div id="seg" style="display: none;">
								{{' {{view Ember.Select
									multiple="true"
									contentBinding="App.segments"
									optionValuePath="content.id"
									optionLabelPath="content.name"
									selectionBinding="segmentlist"
									id="segments"
									class="form-control"
								}} '}}
							</div>
						{% else %}
							{{partial('partials/targetselect_partial')}}
						{% endif %}
					</div>
					<div class="tab-pane fade" id="filter">
						<br />
							<input type="radio" name="filter" id="byMail" value="byMail" />
							<label for="byMail">Enviar a contactos que tenga el siguiente correo: </label><br />
							<div id="mail" style="display: none;">
								{{'{{view Ember.TextField valueBinding="filterByEmail" id="sendByMail" class="form-control"}}'}}
							</div>

							<br />

							<input type="radio" name="filter" id="byOpen" value="byOpen" />
							<label for="byOpen">Enviar a contactos que hayan abierto el siguiente correo electrónico: </label><br />
							<div id="open" style="display: none;">
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.sendByOpen"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="open"
										id="sendByOpen"
										class="form-control"}}'
								 }}
							</div>

							<br />

							<input type="radio" name="filter" id="byClick" value="byClick" />
							<label for="byClick">Enviar a contactos que hayan hecho click en el siguiente enlace: </label><br />
							<div id="click" style="display: none;">
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.sendByClick"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="click"
										id="sendByClick"
										class="form-control"}}'
								 }}
							</div>

							<br />

							<input type="radio" name="filter" id="byExclude" class="icheck" value="byExclude" />
							<label for="byExclude">No enviar a aquellos contactos que hayan abierto el siguiente correo electrónico: </label>
							<div id="exclude" style="display: none;">
								{{ '{{view Ember.Select
										multiple="true"
										contentBinding="App.excludeContact"
										optionValuePath="content.id"
										optionLabelPath="content.name"
										selectionBinding="exclude"
										id="excludeContact"
										class="form-control"}}'
								 }}
							</div>
					</div>
				</div>
			</div>
		</div>
		<br />
		<div class="form-group">
			<div class="col-sm-6 col-md-offset-6 text-right">
				<a href="#" class="btn btn-default">Descartar cambios</a>
				<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
				{#
				<input type="button" class="btn btn-primary" value="Aplicar cambios" onClick="createBlock(this.form, 'target')">
				#}
			</div>
		</div>
		<br />
	</form>
{% else %}
	No existen bases de datos ni contactos en la cuenta, para continuar el proceso con el correo debe haber al menos un contacto. Por favor
	haga click en contactos y siga las instrucciones
{% endif %}