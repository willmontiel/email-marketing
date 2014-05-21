<script type = "text/javascript">
	function getUrlForStatistics(id) {
		$.post("{{url('share/statistics')}}/" + id, function(response){
			$('#summary'+id).val("");
			$('#complete'+id).val("");

			$('#summary' + id).val(response[0]);
			$('#complete' + id).val(response[1]);
		});
	}

	$(function () {
		console.log('POPOVER');
		console.log($('button[data-toggle=popover]'));
		$('button[data-toggle=popover]').click(function () {
			var me = $(this);
			var isVisible = me.data('bs.popover');
			if (isVisible === undefined) {
				var id = me.data('idmail');
				$.post("{{url('share/statistics')}}/" + id, function(response){
					var txt = '<b>Reporte resumido: </b><br />' + response[0] + '<br /><br /><b>Reporte completo: </b><br />' + response[1];
					me.popover({
						trigger: 'manual',
						placement: 'left',
					});
					me.data('bs.popover').options.content = 'Un momento por favor...';
					me.popover("show");

					thepop = me;
					me.data('bs.popover').$tip.find('.popover-content').html(txt);
				});
			}
			else {
				isVisible = isVisible.tip().hasClass('in');
				if (isVisible) {
					me.popover("hide");
					me.popover('destroy')
				}
			}
		});
		//$('button[data-toggle="popover"]').tooltip();
	}) ;
</script>