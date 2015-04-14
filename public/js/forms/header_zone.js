function ZoneHeader(zone) {
	this.zone = zone;
	this.name = 'Nombre del formulario';
	this.bold = true;
	this.family = 'Arial';
	this.size = '24';
	this.align = 'center';
	this.color = '#333333';
	this.img_bg_chk = false;
	this.img_bg_link = '';
	this.size_opt = ['8','10','12','14','18','24','36'];
	this.family_opt = ['Arial', 'Courier New', 'Verdana', 'Comic Sans MS', 'Georgia', 'Times New Roman'];
	this.active = true;
};

ZoneHeader.prototype.desingOptionZone = function(adv_tools) {
	this.adv_tools = adv_tools;
	this.option = $('<li class="edit-form-adv-tools"><div class="btn btn-default btn-sm extra-padding opt-adv-header form-options">Encabezado</div></li>');
	this.adv_tools.addOptInZone(this.option);
	var t = this;
	this.option.on('click', function() {
		if(t.active === false){
			t.active = true;
			t.option.find('.opt-adv-header').addClass('field-option-disabled');
			t.designHeaderZone();
		}
	});
	if(this.active){
		this.option.find('.opt-adv-header').addClass('field-option-disabled');
	}
};

ZoneHeader.prototype.designHeaderZone = function() {
	if(this.active) {
		var bold = (this.bold) ? 'bold' : 'normal';
		var bg_image = (this.img_bg_chk) ? 'url(' + this.img_bg_link + ')' : '';

		this.content = $('<div class="field-content-zone field-content-zone-header form-size-one">\n\
							<div class="header-content" style="font-size: '+ this.size +'px; font-family: '+ this.family +'; text-align: '+ this.align +'; font-weight: '+ bold +'; color: ' + this.color + '; background-image: ' + bg_image + ';">\n\
								<p>' + this.name + '</p>\n\
							</div>\n\
							<div class="form-btns-opt">\n\
								<div class="form-tool edit-field">\n\
									<span class="glyphicon glyphicon-pencil"></span>\n\
								</div>\n\
								<div class="form-tool delete-field">\n\
									<span class="glyphicon glyphicon-trash"></span>\n\
								</div>\n\
							</div>\n\
						</div>');
		$('#header-zone').append(this.content);
		if(this.adv_tools){
			this.updateStyle('background-color', this.adv_tools.background_color);
			this.updateStyle('width', this.adv_tools.size + 'px');
		}
		this.startZoneEvents();
	}
};

ZoneHeader.prototype.startZoneEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editZone(t);	
	});
	
	this.content.find('.delete-field').on('click', function(){
		t.content.remove();
		t.active = false;
		t.option.find('.opt-adv-header').removeClass('field-option-disabled');
	});
};

ZoneHeader.prototype.getEditZone = function() {
	var family = '';
	var size = '';
	var bold = '';
	var left = '';
	var	center = '';
	var right = '';
	var img_selected = '';
	var extended = '';
	
	if(this.bold) {
		bold = 'font-property-btn-selected';
	}
	
	if(this.img_bg_chk){
		img_selected = 'font-property-btn-selected';
		extended = 'form-edit-zone-extended';
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
	zone.designFieldEditZone(extended);
	zone.designSaveBtn();
	zone.designNameField(this.name);
	this.colorSpecialEvent(zone.designColorField('color-title-toolbar'), 'color-title-toolbar');
	this.fontSpecialEvent(zone.designFontOptField(size, family, bold));
	this.alignSpecialEvent(zone.designFontAlignField(left, center, right));
	this.bgimageSpecialEvent(zone.designBgImageField(img_selected, this.img_bg_link));
	
	var edit = zone.getZone();
	
	return edit;
};

ZoneHeader.prototype.colorSpecialEvent = function(color, color_id) {
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

ZoneHeader.prototype.changeValues = function(editzone) {
	var content = this.content.find('.header-content');
	this.name = editzone.find('.field-label-name').val();
	this.size = editzone.find('.field-font-size').val();
	this.family = editzone.find('.field-font-family').val();
	this.img_bg_link = editzone.find('.field-image-bg').val();
	content.text(this.name);
	content.css('font-size', this.size + 'px');
	content.css('font-family', this.family);
	content.css('text-align', this.align);
	content.css('color', this.color);
	
	if(this.img_bg_chk){
		content.css("background-image", "url(" + this.img_bg_link + ")");
	}
	else{
		content.css("background-image", "");
	}
	
	if(this.bold) {
		content.css('font-weight', 'bold');
	}
	else{
		content.css('font-weight', 'normal');
	}
};

ZoneHeader.prototype.updateStyle = function(property, value) {
	if(this.content){
		this.content.css(property, value);
	}
};

ZoneHeader.prototype.alignSpecialEvent = function(align) {
	var t = this;
	align.find('.align-toolbar-form').off('click');
	align.find('.align-toolbar-form').on('click', function(){
		t.align = $(this).find('input').attr('val');
		align.find('.font-property-btn-selected').removeClass('font-property-btn-selected');
		$(this).addClass('font-property-btn-selected');
	});
};

ZoneHeader.prototype.bgimageSpecialEvent = function(bgimage) {
	var t = this;
	if(t.img_bg_chk) {
		bgimage.find('.form_toolbar_bgimg_container').show();
	}
	bgimage.find('.bgimg_toolbar_form').off('click');
	bgimage.find('.bgimg_toolbar_form').on('click', function(){
		if(t.img_bg_chk) {
			t.img_bg_chk = false;
			bgimage.find('.font-property-btn-selected').removeClass('font-property-btn-selected');
			bgimage.find('.form_toolbar_bgimg_container').hide();
			$('.field-edit-zone-row').removeClass('form-edit-zone-extended');
		}
		else{
			t.img_bg_chk = true;
			$('.field-edit-zone-row').addClass('form-edit-zone-extended');
			bgimage.find('.font-property-btn').addClass('font-property-btn-selected');
			bgimage.find('.form_toolbar_bgimg_container').show();
		}
	});
};

ZoneHeader.prototype.fontSpecialEvent = function(font) {
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

ZoneHeader.prototype.persist = function() {
	var bold = 'normal';
	if(this.bold) {
		bold = 'bold';
	}
	
	var obj = {
		name: this.name,
		bold: bold,
		family: this.family,
		size: this.size,
		align: this.align,
		color: this.color,
		img_bg_chk: this.img_bg_chk,
		img_bg_link: this.img_bg_link,
		active: this.active
	};
	
	return obj;
};

ZoneHeader.prototype.unpersist = function(obj) {
	if(obj.bold === 'normal') {
		this.bold = false;
	}
	
	this.name = obj.name;
	this.family = obj.family;
	this.size =	obj.size;
	this.align = obj.align; 
	this.color = obj.color; 
	this.img_bg_chk = obj.img_bg_chk; 
	this.img_bg_link = obj.img_bg_link; 
	this.active = obj.active; 
};