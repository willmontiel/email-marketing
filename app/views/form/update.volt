{% extends "templates/signin.volt" %}
{% block header_javascript %}
	{{ javascript_include('vendors/bootstrap_v3/js/jquery-1.9.1.js') }}
	{{ javascript_include('vendors/bootstrap_v3/js/bootstrap.js') }}
	{{ javascript_include('vendors/datetime_picker_jquery/jquery.datetimepicker.js')}}
	{{ stylesheet_link('vendors/datetime_picker_jquery/jquery.datetimepicker.css') }}
	{{ javascript_include('js/form_date_field.js') }}
{% endblock %}
{% block content %}
<nav class="navbar navbar-default">
	<div class="container-fluid">
		<div class="navbar-header">
			<a class="navbar-brand" href="{{url('')}}">Email Sigma</a>
		</div>
	</div>
</nav>
<div class="container">
	<div class="row">
		<div class="col-md-offset-3 col-md-7 col-sm-offset-3 col-sm-7 col-xs-offset-3 col-xs-7">
			<h4 class="sectiontitle">{{elements['title']}}</h4>
			<form method="post" action="{{link}}" class="form-horizontal">
				{% for element in elements['fields'] %}
					<div class="form-group {{ element['hide'] }}">
						<div class="col-md-3 col-sm-3">
							{{ element['label'] }}
						</div>
						<div class="col-md-8 col-sm-8">
							{{ element['field'] }}
						</div>
					</div>
				{% endfor %}
				<div class="form-actions col-md-offset-8 col-sm-offset-8 col-xs-offset-8">
					<input type="submit" class="btn btn-sm btn-default btn-guardar extra-padding" value="{{elements['button']}}">
				</div>
			</form>
		</div>
	</div>
</div>
{% endblock %}