function PanelContent() {}

PanelContent.prototype.setPanelContainer = function(container) {
	this.container = container;
};

PanelContent.prototype.initialize = function(panel) {
	panel.find('.sgm-panel-content').append(this.content);
};


//-------------------------------------------------------------------------------------------


function ListPanelContent() {}
ListPanelContent.prototype = new PanelContent;

ListPanelContent.prototype.initialize = function() {
	var self = this;
	this.content.find('.sgm-add-filter-content').on('click', function (e) {
		self.addFilter(e);
	});
};

ListPanelContent.prototype.addFilter = function (e) {
	e.preventDefault();
	
	var filterPanelContent = new FilterPanelContent();
	filterPanelContent.setPanelContainer(this.container);
	filterPanelContent.createContent();
	
	var config = {
		sticky: false, 
		leftArrow: true, 
		title: 'Seleccione una opción',
		content: filterPanelContent
	};
		
	this.container.addPanel(config);
};

ListPanelContent.prototype.createContent = function () {
	this.content = $('<div class="sgm-target-selector">\n\
						<div class="sgm-selector-content"></div>\n\
						<div class="sgm-add-filter-content sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span> Agregar filtro</div>\n\
						</div> \n\
					 </div>');
};

//--------------------------------------------------------------------------------------------

function FilterPanelContent() {}
FilterPanelContent.prototype = new PanelContent;

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