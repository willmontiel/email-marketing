function PanelContainer(selector) {
	this.element = $(selector);
	this.panellist = [];
}

PanelContainer.prototype.addPanel = function(config) {
	config.title = config.title + ' - ' + (this.panellist.length + 1);
	var panel = new Panel(this, config);
	panel.createPanel(this.element);
	this.panellist.push(panel);
};

PanelContainer.prototype.removePanel = function(panel) {
	var i = this.panellist.indexOf(panel);
	var l = this.panellist.length;
	if (i >= 0) {
		var tor = this.panellist.splice(i, l - i);
		for (var j=0; j<tor.length; j++) {
				tor[j].remove();
		}
	}
};

PanelContainer.prototype.resetContainer = function () {
	var l = this.panellist.length;
	
	for (var i = 1; i < this.panellist.length; i++) {
		this.panellist[i].remove();
	}
	
	this.panellist.splice(1, l - 1);
	console.log(this.panellist);
};