<div class="row">
	<div class="col-sm-4">
			<div class="select-target-title" style="background-color: #ddd;">
				<h4>Bases de datos</h4>
			</div>
		{% for dbase in dbases%}
			<div class="select-target" style="color: {{dbase.color}}">
				<span class="glyphicon glyphicon-hdd"></span>
				<span>{{dbase.name}}</span>
			</div>	
		{% endfor %}
	</div>
	<div class="col-sm-4"></div>
	<div class="col-sm-4"></div>
</div>	

<div class="row">
	<div class="principal"></div>
</div>
