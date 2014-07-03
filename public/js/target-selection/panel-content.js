function PanelContent() {}

PanelContent.prototype.setPanelContainer = function(container) {
	this.container = container;
};

PanelContent.prototype.initialize = function() {};

PanelContent.prototype.addContent = function(e) {
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

PanelContent.prototype.createContent = function() {};