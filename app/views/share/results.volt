{% extends "templates/index_b3.volt" %}
{% block header_javascript %}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ super() }}
	{{ partial("partials/ember_partial") }}
	<script type="text/javascript">
		var MyDbaseUrl = '{{urlManager.getApi_v1_2Url() ~ '/mail/public/' ~ mail.idMail }}';
	</script>
	{{ javascript_include('js/mixin_pagination_statistics.js') }}
	{{ javascript_include('js/mixin_config.js') }}
	{{ javascript_include('javascripts/moment/moment-with-langs.min.js') }}
	{{ javascript_include('js/app_statistics.js') }}
	{{ javascript_include('js/app_charts.js') }}
	{{ javascript_include('highcharts/highcharts.js')}}
	{{ javascript_include('highcharts/modules/exporting.js')}}
	{{ javascript_include('js/select2.js') }}
	{{ stylesheet_link('css/statisticStyles.css') }}
	{{ stylesheet_link ('css/select2.css') }}
	<script>
		function autoScroll() {
			$('html, body').animate({scrollTop: '615px'}, 'slow');
		}
		
		function expandMailPreview() {
			if ($("#mail-preview").is(":visible")) {
				$('#mail-preview').hide("slow");
			}
			else {
				$('#mail-preview').show("slow");
			}
		}
		
		function autoScroll() {
			event.preventDefault();
			
			var n = $(document).height();
			$('html, body').animate({ scrollTop: 2000 }, 'slow');
		}
	</script>
{% endblock %}
{% block content %}
	<!------------------ Ember! ---------------------------------->
	<div id="emberAppstatisticsContainer">
		<script type="text/x-handlebars">
			{{ partial("statistic/partials/header_partial") }}
				
			{{ partial("statistic/partials/preview_email_partial") }}
			
			{#
				<div id="container" style="width: 300px; height: 250px;"></div>
			#}
			{{ partial("statistic/partials/general_stats_partial") }}
				
			{{ partial("statistic/partials/social_media_stats_partial") }}
				
			{% if type == 'complete'%}
				{{ partial("statistic/partials/partial_statistics_nav") }}
			{% endif %}
			{{ "{{outlet}}" }}
		</script>
		
		{{ partial("statistic/partials/partial_ember_details") }}
		{{ partial("statistic/partials/partial_graph") }}
	</div>
{% endblock %}
