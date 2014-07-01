function Panel(title, attrAdd, attrRemove) {
	this.title = title;
	this.attrAdd = attrAdd;
	this.attrRemove = attrRemove;
	
	var self = this;
	
	$('.addFilter').on('click', function() {
		self.addPanel('Seleccione una opción', true, true);
		console.log('Se agregó panel');
	});
}

Panel.prototype.createHtmlPanel = function() {
	var add = (this.attrAdd ? '<button class="addFilter btn btn-sm btn-primary extra-padding">+</button>' : '');
	var remove = (this.attrRemove ? '<button class="removeFilter btn btn-sm btn-danger extra-padding">-</button>' : '');
	
	console.log(add);
	this.html = $('<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">\n\
					<div class="panel">\n\
						<div class="panel-title">' + this.title + '</div>\n\
						</div>\n\
						<div class="panel-content">\n\
							' + remove + '\n\
							' + add + '\n\
						</div>\n\
					</div>\n\
				 </div>');
};

Panel.prototype.addPanel = function(title, add, remove) {
	var panel = new Panel(title, add, remove);
	panel.createHtmlPanel();
	var cpanel = panel.getPanel();
	
	$('.panel-container').append(cpanel);
};

Panel.prototype.destroyPanel = function() {
	
};

Panel.prototype.getPanel = function() {
	return this.html;
};