{% extends "templates/index.volt" %}

{% block content %}
<div class="row-fluid">
	<div class="span5">
		<table class="contact-info">
			<tbody>
				<tr>
					<td>{{ row['col1'] }}</td>
					<td>
						<select id="col1" name="col1">
							<option value="email">Email</option>
							<option value="name">Nombre</option>
							<option value="lastname">Apellido</option>
						{% for field in customfields %}
							<option value="{{field.idCustomField}}">{{field.name}}</option>
						{%endfor%}
						</select>
					</td>
				</tr>
				<tr>
					<td>{{ row['col2'] }}</td>
					<td>
						<select id="col1" name="col1">
							<option value="email">Email</option>
							<option value="name">Nombre</option>
							<option value="lastname">Apellido</option>
						{% for field in customfields %}
							<option value="{{field.idCustomField}}">{{field.name}}</option>
						{%endfor%}
						</select>
					</td>
				</tr>
				<tr>
					<td>{{ row['col3'] }}</td>
					<td>
						<select id="col1" name="col1">
							<option value="email">Email</option>
							<option value="name">Nombre</option>
							<option value="lastname">Apellido</option>
						{% for field in customfields %}
							<option value="{{field.idCustomField}}">{{field.name}}</option>
						{%endfor%}
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
{% endblock %}