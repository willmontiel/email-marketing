<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        {{ get_title() }}
        {{ stylesheet_link('bootstrap/css/bootstrap.css') }}
        {{ stylesheet_link('bootstrap/css/bootstrap-responsive.css') }}
        {{ stylesheet_link ('css/flat-ui.css') }}
        {{ stylesheet_link ('css/bootstrap-modal.css') }}
        {{ stylesheet_link ('css/prstyles.css') }} 
		{{ javascript_include ('js/app.js') }}
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