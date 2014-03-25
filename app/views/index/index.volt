{% extends "templates/index_new.volt" %}
{% block content %}
{{flashSession.output()}}
	<div class="row-fluid">
		{%for widget in stats.fullPeriodStats()%}
		<div class="span2">
			<div class="box-dashboard-summary summary-opens">
				<div class="title-stats-dashboard-summary">
					{{widget.getTitle()}}
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				<div class="sparkline big" data-color="green">
					<!--
					{%for statvalue in widget.getSecondaryValues()%}
						{{statvalue.value}},
					{%endfor%}
					-->
				</div>
			</div>
		</div>
		{%endfor%}
	</div>
<br />
<br />
	<div class="row-fluid">
		{%for widget in stats.fullSocialStats()%}
		<div class="span2">
			<div class="box-dashboard-summary summary-fb">
				<div>
					<p>{{widget.getTitle()}}</p>
				</div>
				<div class="number-stats-dashboard-summary">
					{{widget.getTotal()}}
				</div>
				{%for value in widget.getSecondaryValues()%}
					<div class="title-stats-dashboard-summary">
						{{value.name}}
					</div>
					<div class="number-stats-dashboard-summary">
						{{value.value}}
					</div>
				{%endfor%}
			</div>
		</div>
		{%endfor%}
	</div>
<br />
<br />
<br />
<br />
	<div class="row-fluid">
		<div class="span8">
			<table class="table table-normal">
				<thead>
					<tr>
						<td>Ultimos 3 Envios</td>
						<td>Aperturas</td>
						<td>Clics</td>
						<td>Desuscripciones</td>
						<td>Rebotes</td>
					</tr>
				</thead>
				<tbody>
					{%for mail in stats.getLastMailsWithStats()%}
						<tr>
							<td>{{mail.name}}</td>
							<td>{{mail.uniqueOpens}}</td>
							<td>{{mail.clicks}}</td>
							<td>{{mail.unsubscribed}}</td>
							<td>{{mail.bounced}}</td>
						</tr>
					{%endfor%}
				</tbody>
			</table>
		</div>
	</div>
{% endblock %}