<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('bootstrap/css/bootstrap-responsive.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
       
        {{ javascript_include('js/jquery-1.8.3.min.js') }}
        {{ javascript_include('bootstrap/js/bootstrap.js') }}
        {{ javascript_include('js/jquery-ui-1.10.3.custom.min.js') }}
        {{ javascript_include('js/jquery.ui.touch-punch.min.js') }}
        {{ javascript_include('js/bootstrap.min.js') }}
        {{ javascript_include('js/bootstrap-select.js') }}
        {{ javascript_include('js/bootstrap-switch.js') }}
        {{ javascript_include('js/flatui-checkbox.js') }}
        {{ javascript_include('js/flatui-radio.js') }}
        {{ javascript_include('js/jquery.tagsinput.js') }}
        {{ javascript_include('js/jquery.placeholder.js') }}
        {{ javascript_include('js/jquery.stacktable.js') }}
        {{ javascript_include('js/application.js') }}
        {{ javascript_include('js/bootstrap-modal.js') }}
        {{ javascript_include('js/bootstrap-modalmanager.js') }}
		{{ javascript_include('js/app.js') }}
		{{ javascript_include ('js/libs/ember-1.0.0-rc.6.1.js') }}
		{{ javascript_include ('js/libs/handlebars-1.0.0-rc.4.js') }}
		{{ javascript_include ('js/libs/jquery-1.9.1.js') }}
		
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Proyect">
        <meta name="author" content="Will">
		
	</head>
	<body>
		{% block content %}{% endblock %}
    </body>
</html>
