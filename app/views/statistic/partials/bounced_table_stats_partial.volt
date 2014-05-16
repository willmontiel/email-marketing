{{ '{{#if bouncedData}}' }}
	<h4 class="sectiontitle">Detalle de rebotes</h4>
	<div class="row">
		<div class="col-sm-6">
			<div class="pull-left scaleChart">
				<div class="pull-left">
					Filtrar por: &nbsp;
				</div>
				<div class="pull-left">
					<label for="typeFilter">
						{{'{{view Ember.RadioButton id="typeFilter" name="filterBounced" selectionBinding="bouncedFilter" value="type" checked="checked"}}'}}
						Tipo &nbsp;
					</label>
				</div>
				<div class="pull-left">
					<label for="categoryFilter">
						{{'{{view Ember.RadioButton id="categoryFilter" name="filterBounced" selectionBinding="bouncedFilter" value="category"}}'}}
						Categoria &nbsp;
					</label>
				</div>
				<div class="pull-left">
					<label for="domainFilter">
						{{'{{view Ember.RadioButton id="domainFilter" name="filterBounced" selectionBinding="bouncedFilter" value="domain"}}'}}
						Dominio &nbsp;
					</label>
				</div>
			</div>
		</div>
		<div class="col-sm-4"></div>
		<div class="col-sm-2 text-right">
			<a href="{{url('statistic/downloadreport')}}/{{mail.idMail}}/bounced" class="btn btn-sm btn-default btn-sm extra-padding">Descargar reporte</a>
		</div>
	</div>

	<div class="row">
		<div class="col-md-5">
			{{ '{{view Ember.Select
					contentBinding="selectedType"
					valueBinding="typeSelected"
					class="form-control"}}'
			}}
		</div>
	</div>

	<div class="space"></div>

	<div class="row">
		<div class="col-md-12">
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<td>Dirección de correo</td>
						<td>Fecha y hora</td>
						<td>Tipo</td>
						<td>Categoria</td>
					</tr>
				</thead>
				<tbody>
				{{'{{#each detailsData}}'}}
					<tr>
						<td>{{'{{email}}'}}</td>
						<td>{{'{{date}}'}}</td>
						<td>{{'{{type}}'}}</td>
						<td>{{'{{category}}'}}</td>
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
	</div>
{{ '{{else}}' }}