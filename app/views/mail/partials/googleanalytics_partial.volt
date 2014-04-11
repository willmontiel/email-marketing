{% if links|length !== 0 %}
	<input type="checkbox" name="googleAnalytics" value="googleAnalytics" {{checked}} id="googleAnalytics">
	Agregar seguimiento de Google Analytics a los siguientes enlaces: <br /><br />
	{% if analytics !== null %}
		<div id="allLinks" style="display: none;">
			<label>Nombre de campaña: </label>
			<input type="text" name="campaignName" autofocus="autofocus" value="{{campaignName}}" class="span10"> <br />

			<label>Enlaces: </label>
			<select multiple="multiple" name="links[]"  id="links" class="chzn-select">
				{% for link in links%}
					 <option value="{{link}}" {% for analytic in analytics%}{% if analytic == link %}selected{% endif %}{% endfor %}>{{link}}</option>
				{% endfor %}
			</select>
		</div>
	{% else %}
		<div id="allLinks" style="display: none;">
			<label>Nombre de campaña: </label>
			<input type="text" name="campaignName" value="{{mail.name}}" autofocus="autofocus" class="span10"> <br />

			<label>Enlaces: </label>
			<select multiple="multiple" name="links[]"  id="links" class="chzn-select">
				{% for link in links%}
					<option value="{{link}}">{{link}}</option>
				{% endfor %}
			</select>
		</div>
	{% endif%}
{% else %}
	No se encontrarón enlaces, si desea agregar seguimiento de Google Analytics, por favor agregue por lo menos uno.
{% endif %}