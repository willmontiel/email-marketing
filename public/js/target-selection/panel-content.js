function PanelContent() {}

PanelContent.prototype.setPanelContainer = function(container) {
	this.container = container;
};

PanelContent.prototype.setContentCriteria = function(criteria) {
	this.criteria = criteria;
};

PanelContent.prototype.initialize = function() {};
PanelContent.prototype.addContent = function() {};
PanelContent.prototype.getUrlForDataSource = function() {};
PanelContent.prototype.createContent = function() {};