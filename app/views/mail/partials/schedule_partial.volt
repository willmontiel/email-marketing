{{ '{{#unless isScheduleExpanded'}}}}
	<div {{'{{bind-attr class=": scheduleEmpty:bg-warning:"}}'}}>
		<div class="panel-body">
			<dl class="dl-horizontal" {{ '{{action "expandSchedule" this}}' }}>
				{{'{{#if scheduleEmpty }}'}}
					<dt>Enviar el correo el:</dt><dd> _______________________________</dd>
				{{'{{else}}'}}
					<dt>Enviar el correo el:</dt><dd>{{'{{scheduleSummary}}'}}</dd>
				{{'{{/if}}'}}
			</dl>
		</div>
	</div>
{{ '{{else}}' }}
	<h4 class="paneltitle">Programación del envío</h4>
	<form class="form-horizontal" role="form">
		<div class="form-group">
			<label class="col-sm-2 control-label">Envíar correo: </label>
			<div class="col-sm-10">
				{{ ' {{view Ember.RadioButton name="schedule" id="now" selectionBinding="scheduleRadio" value="now"}}' }}
				<label for="now">Ahora mismo:</label>
				<br /><br />
				{{'{{#if scheduleEmpty }}'}}
					{{ ' {{view Ember.RadioButton name="schedule" id="later" selectionBinding="scheduleRadio" value="later"}}' }}
				{{ '{{else}}' }}
					{{ ' {{view Ember.RadioButton name="schedule" id="later" selectionBinding="scheduleRadio" value="later" checked="checked"}}' }}
				{{ '{{/if}}' }}
				
				<label for="later">Seleccione la fecha:</label>
				{{'{{#if scheduleEmpty }}'}}
					<div id="programmer" style="display: none">
				{{ '{{else}}' }}
					<div id="programmer" style="display: block">			
				{{ '{{/if}}' }}
					<br />
					{{' {{view App.DateTimePicker valueBinding="scheduleDate" }}' }}
				</div>
			</div>
		</div>

		<div class="form-group text-right">
			<div class="col-sm-12">
				<button class="btn btn-default btn-sm extra-padding" {{'{{action "discardChanges" this}}'}}>Descartar cambios</button>
				<button class="btn btn-default btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
			</div>
		</div>
	</form>
{{ '{{/unless}}' }}