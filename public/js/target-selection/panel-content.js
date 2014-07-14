function PanelContent() {}

PanelContent.prototype.setPanelContainer = function(container) {
	this.container = container;
};

PanelContent.prototype.setModel = function(model) {
	this.model = model;
};

PanelContent.prototype.initialize = function() {};
PanelContent.prototype.createContent = function() {};
PanelContent.prototype.serialize = function() {};
PanelContent.prototype.createNextPanel = function() {};