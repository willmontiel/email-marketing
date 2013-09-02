<script type="text/x-handlebars" data-template-name="segments/index">
	<div class="row-fluid">
		<div class="span12">
			Segmentos
		</div>
	</div>
</script>
<script type="text/x-handlebars" data-template-name="segments">
	{{ '{{#if App.errormessage }}' }}
		<div class="alert alert-message alert-error">
	{{ '{{ App.errormessage }}' }}
		</div>
	{{ '{{/if}} '}}	

	{{'{{outlet}}'}}
</script>
