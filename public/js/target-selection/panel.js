function Panel(parent, config) {
	this.config = config;
	this.parent = parent;
}

Panel.prototype.panelClass = 'col-xs-12 col-sm-6 col-md-4 col-lg-3';

Panel.prototype.createPanel = function(container) {
	this.html = $('<div class="' + Panel.prototype.panelClass + '">\n\
					<div class="sgm-panel" style="display: none;">\n\
						' + ((!this.config.sticky)?'<div class="sgm-close-panel"><span class="glyphicon glyphicon-minus-sign"></span></div>':'') +
						'<div class="sgm-panel-title">' + this.config.title + '</div>\n\
						<div class="sgm-panel-content">\n\
						</div>\n\
					    ' + ((this.config.leftArrow) ? '<div class="sgm-left-arrow-border"></div><div class="sgm-left-arrow"></div>' : '') + '\n\
						' + ((!this.config.sticky) ?'<div class="sgm-add-panel"><span class="glyphicon glyphicon-plus-sign"></span></div>' : '') + '\n\
					</div>\n\
				 </div>');
	this.container = container;
	var self = this;
	this.html.find('.sgm-close-panel').on('click', function (e) {
		self.close(e);
	});
	
//	this.html.show('slow');
	this.html.find('.sgm-panel').css({width: '0px'}).show();
	container.append(this.html);
	this.html.find('.sgm-panel').animate({width: '100%'});
	
	this.config.content.initialize(this.html);
};

Panel.prototype.close = function (e) {
	e.preventDefault();
	this.parent.removePanel(this);
};

Panel.prototype.remove = function (e) {
	this.html.remove();
};