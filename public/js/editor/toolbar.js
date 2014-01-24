function Toolbar(component) {
	this.component = component;
	
	this.toolbar = $('.component-toolbar');
	this.toolbar.empty();
	this.toolbar.append('<ul class="components-list"/>');
	
	$('.element-in-edition').removeClass('element-in-edition');
}

Toolbar.prototype.drawHtml = function() {
	var component = this.component.content.find('.one-element');
	component.addClass('element-in-edition');

	this.toolbar.show();
	var position = component.position();
	this.toolbar.css('top', position.top - 68);
	this.toolbar.css('left', position.left - 103);
	this.component.content.append(this.toolbar);
};

Toolbar.prototype.createBackground = function() {
	var title = $("<div class='option-title-toolbar'>Fondo</div>");

	var backgroundColor = $("<input type='text' value='000' id='color-background-toolbar' name='color-background-toolbar' class='pick-a-color'>");
	
	var elements = $('<li class="toolbar-elements" />');
	elements.append(title);
	elements.append(backgroundColor);
	
	this.toolbar.find('.components-list').append(elements);
	
	$('#color-background-toolbar').pickAColor({showHexInput: false});
};

Toolbar.prototype.createBorder = function() {
	var title = $("<div class='option-title-toolbar'>Borde</div>");
	var color = $("<input type='text' value='000' id='color-border-toolbar' name='color-border-toolbar' class='pick-a-color'>");
	var style = $("<div class='medium-select'>\n\
					<select id='style-border-toolbar'>\n\
						<option value='dotted'>dotted</option>\n\
						<option value='dashed'>dashed</option>\n\
						<option value='solid'>solid</option>\n\
						<option value='double'>double</option>\n\
						<option value='groove'>groove</option>\n\
						<option value='ridge'>ridge</option>\n\
						<option value='inset'>inset</option>\n\
						<option value='outset'>outset</option>\n\
					</select>\n\
				</div>");
	var width = $('<input id="border-width-spinner" name="width" class="toolbar-spinner" value=0>');
	
	var elements = $('<li class="toolbar-elements" />');
	elements.append(title);
	elements.append(color);
	elements.append(style);
	elements.append(width);
	
	this.toolbar.find('.components-list').append(elements);
	
	var t = this;
	
	$('#color-border-toolbar').pickAColor({showHexInput: false});
	
	$("#color-border-toolbar input").on("change", function () {
		t.component.updateBlockStyle('border-color', $(this).val());
	});
	
	$('#border-width-spinner').spinner({min: 0, max: 100,
		stop: function() {
			t.component.updateBlockStyle('border-width', $(this).val());
		}
	});
	
	$('#style-border-toolbar').on('change', function() {
		t.component.updateBlockStyle('border-style', $(this).val());
	});
};

Toolbar.prototype.createCorners = function() {
	var title = $("<div class='option-title-toolbar'>Esquinas</div>");
	var top_left = $('<input id="corner-top-left-spinner" name="top-left" class="toolbar-spinner" value=0>');
	var top_right = $('<input id="corner-top-right-spinner" name="top-right" class="toolbar-spinner" value=0>');
	var bottom_left = $('<input id="corner-bottom-left-spinner" name="bottom-left" class="toolbar-spinner" value=0>');
	var bottom_right = $('<input id="corner-bottom-right-spinner" name="bottom-right" class="toolbar-spinner" value=0>');
	
	var elements = $('<li class="toolbar-elements toolbar-corners" />');
	elements.append(title);
	elements.append(top_left);
	elements.append(top_right);
	elements.append(bottom_left);
	elements.append(bottom_right);
	
	this.toolbar.find('.components-list').append(elements);
	
	this.spinnerBlockChange('corner-top-left-spinner', 'border-top-left-radius', this.component.corner_top_left);
	this.spinnerBlockChange('corner-top-right-spinner', 'border-top-right-radius', this.component.corner_top_right);
	this.spinnerBlockChange('corner-bottom-left-spinner', 'border-bottom-left-radius', this.component.corner_bottom_left);
	this.spinnerBlockChange('corner-bottom-right-spinner', 'border-bottom-right-radius', this.component.corner_bottom_right);
};

Toolbar.prototype.createMargins = function() {
	var title = $("<div class='option-title-toolbar'>Margenes</div>");
	var top_left = $('<input id="margin-top-spinner" name="top" class="toolbar-spinner" value=0>');
	var top_right = $('<input id="margin-bottom-spinner" name="bottom" class="toolbar-spinner" value=0>');
	var bottom_left = $('<input id="margin-left-spinner" name="left" class="toolbar-spinner" value=0>');
	var bottom_right = $('<input id="margin-right-spinner" name="right" class="toolbar-spinner" value=0>');
	
	var elements = $('<li class="toolbar-elements" />');
	elements.append(title);
	elements.append(top_left);
	elements.append(top_right);
	elements.append(bottom_left);
	elements.append(bottom_right);
	
	this.toolbar.find('.components-list').append(elements);
	
	this.spinnerContentChange('margin-top-spinner', 'margin-top', this.component.corner_top_left);
	this.spinnerContentChange('margin-bottom-spinner', 'margin-bottom', this.component.corner_top_left);
	this.spinnerContentChange('margin-left-spinner', 'margin-left', this.component.corner_top_left);
	this.spinnerContentChange('margin-right-spinner', 'margin-right', this.component.corner_top_left);
};

Toolbar.prototype.spinnerBlockChange = function(id, style) {
	var t = this;
	$('#' + id).spinner({min: 0, max: 99,
		stop: function() {
			t.component.updateBlockStyle(style, $(this).val());
		}
	});
};

Toolbar.prototype.spinnerContentChange = function(id, style) {
	var t = this;
	$('#' + id).spinner({min: 0, max: 99,
		stop: function() {
			t.component.updateContentStyle(style, $(this).val());
		}
	});
};