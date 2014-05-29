function Toolbar(component) {
	this.component = component;
	$('#my-component-toolbar').remove();
	$('.component-toolbar-social').remove();
	$('.component-toolbar-button').remove();
	this.toolbar = $('.component-toolbar').clone().attr('id', 'my-component-toolbar');
	this.toolbar.empty();
	this.toolbar.append('<ul class="components-list"/>');
	
	$('.element-in-edition').removeClass('element-in-edition');
	removeTextEditor();
}

Toolbar.prototype.drawHtml = function(obj_in_edit) {
	if(!obj_in_edit) {
		var component = this.component.content;
	}
	else {
		var component = this.component.content.find('.' + obj_in_edit);
	}
	component.addClass('element-in-edition');
	
	$('#edit-area').prepend(this.toolbar);
	this.toolbar.css('position', 'absolute');
	var position = this.component.content.offset();
	this.toolbar.show();
	if(this.component instanceof EditionArea) {
		this.toolbar.css('top', 50);
		this.toolbar.css('left', 20);
	}
	else if (this.component instanceof DropzoneArea) {
		this.toolbar.css('top', position.top + this.component.content.height() - 30);
		this.toolbar.css('left', 222);
	}
	else if(this.component instanceof rowZone) {
		this.toolbar.css('top', position.top + this.component.content.height() - 30);
		this.toolbar.css('left', 112);
	}
	else {
		this.toolbar.css('top', position.top + this.component.content.height() - 20);
		this.toolbar.css('left', 112);
	}
};

Toolbar.prototype.createBackground = function() {
	var with_color = (this.component.background_color === 'transparent') ? '' : 'checked';
	var what_color = (this.component.background_color === 'transparent') ? '#FFFFFF' : this.component.background_color;
	
	var title = $("<div class='option-title-toolbar'>Fondo <input type='checkbox' id='withbgcolor' " + with_color + "></label></div>");

	var backgroundColor = $("<input type='text' id='color-background-toolbar' name='color-background-toolbar' class='pick-a-color'>");
	
	var elements = $('<li class="toolbar-elements" />');
	elements.append(title);
	elements.append(backgroundColor);
	
	this.toolbar.find('.components-list').append(elements);
	
	var t = this;
	
	$('#color-background-toolbar').spectrum({
		color: what_color,
		flat: false,
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			t.component.updateBlockStyle('background-color', color.toHexString());
			t.component.background_color = color.toHexString();
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
			"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"], 
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", 
			"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", 
			"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", 
			"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", 
			"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", 
			"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", 
			"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	
	$("#withbgcolor").on("click", function () {
		if(!$(this)[0].checked) {
			t.component.background_color = 'transparent';
		}
		else {
			t.component.background_color = $('#color-background-toolbar input').val();
		}
		t.component.updateBlockStyle('background-color', t.component.background_color);
	});
};

Toolbar.prototype.createBorder = function() {
	var title = $("<div class='option-title-toolbar'>Borde</div>");
	var color = $("<input type='text' id='color-border-toolbar' name='color-border-toolbar' class='pick-a-color'>");
	var style = $("<div class='medium-select'>\n\
					<select id='style-border-toolbar'>\n\
						<option value='none'>none</option>\n\
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
	var width = $('<div><input id="border-width-spinner" name="width" class="toolbar-spinner" value=' + this.component.border_width +'></div>');
	
	var elements = $('<li class="toolbar-elements" style="width: 203px;"/>');
	elements.append(title);
	elements.append(color);
	elements.append(style);
	elements.append(width);
	
	this.toolbar.find('.components-list').append(elements);
	
	var t = this;
	
	$('#color-border-toolbar').spectrum({
		color: t.component.border_color,
		flat: false,
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			t.component.updateBlockStyle('border-color', color.toHexString());
			t.component.border_color = color.toHexString();
		},
		palette: [
			["rgb(0, 0, 0)", "rgb(67, 67, 67)", "rgb(102, 102, 102)",
			"rgb(204, 204, 204)", "rgb(217, 217, 217)","rgb(255, 255, 255)"],
			["rgb(152, 0, 0)", "rgb(255, 0, 0)", "rgb(255, 153, 0)", "rgb(255, 255, 0)", "rgb(0, 255, 0)",
			"rgb(0, 255, 255)", "rgb(74, 134, 232)", "rgb(0, 0, 255)", "rgb(153, 0, 255)", "rgb(255, 0, 255)"], 
			["rgb(230, 184, 175)", "rgb(244, 204, 204)", "rgb(252, 229, 205)", "rgb(255, 242, 204)", "rgb(217, 234, 211)", 
			"rgb(208, 224, 227)", "rgb(201, 218, 248)", "rgb(207, 226, 243)", "rgb(217, 210, 233)", "rgb(234, 209, 220)", 
			"rgb(221, 126, 107)", "rgb(234, 153, 153)", "rgb(249, 203, 156)", "rgb(255, 229, 153)", "rgb(182, 215, 168)", 
			"rgb(162, 196, 201)", "rgb(164, 194, 244)", "rgb(159, 197, 232)", "rgb(180, 167, 214)", "rgb(213, 166, 189)", 
			"rgb(204, 65, 37)", "rgb(224, 102, 102)", "rgb(246, 178, 107)", "rgb(255, 217, 102)", "rgb(147, 196, 125)", 
			"rgb(118, 165, 175)", "rgb(109, 158, 235)", "rgb(111, 168, 220)", "rgb(142, 124, 195)", "rgb(194, 123, 160)",
			"rgb(166, 28, 0)", "rgb(204, 0, 0)", "rgb(230, 145, 56)", "rgb(241, 194, 50)", "rgb(106, 168, 79)",
			"rgb(69, 129, 142)", "rgb(60, 120, 216)", "rgb(61, 133, 198)", "rgb(103, 78, 167)", "rgb(166, 77, 121)",
			"rgb(91, 15, 0)", "rgb(102, 0, 0)", "rgb(120, 63, 4)", "rgb(127, 96, 0)", "rgb(39, 78, 19)", 
			"rgb(12, 52, 61)", "rgb(28, 69, 135)", "rgb(7, 55, 99)", "rgb(32, 18, 77)", "rgb(76, 17, 48)"]
		]
	});
	
	this.spinnerBlockChange('border-width-spinner', 'border-width', 'border_width');
	
	$('#style-border-toolbar').val(this.component.border_style)
	$('#style-border-toolbar').on('change', function() {
		t.component.updateBlockStyle('border-style', $(this).val());
		t.component.border_style = $(this).val();
	});
};

Toolbar.prototype.createCorners = function() {
	var title = $("<div class='option-title-toolbar'>Esquinas</div>");
	var top_left = $('<input id="corner-top-left-spinner" name="top-left" class="toolbar-spinner" value=' + this.component.corner_top_left + '>');
	var top_right = $('<input id="corner-top-right-spinner" name="top-right" class="toolbar-spinner" value=' + this.component.corner_top_right + '>');
	var bottom_left = $('<input id="corner-bottom-left-spinner" name="bottom-left" class="toolbar-spinner" value=' + this.component.corner_bottom_left + '>');
	var bottom_right = $('<input id="corner-bottom-right-spinner" name="bottom-right" class="toolbar-spinner" value=' + this.component.corner_bottom_right + '>');
	
	var elements = $('<li class="toolbar-elements toolbar-corners" />');
	elements.append(title);
	elements.append(top_left);
	elements.append(top_right);
	elements.append(bottom_left);
	elements.append(bottom_right);
	
	this.toolbar.find('.components-list').append(elements);
	
	this.spinnerBlockChange('corner-top-left-spinner', 'border-top-left-radius', 'corner_top_left');
	this.spinnerBlockChange('corner-top-right-spinner', 'border-top-right-radius', 'corner_top_right');
	this.spinnerBlockChange('corner-bottom-left-spinner', 'border-bottom-left-radius', 'corner_bottom_left');
	this.spinnerBlockChange('corner-bottom-right-spinner', 'border-bottom-right-radius', 'corner_bottom_right');
};

Toolbar.prototype.createMargins = function() {
	var title = $("<div class='option-title-toolbar'>Margenes</div>");
	var top_left = $('<input id="margin-top-spinner" name="top" class="toolbar-spinner" value=' + this.component.margin_top + '>');
	var top_right = $('<input id="margin-bottom-spinner" name="bottom" class="toolbar-spinner" value=' + this.component.margin_bottom + '>');
	var bottom_left = $('<input id="margin-left-spinner" name="left" class="toolbar-spinner" value=' + this.component.margin_left + '>');
	var bottom_right = $('<input id="margin-right-spinner" name="right" class="toolbar-spinner" value=' + this.component.margin_right + '>');
	
	var elements = $('<li class="toolbar-elements" />');
	elements.append(title);
	elements.append(top_left);
	elements.append(top_right);
	elements.append(bottom_left);
	elements.append(bottom_right);
	
	this.toolbar.find('.components-list').append(elements);
	
	this.spinnerColumnChange('margin-top-spinner', 'padding-top', 'margin_top');
	this.spinnerColumnChange('margin-bottom-spinner', 'padding-bottom', 'margin_bottom');
	this.spinnerColumnChange('margin-left-spinner', 'padding-left', 'margin_left');
	this.spinnerColumnChange('margin-right-spinner', 'padding-right', 'margin_right');
};

Toolbar.prototype.spinnerBlockChange = function(id, style, property) {
	var t = this;
	$('#' + id).spinner({min: 0, max: 99,
		stop: function() {
			t.component.updateBlockStyle(style, $(this).val());
			t.component[property] = $(this).val();
		}
	});
};

Toolbar.prototype.spinnerContentChange = function(id, style, property) {
	var t = this;
	$('#' + id).spinner({min: 0, max: 99,
		stop: function() {
			t.component.updateContentStyle(style, $(this).val());
			t.component[property] = $(this).val();
		}
	});
};

Toolbar.prototype.spinnerColumnChange = function(id, style, property) {
	var t = this;
	$('#' + id).spinner({min: 0, max: 99,
		stop: function() {
			t.component.updateColumnStyle(style, $(this).val());
			t.component[property] = $(this).val();
		}
	});
};

Toolbar.prototype.setWidthSize = function(width) {
	this.toolbar.css('width', width);
};

Toolbar.prototype.setHeightSize = function(width) {
	this.toolbar.css('height', width);
};

Toolbar.prototype.createLayout = function() {
	var title = $("<div class='option-title-toolbar'>Layouts</div>");
	var layouts = $("<div class='layout-option-toolbar'><img data-toggle='modal' class='change-layout-toolbar' data-backdrop='static' href='#select-layout' src='" + config.imagesUrl + "/n1.png' style='width: 45px;' width='45px'></div>");
	
	var elements = $('<li class="toolbar-elements" />');
	elements.append(title);
	elements.append(layouts);
	
	this.toolbar.find('.components-list').append(elements);
};