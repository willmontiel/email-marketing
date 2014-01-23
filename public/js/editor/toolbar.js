function Toolbar(component) {
	this.component = component;
}

Toolbar.prototype.drawHtml = function() {
	$('.component-toolbar').remove();
	$('.element-in-edition').removeClass('element-in-edition');
	
	this.component.content.find('.one-element').addClass('element-in-edition');
	var toolbar = $('<div class="component-toolbar"/>');
	var position = this.component.content.find('.one-element').position();
	
	toolbar.css('top', position.top - 32);
	toolbar.css('left', position.left - 22);
	this.component.content.append(toolbar);
};

