{% extends "templates/index.volt" %}

{% block content %}
<div class="row-fluid">
	<div class="span8">
		<h2>Importar contactos</h2>
	</div>
</div>
<br>
<div class="row-fluid">
	<div class="span12">
		<p>	
			Esta función le permite importar muchos contactos desde un archivo CSV.
		</p>
	</div>
</div>
<br><br>
<div class="row-fluid">
	<div class="span5">
		<table class="contact-info">
			<tbody>
				<tr>
					<td>{{ row['row1'] }} &nbsp;</td>
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
					<td>{{ row['row2'] }}</td>
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
					<td>{{ row['row3'] }}</td>
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