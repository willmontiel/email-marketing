{% extends "templates/index_new.volt" %}
{% block sectiontitle %}<i class="icon-envelope-alt icon-2x"></i>Bienvenido a Mail Station{% endblock %}
{%block sectionsubtitle %}Su sistema de marketing digital{% endblock %}
{% block content %}
{{flashSession.output()}}
	<div class="row-fluid">
		<div class="span2">
			<div class="box-dashboard-summary summary-opens">
				<div class="number-stats-dashboard-summary">
					{{values['opens']}}
				</div>
				<div class="sparkline big" data-color="green">
					<!--
					{%for statvalue in statvalues%}
						{{statvalue['opens']}},
					{%endfor%}
					-->
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="box-dashboard-summary summary-clicks">
				<div class="number-stats-dashboard-summary">
					{{values['clicks']}}
				</div>
				<div class="sparkline big" data-color="blue">
					<!--
					{%for statvalue in statvalues%}
						{{statvalue['clicks']}},
					{%endfor%}
					-->
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="box-dashboard-summary summary-unsubscribed">
				<div class="number-stats-dashboard-summary">
					{{values['unsubscribed']}}
				</div>
				<div class="sparkline big" data-color="white">
					<!--
					{%for statvalue in statvalues%}
						{{statvalue['unsubscribed']}},
					{%endfor%}
					-->
				</div>
			</div>
		</div>
		<div class="span2">
			<div class="box-dashboard-summary summary-bounced">
				<div class="number-stats-dashboard-summary">
					{{values['bounced']}}
				</div>
				<div class="sparkline big" data-color="orange">
					<!--
					{%for statvalue in statvalues%}
						{{statvalue['bounced']}},
					{%endfor%}
					-->
				</div>
			</div>
		</div>
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
					{%for mail in lastmails%}
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