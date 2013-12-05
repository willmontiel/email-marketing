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
<input type="radio" name="filter" id="byMail" class="icheck" value="byMail" />
<label for="byMail">Enviar a contactos que tenga el siguiente correo: </label><br />
<div id="mail" style="display: block;">
	<input type="email" name="sendByMail" id="sendMail" value="{{decoded.filter}}">
</div>

<input type="radio" name="filter" id="byOpen" class="icheck" value="byOpen" />
<label for="byOpen">Enviar a contactos que hayan abierto el siguiente correo electrónico: </label><br />
<div id="open" style="display: none;">
	<select multiple="multiple" name="sendByOpen[]" id="sendOpen" class="chzn-select">
		<option value="any">Cualquier correo enviado</option>
		<option value="week10">Boletin informativo semana 10</option>
		<option value="week11">Boletin informativo semana 11</option>
		<option value="week12">Boletin informativo semana 12</option>
	</select>
</div>

<input type="radio" name="filter" id="byClick" class="icheck" value="byClick" />
<label for="byClick">Enviar a contactos que hayan hecho click en el siguiente enlace: </label><br />
<div id="click" style="display: none;">
	<select multiple="multiple" name="sendByClick[]" id="sendClick" class="chzn-select">
		<option value="any">Cualquier correo enviado</option>
		<option value="week10">Boletin informativo semana 10</option>
		<option value="week11">Boletin informativo semana 11</option>
		<option value="week12">Boletin informativo semana 12</option>
	</select>
</div>

<input type="radio" name="filter" id="byExclude" class="icheck" value="byExclude" />
<label for="byExclude">No enviar a aquellos contactos que hayan abierto el siguiente correo electrónico: </label>
<div id="exclude" style="display: none;">
	<select multiple="multiple" name="excludeContact[]" id="sendExclude" class="chzn-select">
		<option value="any">Cualquier correo enviado</option>
		<option value="week10">Boletin informativo semana 10</option>
		<option value="week11">Boletin informativo semana 11</option>
		<option value="week12">Boletin informativo semana 12</option>
	</select>
</div>