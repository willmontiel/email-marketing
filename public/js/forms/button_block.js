function ButtonBlock(zone) {
	this.zone = zone;
	this.name = 'Aceptar';
	this.align = 'center';
//	this.width = '94';
//	this.height = '30';
	this.color = '#ffffff';
	this.size = '12';
	this.family = 'Arial';
	this.bold = false;
	this.bckg_color = '#FF6E00';
	this.size_opt = ['8','10','12','14','18','24','36'];
	this.family_opt = ['Arial', 'Courier New', 'Verdana', 'Comic Sans MS', 'Georgia', 'Times New Roman'];
};

ButtonBlock.prototype.designButtonField = function() {
	var bold = (this.bold) ? 'bold' : 'normal';
	var button = '<div id="btn-content-form" style="text-align: ' + this.align + '"><input type="submit" name="submit" id="form-button-name" class="container-form-button-name btn btn-guardar extra-padding btn-sm" style="color: ' + this.color + '; background-color: ' + this.bckg_color + '; font-size: ' + this.size + 'px; font-family: ' + this.family + '; font-weight: ' + bold + ';" value="' + this.name + '" ></div>';
	
	this.content= $('<div class="form-field form-field-btn form-size-one">\n\
						<div class="form-group field-content-zone">\n\
								' + button + '\n\
							<div class="form-btns-opt">\n\
								<div class="form-tool edit-field">\n\
									<span class="glyphicon glyphicon-pencil"></span>\n\
								</div>\n\
							</div>\n\
						</div>\n\
					</div>');

	$('.form-full-button').append(this.content);
	
	this.startButtonEvents();
};

ButtonBlock.prototype.startButtonEvents = function() {
	var t = this;
	
	this.content.find('input').on('click', function(e){
		e.preventDefault();
	});
	
	this.content.find('.edit-field').on('click', function(){
		t.zone.editZone(t);	
	});

};

ButtonBlock.prototype.changeValues = function(editzone) {
	var content = this.content.find('#btn-content-form');
	var btn = content.find('input');
	
	this.name = editzone.find('.field-label-name').val();
	this.size = editzone.find('.field-font-size').val();
	this.family = editzone.find('.field-font-family').val();
	this.color = (this.pos_color) ? this.pos_color : this.color;
	this.bckg_color = (this.pos_bckg_color) ? this.pos_bckg_color : this.bckg_color;
	
	content.css('text-align', this.align);
	btn.val(this.name);
	btn.css('font-size', this.size + 'px');
	btn.css('font-family', this.family);
	btn.css('color', this.color);
	btn.css('background-color', this.bckg_color);
//	btn.css('width', this.width + 'px');
//	btn.css('height', this.height + 'px');
	
	if(this.bold) {
		btn.css('font-weight', 'bold');
	}
	else{
		btn.css('font-weight', 'normal');
	}
};

ButtonBlock.prototype.updateStyle = function(property, value) {
	this.content.css(property, value);
};

ButtonBlock.prototype.getEditZone = function() {
	var family = '';
	var size = '';
	var bold = '';
	var left = '';
	var	center = '';
	var right = '';
	var color_id = 'color-btn-toolbar';
	var bckg_id = 'color-btn-background-toolbar';
	var width_id = 'btn-width-toolbar';
//	var height_id = 'btn-height-toolbar';
	
	if(this.bold) {
		bold = 'font-property-btn-selected';
	}
	
	for (var i = 0; i < this.size_opt.length; i++) {
		var selected_size = (this.size_opt[i] === this.size) ? 'selected' : '';
		size+= '<option value="' + this.size_opt[i] + '" ' + selected_size + '>' + this.size_opt[i] + '</option>';
	}
	
	for (var i = 0; i < this.family_opt.length; i++) {
		var selected_family = (this.family_opt[i] === this.family) ? 'selected' : '';
		family+= '<option value="' + this.family_opt[i] + '" ' + selected_family + '>' + this.family_opt[i] + '</option>';
	}
	
	switch(this.align) {
		case 'left':
			left = 'font-property-btn-selected';
			break;
		case 'center':
			center = 'font-property-btn-selected';
			break;
		case 'right':
			right = 'font-property-btn-selected';
			break;
	}
	
	var zone = new ZoneCreator();
	zone.designFieldEditZone('form-edit-zone-extended');
	zone.designSaveBtn();
	zone.designNameField(this.name);
	this.colorSpecialEvent(zone.designColorField(color_id), color_id);
	this.fontSpecialEvent(zone.designFontOptField(size, family, bold));
	this.alignSpecialEvent(zone.designFontAlignField(left, center, right));
	this.backgroundSpecialEvent(zone.designColorField(bckg_id), bckg_id);
	this.spinnerSpecialEvent(zone.designSpinnerField(width_id, this.width), width_id, 'width');
//	this.spinnerSpecialEvent(zone.designSpinnerField(height_id, this.height), height_id, 'height');
	var edit = zone.getZone();
	
	return edit;
};

ButtonBlock.prototype.alignSpecialEvent = function(align) {
	var t = this;
	align.find('.align-toolbar-form').off('click');
	align.find('.align-toolbar-form').on('click', function(){
		t.align = $(this).find('input').attr('val');
		align.find('.font-property-btn-selected').removeClass('font-property-btn-selected');
		$(this).addClass('font-property-btn-selected');
	});
};

ButtonBlock.prototype.fontSpecialEvent = function(font) {
	var t = this;
	font.find('.font-bold-form').off('click');
	font.find('.font-bold-form').on('click', function(){
		if(t.bold){
			$(this).removeClass('font-property-btn-selected');
			t.bold = false;
		}
		else{
			$(this).addClass('font-property-btn-selected');
			t.bold = true;
		}
	});
};

ButtonBlock.prototype.colorSpecialEvent = function(color, color_id) {
	var t = this;
	color.find('#' + color_id).spectrum({
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
			t.pos_color = color.toHexString();
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

ButtonBlock.prototype.backgroundSpecialEvent = function(bckg, bckg_id) {
	var t = this;
	bckg.find('#' + bckg_id).spectrum({
		color: t.bckg_color,
		flat: false,
		showInput: true,
		className: "full-spectrum",
		showInitial: true,
		showPalette: true,
		showSelectionPalette: true,
		maxPaletteSize: 10,
		preferredFormat: "hex",
		change: function(color) {
			t.pos_bckg_color = color.toHexString();
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

ButtonBlock.prototype.spinnerSpecialEvent = function(obj, id, property) {
	var t = this;
	obj.find('#' + id).spinner({min: 30, max: 500,
		stop: function() {
			t[property] = $(this).val();
		}
	});
};

ButtonBlock.prototype.checkIfCanSave = function(editzone) {
	if(editzone.find('.field-hide-option').length > 0 && editzone.find('.field-hide-option')[0].checked && editzone.find('.field-default-value').val() === '') {
		return false;
	}
	return true;
};

ButtonBlock.prototype.persist = function() {
	var bold = 'normal';
	if(this.bold) {
		bold = 'bold';
	}
	
	var obj = {
		name: this.name,
		align: this.align,
		color: this.color,
		size: this.size,
		family: this.family,
		bold: bold,
		bckg_color: this.bckg_color
	};
	
	return obj;
};

ButtonBlock.prototype.unpersist = function(obj) {
	if(obj.bold === 'bold') {
		this.bold = true;
	}
	
	this.name = obj.name;
	this.align = obj.align;
	this.color = obj.color;
	this.size = obj.size;
	this.family = obj.family;
	this.bckg_color = obj.bckg_color;
};

