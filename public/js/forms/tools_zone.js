function ToolsZone(content_zone, content_header, button_zone){
	this.content_zone = content_zone;
	this.content_header = content_header;
	this.button_zone = button_zone;
	this.family_font = ['Arial', 'Courier New', 'Verdana', 'Comic Sans MS', 'Georgia', 'Times New Roman'];
	this.family = 'Arial';
	this.size_opt = ['8','10','12','14','18','24','36'];
	this.font_size = '14';
	this.background_color = '#FCFCFC';
	this.color = '#333333';
};

ToolsZone.prototype.designZone = function() {
	this.content = $('<ul class="adv_tools_list"></ul>');
	$('.form-adv-tools').append(this.content);
	
	this.colorFontFields();
	this.colorFormBackground();
	this.fontFamilyFields();
	this.fontSizeFields();
};

ToolsZone.prototype.addOptInZone = function(opt) {
	this.content.append(opt);
};

ToolsZone.prototype.applyChanges = function() {
	this.content_zone.updateStyle('color', this.color);
	this.content_zone.updateStyle('background-color', this.background_color);
	this.content_header.updateStyle('background-color', this.background_color);
	this.button_zone.updateStyle('background-color', this.background_color);
	this.content_zone.updateStyle('font-family', this.family);
	this.content_zone.updateStyle('font-size', this.font_size + 'px');
};

ToolsZone.prototype.colorFontFields = function() {
	var t = this;
	
	this.content.append($('<li><div>Color Fuente: </div><div><input type="text" id="color-font-toolbar" name="color-font-toolbar" class="pick-a-color"></div></li>'));
	
	$('#color-font-toolbar').spectrum({
			color: t.color,
			flat: false,
			showInput: true,
			className: "full-spectrum",
			showInitial: true,
			showPalette: true,
			showSelectionPalette: true,
			maxPaletteSize: 10,
			preferredFormat: "hex",
			change: function(color) {
				t.content_zone.updateStyle('color', color.toHexString());
				t.color = color.toHexString();
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
};

ToolsZone.prototype.colorFormBackground = function() {
	var t = this;
	
	this.content.append($('<li><div>Color Fondo: </div><div><input type="text" id="color-background-toolbar" name="color-background-toolbar" class="pick-a-color"></div></li>'));
	
	$('#color-background-toolbar').spectrum({
			color: t.background_color,
			flat: false,
			showInput: true,
			className: "full-spectrum",
			showInitial: true,
			showPalette: true,
			showSelectionPalette: true,
			maxPaletteSize: 10,
			preferredFormat: "hex",
			change: function(color) {
				t.content_zone.updateStyle('background-color', color.toHexString());
				t.content_header.updateStyle('background-color', color.toHexString());
				t.button_zone.updateStyle('background-color', color.toHexString());
				t.background_color = color.toHexString();
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
};

ToolsZone.prototype.fontFamilyFields = function() {
	var t = this;
	var family = '';

	for (var i = 0; i < this.family_font.length; i++) {
		var selected_family = (this.family_font[i] === this.family) ? 'selected' : '';
		family+= '<option value="' + this.family_font[i] + '" ' + selected_family + '>' + this.family_font[i] + '</option>';
	}

	var family_content = $('<li><div>Familia: </div><div><select class="form-control form-font-family">' + family + '</select></div></li>');
	this.content.append(family_content);

	family_content.find('.form-font-family').change(function(){
		t.content_zone.updateStyle('font-family', $(this).val());
		t.family = $(this).val();
	});	
};

ToolsZone.prototype.fontSizeFields = function() {
	var t = this;
	var size = '';
		
	for (var i = 0; i < this.size_opt.length; i++) {
		var selected_size = (this.size_opt[i] === this.font_size) ? 'selected' : '';
		size+= '<option value="' + this.size_opt[i] + '" ' + selected_size + '>' + this.size_opt[i] + '</option>';
	}
	
	var size_content = $('<li><div>Tamaño: </div><div><select class="form-control form-font-size">' + size + '</select></div></li>');
	this.content.append(size_content);
	
	size_content.find('.form-font-size').change(function(){
		t.content_zone.updateStyle('font-size', $(this).val() + 'px');
		t.font_size = $(this).val();
	});	
};

ToolsZone.prototype.persist = function() {
	var obj = {
		family: this.family,
		font_size: this.font_size,
		background_color: this.background_color,
		color: this.color,
	};
	
	return obj;
};

ToolsZone.prototype.unpersist = function(obj) {
	this.family = obj.family;
	this.font_size =	obj.font_size;
	this.background_color = obj.background_color; 
	this.color = obj.color; 
};
