{% extends "templates/index_new.volt" %}
{% block header_javascript %}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{apiurlbase.url}}';
	</script>
	{{ javascript_include('js/mixin_pagination.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('js/app_std.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('amcharts/amcharts.js')}}
	{{ javascript_include('amcharts/serial.js')}}
{% endblock %}
{% block sectiontitle %}Lista: <strong>lala</strong>{% endblock %}
{% block sectionsubtitle %}lala{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberApplistContainer">
		<script type="text/x-handlebars">
			{# Tabs de navegacion #}
			<div class="box">
				<div class="box-header">
					<ul class="nav nav-tabs nav-tabs-left">
						{{'{{#linkTo "apertures" tagName="li" href=false}}<a {{bindAttr href="view.href"}}> Aperturas</a>{{/linkTo}}'}}
						
					</ul>
				</div>
				<div class="box-content padded">
					<div class="tab-content">
						{{ "{{outlet}}" }}
					</div>
				</div>
			</div>
		</script>
		<script type="text/x-handlebars" data-template-name="apertures/index">
			lalalalal
		</script>
	</div>
{% endblock %}
