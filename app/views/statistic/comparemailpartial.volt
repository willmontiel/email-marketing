<script type="text/x-handlebars" data-template-name="compare/opens">
	<div class="row-fluid">
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-search"></i> Resumen de aperturas {{'{{data1.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-green-left"></td>
							<td class="border-radious-green-center">{{'{{data1.quantity}}'}}</td>
							<td class="border-radious-green-center">|</td>
							<td class="border-radious-green-right">{{'{{data1.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-search"></i> Resumen de aperturas {{'{{data2.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-green-left"></td>
							<td class="border-radious-green-center">{{'{{data2.quantity}}'}}</td>
							<td class="border-radious-green-center">|</td>
							<td class="border-radious-green-right">{{'{{data2.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			{{'{{view App.TimeGraphView idChart="openBarChartCompareContainer" typeChart="Bar" textChart="Aperturas de Correo"}}'}}
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="compare/clicks">
	<div class="row-fluid">
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-hand-up"></i> Resumen de clics {{'{{data1.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-cyan-left"></td>
							<td class="border-radious-cyan-center">{{'{{data1.quantity}}'}}</td>
							<td class="border-radious-cyan-center">|</td>
							<td class="border-radious-cyan-right">{{'{{data1.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-hand-up"></i> Resumen de clics  {{'{{data2.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-cyan-left"></td>
							<td class="border-radious-cyan-center">{{'{{data2.quantity}}'}}</td>
							<td class="border-radious-cyan-center">|</td>
							<td class="border-radious-cyan-right">{{'{{data2.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			{{'{{view App.TimeGraphView idChart="clickBarChartCompareContainer" typeChart="Bar" textChart="Clics de Correo"}}'}}
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="compare/unsubscribed">
	<div class="row-fluid">
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-minus-sign"></i> Resumen de des-suscritos {{'{{data1.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-gray-left"></td>
							<td class="border-radious-gray-center">{{'{{data1.quantity}}'}}</td>
							<td class="border-radious-gray-center">|</td>
							<td class="border-radious-gray-right">{{'{{data1.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-minus-sign"></i> Resumen de des-suscritos {{'{{data2.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-gray-left"></td>
							<td class="border-radious-gray-center">{{'{{data2.quantity}}'}}</td>
							<td class="border-radious-gray-center">|</td>
							<td class="border-radious-gray-right">{{'{{data2.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			{{'{{view App.TimeGraphView idChart="unsubscribedBarChartCompareContainer" typeChart="Bar" textChart="Des-suscritos de Correo"}}'}}
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="compare/bounced">
	<div class="row-fluid">
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-warning-sign"></i> Resumen de rebotes {{'{{data1.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-scarlet-left"></td>
							<td class="border-radious-scarlet-center">{{'{{data1.quantity}}'}}</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-right">{{'{{data1.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-warning-sign"></i> Resumen de rebotes  {{'{{data2.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-scarlet-left"></td>
							<td class="border-radious-scarlet-center">{{'{{data2.quantity}}'}}</td>
							<td class="border-radious-scarlet-center">|</td>
							<td class="border-radious-scarlet-right">{{'{{data2.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			{{'{{view App.TimeGraphView idChart="bouncedBarChartCompareContainer" typeChart="Bar" textChart="Rebotes de Correo"}}'}}
		</div>
	</div>
</script>

<script type="text/x-handlebars" data-template-name="compare/spam">
	<div class="row-fluid">
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-remove"></i> Resumen de spam {{'{{data1.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-red-left"></td>
							<td class="border-radious-red-center">{{'{{data1.quantity}}'}}</td>
							<td class="border-radious-red-center">|</td>
							<td class="border-radious-red-right">{{'{{data1.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
		<div class="news span4">
			<label class="label-gray-light-percent"><i class="icon-remove"></i> Resumen de spam  {{'{{data2.name}}'}}</label>
			<div>
				<label class="label-open-percent">
					<table>
						<tr>
							<td class="border-radious-red-left"></td>
							<td class="border-radious-red-center">{{'{{data2.quantity}}'}}</td>
							<td class="border-radious-red-center">|</td>
							<td class="border-radious-red-right">{{'{{data2.percent}}'}}%</td>
						</tr>
					</table>
				</label>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span12">
			{{'{{view App.TimeGraphView idChart="spamBarChartCompareContainer" typeChart="Bar" textChart="Reportes de Spam"}}'}}
		</div>
	</div>
</script>