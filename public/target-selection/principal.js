function PrincipalPanel(dbase, list, segment) {
	this.dbase = dbase;
	this.list = list;
	this.segment = segment;
	 
	$(function () {
		$('.principal-panel > ul > li').click(function() {
			$('.principal-panel li').removeClass('li-active');
			$(this).closest('li').addClass('li-active'); 
		});
	});
}

PrincipalPanel.prototype.createHtmlPanel = function() {
	this.html = '<div class="principal-panel">\n\
					 <div class="panel-title">Seleccione una opción</div>\n\
						<ul>\n\
							<li class="' + this.dbase + '">\n\
								<span class="glyphicon glyphicon-hdd"></span> \n\
									Base de datos \n\
								<span class="glyphicon glyphicon-chevron-right pull-right arrow-right"></span>\n\
							</li>\n\
							<li class="' + this.list + '">\n\
								<span class="glyphicon glyphicon-list-alt"></span> \n\
									Listas de contactos \n\
								<span class="glyphicon glyphicon-chevron-right pull-right arrow-right"></span>\n\
							</li>\n\
							<li class="' + this.segment + '">\n\
								<span class="glyphicon glyphicon-fire"></span> \n\
									Segmentos \n\
								<span class="glyphicon glyphicon-chevron-right pull-right arrow-right"></span>\n\
							</li>\n\
						</ul>\n\
					</div>\n\
				</div>';
};

PrincipalPanel.prototype.appendPanel = function() {
	$('.principal').html(this.html);
};