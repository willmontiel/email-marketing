<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
			  <h3 class="panel-title">Envíe el correo ahora, programelo para que se envíde déspues</h3>
			</div>
			<div class="panel-body">
				{% if mail is not defined or mail.scheduleDate == null %}
					{% set display = 'display: none;'%}
					{% set checked = '' %}
					{% set value = ''%}
				{% else %}
					{% set display = 'display: block;'%}
					{% set checked = 'checked' %}
					{% set value = date('d/m/Y G:i', mail.scheduleDate) %}
				{% endif %}
				<form class="form-horizontal" role="form">
					<div class="form-group">
						<label class="col-sm-2 control-label">Envíar correo: </label>
						<div class="col-sm-10">
							<input type="radio" name="schedule" value="now" id="now">
							<label for="now">De inmediato:</label>
							<br /><br />

							<input type="radio" name="schedule" {{checked}} value="later" id="later">
							<label for="later">En la siguiente fecha:</label>
							<div id="programmer" style="{{display}}">
								<br />
								{{' {{ view App.DateTimePicker }}' }}
							</div>
						</div>
					</div>

					<div class="form-group text-right">
						<a href="#" class="btn btn-default">Descartar cambios</a>
						<button class="btn btn-blue" {{'{{action "save" this}}'}}>Aplicar cambios</button>
					</div>
				</form>
			</div>
		</div>	
	</div>
</div>