<div class="col-md-10 col-md-offset-1">
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Dirección de correo</td>
				<td>Nombre</td>
				<td>Apellido</td>
				<td>Fecha y hora</td>
			</tr>
		</thead>
		<tbody>
		{{'{{#each detailsData}}'}}
			<tr>
				<td>{{'{{email}}'}}</td>
				<td>{{'{{name}}'}}</td>
				<td>{{'{{lastname}}'}}</td>
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
