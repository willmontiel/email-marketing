<div class="space"></div>

<div class="text-right">
	<button class="btn btn-sm btn-add extra-padding">Compartir estadísticas</button>
</div>

<div class="clearfix"></div>
<div class="space"></div>

<h4 class="sectiontitle">Detalle de aperturas</h4>
<div class="pull-right">
	<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/opens" class="btn btn-sm btn-default extra-padding">Descargar reporte</a>
</div>

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
