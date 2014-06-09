function BtnBlock(row) {
	this.row = row;
	this.content_button = '<span class="content-button pull-center" style="background-image:url(\'' + config.imagesUrl + '/btn-blue.png\');border:1px solid #1e3650;border-radius:4px;">Clic Aqui!</span>';
	
	this.btntext = 'Clic Aqui!';
	this.btnlink = '';
	this.btnbgcolor = '#556270';
	this.btntextcolor = '#ffffff';;
	this.btnradius = 4;
	this.btnbordercolor = '#1e3650';
	this.btnborderwidth = 1;
	this.btnborderstyle = 'solid';
	this.btnwithbgimage = true;
	this.btnbgimage = 'blue';
	this.btnwidth = 90;
	this.btnheight = 20;
	this.btnalign = 'center';
	this.btnfontsize = 14;
	this.btnfontfamily = 'arial';
	
	this.background_color = "transparent";
	this.border_width = 0;
	this.border_color = "#FFFFFF";
	this.border_style = "none";
	this.corner_top_left = 0;
	this.corner_top_right = 0;
	this.corner_bottom_left = 0;
	this.corner_bottom_right = 0;
	this.margin_top = 0;
	this.margin_bottom = 0;
	this.margin_left = 0;
	this.margin_right = 0;
}

BtnBlock.prototype.createBlock = function() {
	this.content = this.drawHtml();
	this.row.content.find('.in-row').append(this.content);
	this.updateChanges();
	this.editBlock();
	this.removeBlock();
	
	var t = this;
	this.content.find('.content-button').on('click', function() {
		removeTextEditor();
		t.createToolbar();
	});
};

BtnBlock.prototype.drawHtml = function() {
	var block = $('<td class="in-column">\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element clearfix">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	block.find('.one-element').append(this.content_button);
	return block;
};

BtnBlock.prototype.editBlock = function() {
	var t = this;
	this.row.content.find('td:last .edit-block').on('click', function(event) {
		var toolbar = new Toolbar(t);
		toolbar.drawHtml('one-element');
		toolbar.createBackground();
		toolbar.createBorder();
		toolbar.createCorners();
		toolbar.createMargins();
		toolbar.setWidthSize('750');
		
		event.stopPropagation();
	});
};

BtnBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
		removeTextEditor();
		t.row.removeBlock(t);
		t.content.remove();
	});
};

BtnBlock.prototype.updateBlockStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

BtnBlock.prototype.updateContentStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

BtnBlock.prototype.updateColumnStyle = function(style, value) {
	this.content.css(style, value);
};

BtnBlock.prototype.persist = function() {
	var obj = {
		text : this.btntext,
		link : this.btnlink,
		bgcolor : this.btnbgcolor,
		textcolor : this.btntextcolor,
		radius : this.btnradius,
		bordercolor : this.btnbordercolor,
		borderwidth : this.btnborderwidth,
		borderstyle : this.btnborderstyle,
		withbgimage : this.btnwithbgimage,
		bgimage : this.btnbgimage,
		width : this.btnwidth,
		height : this.btnheight,
		align : this.btnalign,
		fontsize : this.btnfontsize,
		fontfamily : this.btnfontfamily,
		
		background_color : this.background_color,
		border_width : this.border_width,
		border_color : this.border_color,
		border_style : this.border_style ,
		corner_top_left : this.corner_top_left,
		corner_top_right : this.corner_top_right,
		corner_bottom_left : this.corner_bottom_left,
		corner_bottom_right : this.corner_bottom_right,
		margin_top : this.margin_top,
		margin_bottom : this.margin_bottom,
		margin_left : this.margin_left,
		margin_right : this.margin_right,
		type : 'Button'
	};
	return obj;
};

BtnBlock.prototype.unpersist = function(obj) {
	this.btntext = obj.text;
	this.btnlink = obj.link;
	this.btnbgcolor = obj.bgcolor;
	this.btntextcolor = obj.textcolor;
	this.btnradius = obj.radius;
	this.btnbordercolor = obj.bordercolor;
	this.btnborderwidth = obj.borderwidth;
	this.btnborderstyle = obj.borderstyle;
	this.btnwithbgimage = obj.withbgimage;
	this.btnbgimage = obj.bgimage;
	this.btnwidth = obj.width;
	this.btnheight = obj.height;
	this.btnalign = obj.align;
	this.btnfontsize = obj.fontsize;
	this.btnfontfamily = obj.fontfamily;
	
	this.background_color = obj.background_color,
	this.border_width = obj.border_width;
	this.border_color = obj.border_color;
	this.border_style = obj.border_style;
	this.corner_top_left = obj.corner_top_left;
	this.corner_top_right = obj.corner_top_right;
	this.corner_bottom_left = obj.corner_bottom_left;
	this.corner_bottom_right = obj.corner_bottom_right;
	this.margin_top = obj.margin_top;
	this.margin_bottom = obj.margin_bottom;
	this.margin_left = obj.margin_left;
	this.margin_right = obj.margin_right;
	
	return this;
};

BtnBlock.prototype.updateChanges = function() {
	this.updateBlockStyle('background-color', this.background_color);
	
	this.updateBlockStyle('border-color', this.border_color);
	this.updateBlockStyle('border-width', this.border_width);
	this.updateBlockStyle('border-style', this.border_style);
	
	this.updateBlockStyle('border-top-left-radius', this.corner_top_left);
	this.updateBlockStyle('border-top-right-radius', this.corner_top_right);
	this.updateBlockStyle('border-bottom-left-radius', this.corner_bottom_left);
	this.updateBlockStyle('border-bottom-right-radius', this.corner_bottom_right);
	
	this.updateColumnStyle('padding-top', this.margin_top);
	this.updateColumnStyle('padding-bottom', this.margin_bottom);
	this.updateColumnStyle('padding-left', this.margin_left);
	this.updateColumnStyle('padding-right', this.margin_right);
	
	this.designBtn();
};

BtnBlock.prototype.designBtn = function() {
	var content = this.content.find('.content-button');
	content.text(this.btntext);
	content.css('background-color', this.btnbgcolor);
	content.css('color', this.btntextcolor);
	content.css('border-radius', this.btnradius);
	content.css('border-color', this.btnbordercolor);
	content.css('border-style', this.btnborderstyle);
	content.css('border-width', this.btnborderwidth);
	content.css('width', this.btnwidth);
	
	if(this.btnwithbgimage) {
		content.css('background-image', 'url(' + config.imagesUrl + '/btn-' + this.btnbgimage + '.png)');
	}
	else {
		content.css('background-image', '');
	}
	
	content.css('height', this.btnheight);
	content.css('font-size', this.btnfontsize);
	content.css('font-family', this.btnfontfamily);
	
	content.removeClass('pull-center');
	content.removeClass('pull-left');
	content.removeClass('pull-right');
	content.addClass('pull-' + this.btnalign);
};

BtnBlock.prototype.createToolbar = function() {
	$('#my-btn-component-toolbar').remove();
	$('.component-toolbar-button').remove();

	var toolbar =  $('<div class="component-toolbar-button" id="my-btn-component-toolbar" />');
	$('#edit-area').prepend(toolbar);
	var position = this.content.offset();
	toolbar.css('position', 'absolute');
	toolbar.css('top', position.top + this.content.height() - 20);
	toolbar.css('left', 112);
	
	$('.element-btn-in-edition').removeClass('element-btn-in-edition');
	this.content.find('.one-element').addClass('element-btn-in-edition');

	toolbar.append('<table><tr><td class="first_row"><ul class="first_elements"></ul></td></tr><tr><td class="second_row"><ul class="second_elements"></ul></td></tr><tr><td class="third_row"><ul class="third_elements"></ul></td></tr></table>');
	
	var checkedBGSty = (this.btnwithbgimage) ? 'checked' : '';
	var backgroundColor = $("<div class='button-background-toolbar-container'><div class='btn-toolbar-title'>Fondo</div><input type='text' id='color-button-background-toolbar' name='color-button-background-toolbar' class='pick-a-color'></div>");
	var backgroundStyle = $("<div class='button-background-toolbar-container'>\n\
								<div class='btn-toolbar-title'>Degradado</div><div class='btn-toolbar-background-style'><input type='checkbox' id='style-button-with-background-toolbar' " + checkedBGSty + "></div>\n\
								<div class='medium-select btn-toolbar-background-style-options'>\n\
								<select id='style-button-background-toolbar'>\n\
									<option value='blue' selected>Azul</option>\n\
									<option value='bluelight'>Azul Claro</option>\n\
									<option value='red'>Rojo</option>\n\
									<option value='redlight'>Rojo Claro</option>\n\
									<option value='black'>Negro</option>\n\
									<option value='yellow'>Amarillo</option>\n\
									<option value='orange'>Naranja</option>\n\
									<option value='gray'>Gris</option>\n\
								</select>\n\
							</div></div>");
	var elements = $('<li class="toolbar-elements" />');
	elements.append(backgroundColor);
	elements.append(backgroundStyle);
	
	var border_radius = $('<div class="button-corner-toolbar-container"><div class="btn-toolbar-title">Esquinas</div><input id="button-border-radius-spinner" name="border-radius" class="toolbar-spinner spinner-button" value=' + this.btnradius + '></div>');
	var border_color = $("<div class='button-border-toolbar-container'><div class='btn-toolbar-title'>Borde</div>\n\
							<div class='btn-border-style-toolbar medium-select'>\n\
								<select id='style-button-border-toolbar'>\n\
									<option value='none'>None</option>\n\
									<option value='dotted'>Dotted</option>\n\
									<option value='dashed'>Dashed</option>\n\
									<option value='solid'>Solid</option>\n\
									<option value='double'>Double</option>\n\
									<option value='groove'>Groove</option>\n\
									<option value='ridge'>Ridge</option>\n\
									<option value='inset'>Inset</option>\n\
									<option value='outset'>Outset</option>\n\
								</select>\n\
							</div>\n\
							<div class='btn-color-border-container'><input type='text' id='color-button-border-toolbar' name='color-button-border-toolbar' class='pick-a-color'></div>\n\
							<div class='btn-size-border-container'><input id='button-border-width-spinner' name='width' class='toolbar-spinner' value=" + this.btnborderwidth + "></div>\n\
						</div>");
	elements.append(border_radius);
	elements.append(border_color);
	toolbar.find('.first_row ul').append(elements);
	
	this.colorPickerBlockChange('color-button-background-toolbar', 'background-color', 'btnbgcolor', this.btnbgcolor);
	this.colorPickerBlockChange('color-button-border-toolbar', 'border-color', 'btnbordercolor', this.btnbordercolor);	
	this.spinnerBlockChange('button-border-radius-spinner', 'border-radius', 'btnradius', 0, 20);
	this.spinnerBlockChange('button-border-width-spinner', 'border-width', 'btnborderwidth', 0, 20);
	
	
	var font = $("<div class='button-title-toolbar-container'>\n\
								<div class='btn-toolbar-title'>Texto</div>\n\
								<div class='medium-select btn-toolbar-font-family-options'>\n\
								<select id='font-family-button-toolbar'>\n\
									<option value='arial'>Arial</option>\n\
									<option value='helvetica'>Helvetica</option>\n\
									<option value='georgia'>Georgia</option>\n\
									<option value='times new roman'>Times New Roman</option>\n\
									<option value='monospace'>Monospace</option>\n\
								</select>\n\
							</div>\n\
						<div class='color-font-container'><input type='text' id='color-button-font-toolbar' name='color-button-font-toolbar' class='pick-a-color'></div>\n\
						<div class='size-font-container'><input id='button-font-size-spinner' name='font-size' class='toolbar-spinner spinner-button' value=" + this.btnfontsize + "></div>\n\
						</div>");
	var text = $('<div class="button-title-toolbar-container"><div class="btn-toolbar-title">Titulo</div><div class="btn-toolbar-field"><input type="text" id="button-text-toolbar" value="' + this.btntext + '"></div></div>');
	var elements = $('<li class="toolbar-elements" />');
	elements.append(font);
	elements.append(text);
	toolbar.find('.second_row ul').append(elements);
	
	this.spinnerBlockChange('button-font-size-spinner', 'font-size', 'btnfontsize', 0, 30);
	this.colorPickerBlockChange('color-button-font-toolbar', 'color', 'btntextcolor', this.btntextcolor);
	
	
	var link = $('<div class="button-title-toolbar-container"><div class="btn-toolbar-title">Hipervinculo</div><div class="btn-toolbar-field"><input type="text" id="button-link-toolbar" value="' + this.btnlink + '"></div></div>');
	var elements = $('<li class="toolbar-elements list-no-right-line" />');
	elements.append(link);
	toolbar.find('.third_row ul').append(elements);
	
	
	var height = $('<div class="button-title-toolbar-container"><div class="btn-toolbar-title">Altura</div><div class="btn-height-container"><input id="button-height-spinner" name="height-button" class="toolbar-spinner-larger spinner-button" value=' + this.btnheight +'></div></div>');
	var width = $('<div class="button-title-toolbar-container"><div class="btn-toolbar-title">Ancho</div><div class="btn-width-container"><input id="button-width-spinner" name="width-button" class="toolbar-spinner-larger spinner-button-larger" value=' + this.btnwidth +'></div></div>');
	var align = $('<div class="button-title-toolbar-container"><div class="btn-toolbar-title">Alineacion</div><div class="medium-select btn-align-container">\n\
						<select id="align-button-toolbar">\n\
							<option value="left">Izquierda</option>\n\
							<option value="center" selected>Centro</option>\n\
							<option value="right">Derecha</option>\n\
						</select>\n\
					</div></div>');
	var elements = $('<li class="toolbar-elements" />');
	elements.append(height);
	elements.append(width);
	elements.append(align);
	toolbar.find('.third_row ul').append(elements);
	
	this.spinnerBlockChange('button-height-spinner', 'height', 'btnheight', 0, 200);
	this.spinnerBlockChange('button-width-spinner', 'width', 'btnwidth', 0, 200);
	
	this.eventsChange();
	
};

BtnBlock.prototype.spinnerBlockChange = function(id, style, property, min, max) {
	var t = this;
	$('#' + id).spinner({min: min, max: max,
		stop: function() {
			t.content.find('.content-button').css(style, $(this).val());
			t[property] = $(this).val();
		}
	});
};

BtnBlock.prototype.colorPickerBlockChange = function(id, style, property, value) {
	var t = this;
	
	$('#' + id).spectrum({
		color: value,
		flat: false,
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			t.content.find('.content-button').css(style, color.toHexString());
			t[property] = color.toHexString();
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
	
//	$('#' + id + ' input').on("change", function () {
//		t.content.find('.content-button').css(style, '#' + $(this).val());
//		t[property] = '#' + $(this).val();
//	});
};

BtnBlock.prototype.eventsChange = function() {
	var t = this;
	
	$('#style-button-background-toolbar').val(this.btnbgimage);
	$('#style-button-background-toolbar').on('change', function() {
		if($('#style-button-with-background-toolbar')[0].checked) {
			t.content.find('.content-button').css('background-image', 'url(' + config.imagesUrl + '/btn-' + $(this).val() + '.png)');
		}
		t.btnbgimage = $(this).val();
	});
	
	$('#style-button-border-toolbar').val(this.btnborderstyle);
	$('#style-button-border-toolbar').on('change', function() {
		t.content.find('.content-button').css('border-style', $(this).val());
		t.btnborderstyle = $(this).val();
	});
	
	$('#style-button-with-background-toolbar').on('change', function() {
		if($(this)[0].checked) {
			t.content.find('.content-button').css('background-image', 'url(' + config.imagesUrl + '/btn-' + t.btnbgimage + '.png)');
		}
		else {
			t.content.find('.content-button').css('background-image', '');
		}
		t.btnwithbgimage = $(this)[0].checked;
	});
	
	$('#font-family-button-toolbar').val(this.btnfontfamily);
	$('#font-family-button-toolbar').on('change', function() {
		t.content.find('.content-button').css('font-family', $(this).val());
		t.btnfontfamily = $(this).val();
	});
	
	$('#button-text-toolbar').on('change', function() {
		t.content.find('.content-button').text($(this).val());
		t.btntext = $(this).val();
	});
	
	$('#button-link-toolbar').on('change', function() {
		t.btnlink = $(this).val();
	});
	
	$('#align-button-toolbar').val(this.btnalign);
	$('#align-button-toolbar').on('change', function() {
		var content = t.content.find('.content-button')
		content.removeClass('pull-center');
		content.removeClass('pull-left');
		content.removeClass('pull-right');
		content.addClass('pull-' + $(this).val());
		t.btnalign = $(this).val();
	});
};