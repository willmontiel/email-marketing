function PanelContainer(selector) {
	var structure = $('<div class="row"><div class="sgm-panel-space"></div></div><div class="row"><div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><div class="sgm-panel-contacts-space">Contactos aproximados: 0</div></div></div>');
	this.element = $(selector);
	this.element.append(structure);
	this.panellist = [];
}

PanelContainer.prototype.addPanel = function(config, model) {
	var panel = new Panel(this, config);
	panel.setModel(model);
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
	
	return i;
};

PanelContainer.prototype.resetContainer = function () {
	var l = this.panellist.length;
	
	for (var i = 1; i < this.panellist.length; i++) {
		this.panellist[i].remove();
	}
	
	this.panellist.splice(1, l - 1);
	$('.sgm-add-selector-content').removeClass('li-active');
};

PanelContainer.prototype.updateContainer = function() {
	for (var i = 2; i < this.panellist.length; i++) {
		this.panellist[i].remove();
	}
	
	var e = [];
	e.push(this.panellist[0]);
	e.push(this.panellist[1]);
	
	this.panellist = [];
	this.panellist = e.slice(0);
	
	console.log(this.panellist[0]);
	console.log(this.panellist[1]);
};