function PanelContent() {}

PanelContent.prototype.setPanelContainer = function(container) {
	this.container = container;
};

PanelContent.prototype.setModel = function(model) {
	this.model = model;
};

PanelContent.prototype.initialize = function() {};
PanelContent.prototype.updateView = function() {};
PanelContent.prototype.createNextPanel = function() {};
PanelContent.prototype.serialize = function() {};