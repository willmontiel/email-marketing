<div class="container-fluid">
	<div class="col-md-8 col-md-offset-2">
		<table class="table table-normal">
			<tr class="big-number">
				<td>{{compare1.Cactive|numberf}}</td>
				<td></td>
				<td>Contactos</td>
				<td></td>
				<td>{{compare2.Cactive|numberf}}</td>
			</tr>
			<tr class="big-number">
				<td>{{statisticsData1.sent|numberf}}</td>
				<td></td>
				<td>Correos enviados</td>
				<td></td>
				<td>{{statisticsData2.sent|numberf}}</td>
			</tr>
			<tr class="opens big-number">
				<td>{{statisticsData1.uniqueOpens|numberf}}</td>
				<td>{{statisticsData1.percentageUniqueOpens}}%</td>
				<td>Aperturas</td>
				<td>{{statisticsData2.percentageUniqueOpens}}%</td>
				<td>{{statisticsData2.uniqueOpens|numberf}}</td>
			</tr>
			<tr class="clics big-number">
				<td>{{statisticsData1.clicks|numberf}}</td>
				<td>{#{{statisticsData1.statclicks}}%0%#}</td>
				<td>Clics</td>
				<td>{#{{statisticsData2.statclicks}}%0%#}</td>
				<td>{{statisticsData2.clicks|numberf}}</td>
			</tr>
			<tr class="unsubs big-number">
				<td>{{statisticsData1.unsubscribed|numberf}}</td>
				<td>{{statisticsData1.percentageUnsubscribed}}%</td>
				<td>Desuscritos</td>
				<td>{{statisticsData2.percentageUnsubscribed}}%</td>
				<td>{{statisticsData2.unsubscribed|numberf}}</td>
			</tr>
			<tr class="bounced big-number">
				<td>
					{{statisticsData1.bounced|numberf}}
				</td>
				<td>
					{{statisticsData1.percentageBounced}}%
				</td>
				<td>
					Rebotes
				</td>
				<td>
					{{statisticsData2.percentageBounced}}%
				</td>
				<td>
					{{statisticsData2.bounced|numberf}}
				</td>
			</tr>
			<tr class="spam big-number">
				<td>
					{{statisticsData1.spam|numberf}}
				</td>
				<td>
					{{statisticsData1.percentageSpam}}%
				</td>
				<td>
					Spam
				</td>
				<td>
					{{statisticsData2.percentageSpam}}%
				</td>
				<td>
					{{statisticsData2.spam|numberf}}
				</td>
			</tr>
		</table>
	</div>
</div>
