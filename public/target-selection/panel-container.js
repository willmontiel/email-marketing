function PanelContainer() {
	this.addPanel('Seleccione una opción', true);
	
	var self = this;
	
	$('.addFilter').on('click', function() {
		self.addPanel('Seleccione una opción', true, true);
		console.log('Se agregó panel');
	});
}

PanelContainer.prototype.addPanel = function(title, add, remove) {
	var panel = new Panel(title, add, remove);
	panel.createHtmlPanel();
	var cpanel = panel.getPanel();
	
	$('.panel-container').append(cpanel);
};