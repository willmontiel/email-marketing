<div {{'{{bind-attr class=":bs-callout headerEmpty:bs-callout-warning:bs-callout-success"}}'}}>
	<div class="panel-body">
		{{'{{#if headerEmpty }}'}}
			<p><strong>Google Analytics:</strong> _______________________________</p>
			<label style="cursor: pointer;" {{ '{{action "expandGA" this}}' }}>Click aqui para configurar</label>
		{{'{{else}}'}}
			<p><strong>Google Analytics:</strong> _______________________________</p>
			<label style="cursor: pointer;" {{ '{{action "expandGA" this}}' }}>Click aqui para configurar</label>
		{{'{{/if}}'}}
	</div>
</div>

{{ '{{#if isGAExpanded}}' }}
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading">
				  <h3 class="panel-title">Configure google analytics con los enlaces que haya insertado en el contenido correo</h3>
				</div>
				<div class="panel-body">
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
				</div>
			</div>
		</div>
	</div>
{{ '{{/if}}' }}