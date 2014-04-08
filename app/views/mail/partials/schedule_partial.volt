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
			<br />
			
			<input type="radio" name="schedule" {{checked}} value="later" id="later">
			<label for="later">En la siguiente fecha:</label>
			<div id="programmer" style="{{display}}">
				<br />
				<div id="date">
					<div id="schedule" class="input-append date col-sm-4">
						<input type="text" name="scheduleDate" id="date" class="form-control" value="{{value}}">
					</div>
				</div>
			</div>
		</div>
	</div>
		
	<div class="form-group text-right">
		<a href="#" class="btn btn-default">Descartar cambios</a>
		<a href="#" class="btn btn-primary">Aplicar cambios</a>
	</div>
</form>