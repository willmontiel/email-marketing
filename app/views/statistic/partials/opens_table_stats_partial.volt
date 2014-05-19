<h4 class="sectiontitle">Detalle de aperturas</h4>
<div class="pull-right">
	<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/opens" class="btn btn-sm btn-default extra-padding">Descargar reporte</a>
</div>

<div class="space"></div>

<div class="row">
	<div class="col-md-12">
		<table class="table table-striped table-bordered">
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
		<div class="box-footer flat"> 
			{{ partial("partials/pagination_partial") }}
		</div>
	</div>
</div>
