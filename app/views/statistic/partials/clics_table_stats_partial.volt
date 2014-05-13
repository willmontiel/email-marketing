<div class="col-md-10 col-md-offset-1">
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Vínculos</td>
				<td>Total Clics</td>
				{#<td>Total Clics únicos</td>#}
			</tr>
		</thead>
		<tbody>
			{{'{{#each detailsLinks}}'}}
			<tr>
				<td>{{'{{link}}'}}</td>
				<td>{{'{{total}}'}}</td>
				{#<td>{{'{{uniques}}'}}</td>#}
			</tr>
			{{ '{{/each}}' }}
		</tbody>
	</table>
</div>

<div class="space"></div>

<div class="col-md-10 col-md-offset-1">
	<div class="row">
		<div class="col-md-5">
			<div class="pull-left">
				{{ '{{view Ember.Select
						contentBinding="selectedLink"
						valueBinding="linkSelected"
						class="form-control"}}'
				}}
			</div>
		</div>
	</div>

	<table class="table table-striped">
		<thead>
			<tr>
				<td>Dirección de correo</td>
				<td>Enlace</td>
				<td>Fecha y hora</td>
			</tr>
		</thead>
		<tbody>
		{{'{{#each detailsData}}'}}
			<tr>
				<td>{{'{{email}}'}}</td>
				<td>{{'{{link}}'}}</td>
				<td>{{'{{date}}'}}</td>
			</tr>
		{{ '{{/each}}' }}
		</tbody>
	</table>
	<div class="space"></div>
	<hr>
		
	<div class="box-footer flat"> 
		{{ partial("partials/pagination_partial") }}
	</div>
</div>
