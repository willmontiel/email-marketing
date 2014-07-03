TopPanelContent = function() {};

TopPanelContent.prototype = new PanelContent;

TopPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-selector-content').on('click', function (e) {
		var criteria = $(this).attr('data-type');
		self.addContent(e, criteria);
		$('.sgm-add-selector-content').removeClass('li-active');
		$(this).addClass('li-active');
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

TopPanelContent.prototype.addContent = function (e, criteria) {
	e.preventDefault();
	var listPanelContent = new ListPanelContent();
	listPanelContent.setPanelContainer(this.container);
	listPanelContent.setContentCriteria(criteria);
	listPanelContent.createContent();
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione un criterio',
		content: listPanelContent
	};
		
	this.container.addPanel(config);
};

TopPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-content-selector">\n\
						 <ul>\n\
							<li class="sgm-add-selector-content" data-type="dbases">\n\
								<span class="glyphicon glyphicon-tasks"></span> Bases de datos\n\
							</li>\n\
							<li class="sgm-add-selector-content" data-type="lists">\n\
								<span class="glyphicon glyphicon-list-alt"></span> Listas de contactos\n\
							</li>\n\
							<li class="sgm-add-selector-content" data-type="segments">\n\
								<span class="glyphicon glyphicon-user"></span> Segmentos\n\
							</li>\n\
						 </ul>\n\
					  </div>');
};
