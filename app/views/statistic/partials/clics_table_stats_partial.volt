<div class="space"></div>
<div class="text-right">
	<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
</div>
<div class="clearfix"></div>
<div class="space"></div>

<h4 class="sectiontitle">Clics por enlance</h4>
<div class="col-md-10 col-md-offset-1">
	<table class="table table-striped">
		<thead>
			<tr>
				<td>Vínculos</td>
				<td>Total Clics</td>
			</tr>
		</thead>
		<tbody>
			{{'{{#each detailsLinks}}'}}
			<tr>
				<td>{{'{{link}}'}}</td>
				<td>{{'{{total}}'}}</td>
			</tr>
			{{ '{{/each}}' }}
		</tbody>
	</table>
</div>

<div class="clearfix"></div>
<div class="space"></div>

<h4 class="sectiontitle">Detalle de clics</h4>
<div class="pull-right">
	<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/clicks" class="btn btn-sm btn-default extra-padding">Descargar reporte</a>
</div>

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
