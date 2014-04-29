{# Nuevo template usando Bootstrap 3 #}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
		<!-- Always force latest IE rendering engine or request Chrome Frame -->
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">

		{{ stylesheet_link('b3/css/bootstrap.css') }}
		{{ stylesheet_link('b3/css/font-awesome.css') }}
		{{ stylesheet_link('b3/css/sm-email-theme.css') }}
		{{ stylesheet_link('b3/vendors/css/bootstrap-editable.css') }}
		{{ stylesheet_link('datetime_picker_jquery/jquery.datetimepicker.css') }}
		{{ stylesheet_link('css/prstyles.css') }}

		<!--[if lt IE 9]>
		{{ javascript_include('javascripts/vendor/html5shiv.js') }}
		{{ javascript_include('javascripts/vendor/excanvas.js') }}
		<![endif]-->

		{{ javascript_include('b3/js/jquery-1.9.1.js') }}
		{{ javascript_include('b3/js/bootstrap.js') }}
		{{ javascript_include('b3/vendors/js/jquery.sparkline.js') }}
		{{ javascript_include('b3/vendors/js/spark_auto.js') }}
		{{ javascript_include('b3/vendors/js/bootstrap-editable.js') }}
		{{ javascript_include('b3/vendors/js/jquery.gritter.js') }}
		{{ javascript_include('datetime_picker_jquery/jquery.datetimepicker.js')}}
		{{ javascript_include('js/form_date_field.js') }}
	</head>
	<body>
		<div class="container">
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
		</div>
	</body>
</html>