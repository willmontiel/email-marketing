function FilterPanelContent() {}
FilterPanelContent.prototype = new PanelContent;

FilterPanelContent.prototype.initialize = function(panel) {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addContent(e);
	});
	
	panel.find('.sgm-panel-content').append(this.content);
};

//FilterPanelContent.prototype.addContent = function(e) {
//	e.preventDefault();
//	
//	var filterPanelContent = new FilterPanelContent();
//	filterPanelContent.setPanelContainer(this.container);
//	filterPanelContent.createContent();
//	
//	var config = {
//		sticky: false, 
//		leftArrow: true, 
//		title: 'Seleccione una opción',
//		content: filterPanelContent
//	};
//		
//	this.container.addPanel(config);
//};

FilterPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-filter-selector">\n\
						<div class="sgm-filter-content">\n\
							<div class="sgm-filter-content-header">\n\
								<span class="filter-icon glyphicon glyphicon-hand-up"></span>\n\
								<span class="filter-icon glyphicon glyphicon-eye-open"></span>\n\
								<span class="filter-icon glyphicon glyphicon-envelope"></span>\n\
								<span class="filter-icon glyphicon glyphicon-comment"></span>\n\
								<span class="filter-icon glyphicon glyphicon-globe"></span>\n\
								<span class="filter-icon glyphicon glyphicon-star"></span>\n\
							</div>\n\
						</div>\n\
						<div class="sgm-add-filter-content sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>\n\
						</div> \n\
					 </div>');
};

