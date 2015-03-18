function ZoneHeader(zone) {
	this.zone = zone;
	this.name = 'Nombre del formulario';
	this.bold = true;
	this.family = 'Arial';
	this.size = '18';
	this.align = 'center';
	this.size_opt = ['8','10','12','14','18','24','36'];
	this.family_opt = ['Arial', 'Courier New', 'Verdana', 'Comic Sans MS', 'Georgia', 'Times New Roman'];
};

ZoneHeader.prototype.designHeaderZone = function() {
	var bold = (this.bold) ? 'bold' : 'normal';
	this.content = $('<div class="field-content-zone field-content-zone-header form-size-one">\n\
						<div class="header-content" style="font-size: '+ this.size +'px; font-family: '+ this.family +'; text-align: '+ this.align +'; font-weight: '+ bold +';">\n\
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
	
	this.startZoneEvents();
};

ZoneHeader.prototype.startZoneEvents = function() {
	var t = this;
	this.content.find('.edit-field').on('click', function(){
		t.zone.editZone(t);	
	});
};

ZoneHeader.prototype.getEditZone = function() {
	var family = '';
	var size = '';
	var bold = '';
	var left = '';
	var	center = '';
	var right = '';
	
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
	zone.designFieldEditZone('');
	zone.designSaveBtn();
	zone.designNameField(this.name);
	var font = zone.designFontOptField(size, family, bold);
	this.fontSpecialEvent(font);
	var align = zone.designFontAlignField(left, center, right);
	this.alignSpecialEvent(align);
	
	var edit = zone.getZone();
	
	return edit;
};

ZoneHeader.prototype.changeValues = function(editzone) {
	var content = this.content.find('.header-content');
	this.name = editzone.find('.field-label-name').val();
	this.size = editzone.find('.field-font-size').val();
	this.family = editzone.find('.field-font-family').val();
	content.text(this.name);
	content.css('font-size', this.size + 'px');
	content.css('font-family', this.family);
	content.css('text-align', this.align);
	
	if(this.bold) {
		content.css('font-weight', 'bold');
	}
	else{
		content.css('font-weight', 'normal');
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
	var obj = {
		name: this.name,
		bold: this.bold,
		family: this.family,
		size: this.size,
		align: this.align
	};
	
	return obj;
};

ZoneHeader.prototype.unpersist = function(obj) {
	this.name = obj.name;
	this.bold = obj.bold;
	this.family = obj.family;
	this.size =	obj.size;
	this.align = obj.align; 
};