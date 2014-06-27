{{'{{#unless isTargetExpanded }}'}}
	<div {{'{{bind-attr class=":wrapper targetEmpty:bg-warning: "}}'}}>
		<dl class="dl-horizontal" {{ '{{action "expandTarget" this}}' }}>
			{{'{{#if targetEmpty }}'}}
				<dt>Para:</dt>
				<dd><i>Elija los destinatarios...</i></dd>
			{{'{{else}}'}}
				<dt>Para:</dt>
				<dd>
					{{ '{{#if dbaselist}}' }}
						{{ '{{#each dbaselist}}' }}
							Base de datos: {{' {{name}} '}}, 
						{{ '{{/each}}' }}
					{{ '{{/if}}' }}

					{{ '{{#if list}}' }}
						{{ '{{#each list}}' }}
							Lista de contactos: {{' {{name}} '}}, 
						{{ '{{/each}}' }}
					{{ '{{/if}}' }}

					{{ '{{#if segmentlist}}' }}
						{{ '{{#each segmentlist}}' }}
							Segmento: {{' {{name}} '}}, 
						{{ '{{/each}}' }}
					{{ '{{/if}}' }}

					{{ '{{#unless filterEmpty}}' }}
						(filtro)
					{{ '{{/unless}}' }}
				</dd>
				<dd>
					Contactos: <strong>{{ '{{totalContacts}}' }}</strong> (En el momento del envío podría variar)
				</dd>
			{{'{{/if}}'}}
		</dl>
	</div>
{{ '{{/unless}}' }}

{{ '{{#if isTargetExpanded}}' }}
	<div class="panel-heading">
	  <h3 class="panel-title">Destinatarios</h3>
	</div>
	<div class="panel panel-default">
		<div class="panel-body">
			{{ partial("partials/select_target_partial") }}
			
			
			
			{% if db == true%}
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label for="target" class="col-sm-2 control-label">Para: </label>
						<div class="col-sm-10">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#target" data-toggle="tab"><img src="{{url('b3/images/email-color.png')}}" class="" alt=""></a></li>
								<li><a href="#filter" data-toggle="tab"><img src="{{url('b3/images/filter.jpg')}}" class="" alt=""></a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane fade in active" id="target">
									<br />
									{{ '{{#if isTargetBydbases}}' }}
										{{ ' {{view Ember.RadioButtonTarget name="radioTarget" value="dataBase" id="dbRadio" checked="checked"}}' }}
									{{ '{{else}}' }}
										{{ ' {{view Ember.RadioButtonTarget name="radioTarget" value="dataBase" id="dbRadio"}}' }}
									{{ '{{/if}}' }}
									
									<label for="dbRadio">Bases de datos</label>
									<div id="db" style="{{ '{{unbound dbaseChecked}}' }}">
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
									<br /><br />
									
									{{ '{{#if isTargetByLists}}' }}
											{{ ' {{view Ember.RadioButtonTarget selectionBinding="radioTarget" name="radioTarget" value="contactList" id="listRadio" checked="checked"}}' }}
									{{ '{{else}}' }}
										{{ ' {{view Ember.RadioButtonTarget selectionBinding="radioTarget" name="radioTarget" value="contactList" id="listRadio"}}' }}
									{{ '{{/if}}' }}
									<label for="listRadio">Lista de contactos</label>
									<div id="list" style="{{ '{{unbound listChecked}}' }}">
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
									
									{{ '{{#if isTargetBySegments}}' }}
										{{ ' {{view Ember.RadioButtonTarget selectionBinding="radioTarget" name="radioTarget" value="segment" id="segmentRadio" checked="checked"}}' }}
									{{ '{{else}}' }}
										{{ ' {{view Ember.RadioButtonTarget selectionBinding="radioTarget" name="radioTarget" value="segment" id="segmentRadio"}}' }}
									{{ '{{/if}}' }}
									<label for="segmentRadio">Segmentos</label>
									<br />
									<div id="seg" style="{{ '{{unbound segmentChecked}}' }}">
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
								</div>
								<div class="tab-pane fade" id="filter">
									<br />
										{{ '{{#if isFilterByEmail}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byMail" id="byMail" checked="checked"}}' }}
										{{ '{{else}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byMail" id="byMail"}}' }}
										{{ '{{/if}}' }}
										<label for="byMail">Enviar a contactos que tenga el siguiente correo: </label><br />
										
										<div id="mail" style="{{ '{{unbound filterEmailChecked}}' }}">
											{{'{{view Ember.TextField valueBinding="filterByEmail" id="sendByMail" class="form-control"}}'}}
										</div><br />
										
										{{ '{{#if isFilterByOpen}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byOpen" id="byOpen" checked="checked"}}' }}
										{{ '{{else}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byOpen" id="byOpen"}}' }}
										{{ '{{/if}}' }}
										<label for="byOpen">Enviar a contactos que hayan abierto el siguiente correo electrónico: </label><br />
										
										<div id="open" style="{{ '{{unbound filterOpenChecked}}' }}">
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
										
										{{ '{{#if isFilterByClick}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byClick" id="byClick" checked="checked"}}' }}
										{{ '{{else}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byClick" id="byClick"}}' }}
										{{ '{{/if}}' }}
										<label for="byClick">Enviar a contactos que hayan hecho click en el siguiente enlace: </label><br />
										<div id="click" style="{{ '{{unbound filterClickChecked}}' }}">
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
										
										{{ '{{#if isFilterByExclude}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byExclude" id="byExclude" checked="checked"}}' }}
										{{ '{{else}}' }}
											{{ ' {{view Ember.RadioFilter name="RadioFilter" value="byExclude" id="byExclude"}}' }}
										{{ '{{/if}}' }}
										
										<label for="byExclude">No enviar a aquellos contactos que hayan abierto el siguiente correo electrónico: </label>
										<div id="exclude" style="{{ '{{unbound filterExcludeChecked}}' }}">
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
							<button class="btn btn-default  btn-sm extra-padding" {{'{{action "discardTarget" this}}'}}>Descartar cambios</button>
							<button class="btn btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
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

{{' {{view App.Select2 contentBinding="App.dbs" optionValuePath="content.id" optionLabelPath="content.name" }}' }}

{{ '{{/if}}' }}