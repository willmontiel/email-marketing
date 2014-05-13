<div class="col-md-10 col-md-offset-1">
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Fecha y hora</td>
				<td>Dirección de correo</td>
			</tr>
		</thead>
		<tbody>
		{{'{{#each detailsData}}'}}
			<tr>
				<td>{{'{{date}}'}}</td>
				<td>{{'{{email}}'}}</td>
			</tr>
		{{ '{{/each}}' }}
		</tbody>
	</table>
	<hr>
	<div class="space"></div>
	<div class="box-footer flat"> 
		{{ partial("partials/pagination_partial") }}
	</div>
</div>
