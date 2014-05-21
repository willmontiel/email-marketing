<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<table class="table table-normal">
			<tr class="big-number">
				<td>{{mail1.totalContacts}}</td>
				<td></td>
				<td>Correos enviados</td>
				<td></td>
				<td>{{mail2.totalContacts}}</td>
			</tr>
			<tr class="opens big-number">
				<td>
					{{statisticsData1.uniqueOpens}}
				</td>
				<td>
					{{statisticsData1.percentageUniqueOpens}}%
				</td>
				<td>
					Aperturas
				</td>
				<td>
					{{statisticsData2.percentageUniqueOpens}}%
				</td>
				<td>
					{{statisticsData2.uniqueOpens}}
				</td>
			</tr>
			<tr class="clics big-number">
				<td>
					{{statisticsData1.clicks}}
				</td>
				<td>
						{#{{statisticsData1.statclicks}}%0%#}
				</td>
				<td>
					Clics
				</td>
				<td>
						{#{{statisticsData2.statclicks}}%0%#}
				</td>
				<td>
					{{statisticsData2.clicks}}
				</td>
			</tr>
			<tr class="unsubs big-number">
				<td>
					{{statisticsData1.unsubscribed}}
				</td>
				<td>
					{{statisticsData1.percentageUnsubscribed}}%
				</td>
				<td>
					Desuscritos
				</td>
				<td>
					{{statisticsData2.percentageUnsubscribed}}%
				</td>
				<td>
					{{statisticsData2.unsubscribed}}
				</td>
			</tr>
			<tr class="bounced big-number">
				<td>
					{{statisticsData1.bounced}}
				</td>
				<td>
					{{statisticsData1.percentageSpam}}%
				</td>
				<td>
					Rebotes
				</td>
				<td>
					{{statisticsData2.percentageSpam}}%
				</td>
				<td>
					{{statisticsData2.bounced}}
				</td>
			</tr>
		</table>
	</div>
</div>