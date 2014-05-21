<div class="clearfix"></div>
<div class="space"></div>

<h4 class="sectiontitle">Detalle de clics</h4>

<div class="row">
	<div class="col-sm-4">
		<div class="pull-left">
			{{ '{{view Ember.Select
					contentBinding="selectedLink"
					valueBinding="linkSelected"
					class="form-control"}}'
			}}
		</div>
	</div>
	<div class="col-sm-6"></div>	
	<div class="col-sm-2 text-right">
		<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/clicks" class="btn btn-sm btn-default extra-padding">Descargar reporte</a>
	</div>
</div>

<div class="space"></div>

<div class="row">
	<div class="col-sm-12">
		<table class="table table-striped table-bordered">
			<thead>
				<tr>
					<td class="col-sm-3">Dirección de correo</td>
					<td class="col-sm-7">Enlace</td>
					<td class="col-sm-2">Fecha y hora</td>
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

		<div class="box-footer flat"> 
			{{ partial("partials/pagination_partial") }}
		</div>
	</div>
</div>
