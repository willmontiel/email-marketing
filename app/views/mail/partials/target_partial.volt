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
			<div class="row">
				<div id="panel-container">
				</div>
			</div>
				
			<div class="row">
				<div class="col-sm-6 col-md-offset-6 text-right">
					<button class="btn btn-default  btn-sm extra-padding" {{'{{action "discardTarget" this}}'}}>Descartar cambios</button>
					<button class="btn btn-guardar btn-sm extra-padding" {{'{{action "save" this}}'}}>Aplicar cambios</button>
				</div>
			</div>
			<br />
		</div>
	</div>
	{{ '{{#view App.target}}' }}
	{{ '{{/view}}' }}
{{ '{{/if}}' }}