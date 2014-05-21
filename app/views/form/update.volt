{% extends "templates/signin.volt" %}
{% block header_javascript %}
	{{ javascript_include('b3/js/jquery-1.9.1.js') }}
	{{ javascript_include('b3/js/bootstrap.js') }}
	{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js')}}
	{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
	{{ javascript_include('js/form_date_field.js') }}
{% endblock %}
{% block content %}
<div class="row">
	<div class="col-md-5">
		<h4 class="sectiontitle">{{elements['title']}}</h4>
		<form method="post" action="{{link}}" class="form-horizontal">
			{% for element in elements['fields'] %}
				<div class="form-group {{ element['hide'] }}">
					<div class="col-md-3">
						{{ element['label'] }}
					</div>
					<div class="col-md-7">
						{{ element['field'] }}
					</div>
				</div>
			{% endfor %}
			<div class="form-actions pull-right">
				<input type="submit" class="btn btn-sm btn-default btn-guardar extra-padding" value="{{elements['button']}}">
			</div>
		</form>
	</div>
</div>
{% endblock %}