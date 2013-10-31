{% set checkedDB = ''%} {% set displayDB = 'display: none;' %}
{% set checkedLC = ''%} {% set displayLC = 'display: none;' %}
{% set checkedSG = ''%} {% set displaySG = 'display: none;' %}

{% set decoded = mail.target|json_decode %}
{% if decoded.destination == 'dbases' %}
	{% set checkedDB = 'checked'%}
	{% set displayDB = 'display: block;' %}
{% elseif decoded.destination == 'contactlists' %}
	{% set checkedLC = 'checked'%}
	{% set displayLC = 'display: block;' %}
{% elseif decoded.destination == 'segments' %}
	{% set checkedSG = 'checked'%}
	{% set displaySG = 'display: block;' %}
{% endif %}
	<input type="radio" name="radios" class="icheck" {{checkedDB}} value="0" id="dbRadio" >
	<label for="dbRadio">Base de datos de contactos</label> <br />
	<div id="db" style="{{displayDB}}">
		<select multiple="multiple" name="dbases[]"  id="dbSelect" class="chzn-select">
			{% for dbase in dbases %}
				<option value="{{dbase.idDbase}}" {% for id in decoded.ids%}{% if decoded.destination =='dbases' and id == dbase.idDbase %}selected{% endif %}{% endfor %}>{{dbase.name}}</option>
			{% endfor %}
		</select>
	</div>
<br />
<input type="radio" name="radios" class="icheck" {{checkedLC}} value="1" id="listRadio">
<label for="listRadio">Lista de contactos </label>
<div id="list" style="{{displayLC}}">
	<select multiple="multiple" name="contactlists[]" id="listSelect" class="chzn-select">
		{% for contactlist in contactlists %}
			<option value="{{contactlist.idContactlist}}" {% for id in decoded.ids%}{% if decoded.destination =='contactlists' and id == contactlist.idContactlist %}selected{% endif %}{% endfor %}>{{contactlist.name}},  {{contactlist.Dbase}}</option>
		{% endfor %}
	</select>
</div>
<br /><br />
<input type="radio" name="radios" class="icheck" {{checkedSG}} value="2" id="segmentRadio">
<label for="segmentRadio">Segmentos</label>
<br />
<div id="seg" style="{{displaySG}}">
	<select multiple="multiple" name="segments[]" id="segSelect" class="chzn-select">
		{% for segment in segments %}
			<option value="{{segment.idSegment}}" {% for id in decoded.ids%}{% if decoded.destination =='segments' and id == segment.idSegment %}selected{% endif %}{% endfor %}>{{segment.name}}</option>
		{% endfor %}
	</select>
</div>