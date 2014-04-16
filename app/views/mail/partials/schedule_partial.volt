{{ '{{#unless isScheduleExpanded'}}}}
	<div {{'{{bind-attr class=":bs-callout scheduleEmpty:bs-callout-warning:bs-callout-success"}}'}}>
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
{{ '{{/unless}}' }}

{{ '{{#if isScheduleExpanded}}' }}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title">Envíe el correo ahora o programelo para que se envíe déspues</h3>
				</div>
				<div class="panel-body">
					{#
					{% if mail is not defined or mail.scheduleDate == null %}
						{% set display = 'display: none;'%}
						{% set checked = '' %}
						{% set value = ''%}
					{% else %}
						{% set display = 'display: block;'%}
						{% set checked = 'checked' %}
						{% set value = date('d/m/Y G:i', mail.scheduleDate) %}
					{% endif %}
					#}
					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label class="col-sm-2 control-label">Envíar correo: </label>
							<div class="col-sm-10">
								{{ ' {{view Ember.RadioButton name="schedule" id="now" selectionBinding="scheduleRadio" value="now"}}' }}
								<label for="now">De inmediato:</label>
								<br /><br />
								{{ ' {{view Ember.RadioButton name="schedule" id="later" selectionBinding="scheduleRadio" value="later"}}' }}
								{#
								<input type="radio" name="schedule" value="now" id="now">
								#}
								
								
								{#
								<input type="radio" name="schedule" {{checked}} value="later" id="later">
								#}
								<label for="later">En la siguiente fecha:</label>
								<div id="programmer" style="">
									<br />
									{{' {{ view App.DateTimePicker valueBinding="scheduleDate" id="scheduleDate"}}' }}
								</div>
							</div>
						</div>

						<div class="form-group text-right">
							<div class="col-sm-12">
								<button class="btn btn-default" {{'{{action "discardSchedule" this}}'}}>Descartar cambios</button>
								<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
							</div>
						</div>
					</form>
				</div>
			</div>	
		</div>
	</div>
{{ '{{/if}}' }}