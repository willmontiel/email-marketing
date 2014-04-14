{{ '{{#if isTargetExpanded}}' }}
	<div class="panel panel-default">
		<div class="panel-heading">
		  <h3 class="panel-title">Seleccione destinatarios</h3>
		</div>
		<div class="panel-body">
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
										{{ ' {{view Ember.RadioButtonTarget name="radioTarget" value="dataBase" id="dbRadio"}}' }}
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
										{{ ' {{view Ember.RadioButtonTarget name="radioTarget" value="contactList" id="listRadio"}}' }}
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
										{{ ' {{view Ember.RadioButtonTarget name="radioTarget" value="segment" id="segmentRadio"}}' }}
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
										{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byMail" id="byMail"}}' }}
										<label for="byMail">Enviar a contactos que tenga el siguiente correo: </label><br />
										<div id="mail" style="display: none;">
											{{'{{view Ember.TextField valueBinding="filterByEmail" id="sendByMail" class="form-control"}}'}}
										</div><br />

										{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byOpen" id="byOpen"}}' }}
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
										</div><br />

										{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byClick" id="byClick"}}' }}
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
										</div><br />
										
										{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byExclude" id="byExclude"}}' }}
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
							<button class="btn btn-default" {{'{{action "discardTarget" this}}'}}>Descartar cambios</button>
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
		</div>
	</div>
{{ '{{/if}}' }}