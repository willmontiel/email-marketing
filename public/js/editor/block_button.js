function BtnBlock(row) {
	this.row = row;
	this.content_button = '<span data-toggle="modal" data-backdrop="static" href="#buttonaction" class="content-button pull-center" style="background-image:url(\'' + config.imagesUrl + '/btn-blue.png\');border:1px solid #1e3650;border-radius:4px;">Clic Aqui!</span>'
	
	this.btntext = 'Clic Aqui!';
	this.btnlink = '';
	this.btnbgcolor = '#556270';
	this.btntextcolor = '#ffffff';;
	this.btnwithborderradius = true;
	this.btnradius = 4;
	this.btnwithbordercolor = true;
	this.btnbordercolor = '#1e3650';
	this.btnwithbgimage = true;
	this.btnbgimage = 'blue';
	this.btnwidth = 120;
	this.btnheight = 40;
	this.btnalign = 'center';
	this.btnfontsize = 14;
	this.btnfontfamily = 'arial';
	
	this.background_color = "FFFFFF";
	this.border_width = 0;
	this.border_color = "FFFFFF";
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
	
	this.editBlock();
	this.removeBlock();
	
	var t = this;
	this.content.on('click', function() {
		t.createFields();
	});
};

BtnBlock.prototype.drawHtml = function() {
	var block = $('<td>\n\
						<div class="one-element">\n\
							<div class="elements-options">\n\
								<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
								<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
							</div>\n\
						</div>\n\
					</td>');
	block.find('.one-element').append(this.content_button);
	return block;
};

BtnBlock.prototype.editBlock = function() {
	var t = this;
	this.row.content.find('td:last .edit-block').on('click', function(event) {
		var toolbar = new Toolbar(t);
		toolbar.drawHtml();
		toolbar.createBackground();
		toolbar.createBorder();
		toolbar.createCorners();
		toolbar.createMargins();
		
		event.stopPropagation();
	});
};

BtnBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
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

BtnBlock.prototype.persist = function() {
	var obj = {
		text : this.btntext,
		link : this.btnlink,
		bgcolor : this.btnbgcolor,
		textcolor : this.btntextcolor,
		withborderradius : this.btnwithborderradius,
		radius : this.btnradius,
		withbordercolor : this.btnwithbordercolor,
		bordercolor : this.btnbordercolor,
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
	this.btnwithborderradius = obj.withborderradius;
	this.btnradius = obj.radius;
	this.btnwithbordercolor = obj.withbordercolor;
	this.btnbordercolor = obj.bordercolor;
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
	
	this.updateContentStyle('margin-top', this.margin_top);
	this.updateContentStyle('margin-bottom', this.margin_bottom);
	this.updateContentStyle('margin-left', this.margin_left);
	this.updateContentStyle('margin-right', this.margin_right);
	
	this.designBtn();
};

BtnBlock.prototype.createFields = function() {
	
	$('#btntext').val(this.btntext);
	$('#btnlink').val(this.btnlink);
	$('#btnbgcolor').colorpicker('setValue', this.btnbgcolor);
	$('#field-btnbgcolor').val(this.btnbgcolor);
	$('#btntextcolor').colorpicker('setValue', this.btntextcolor);
	$('#field-btntextcolor').val(this.btntextcolor);
	$('#btnradius').val(this.btnradius);
	$('#btnbordercolor').colorpicker('setValue', this.btnbordercolor);
	$('#field-btnbordercolor').val(this.btnbordercolor);
	$('#btnbgimage').val(this.btnbgimage);
	$('#btnwidth').val(this.btnwidth);
	$('#btnheight').val(this.btnheight);
	$('#btnalign').val(this.btnalign);
	$('#btnfontsize').val(this.btnfontsize);
	$('#btnfontfamily').val(this.btnfontfamily);
	$('#withborderradius')[0].checked = this.btnwithborderradius;
	$('#withbordercolor')[0].checked = this.btnwithbordercolor;
	$('#withbgimage')[0].checked = this.btnwithbgimage;

	this.colorField('btnbgcolor');
	this.colorField('btntextcolor');
	this.colorField('btnbordercolor');
	
	var t = this;
	
	$('#savebtndata').off('click');
	
	$('#savebtndata').on('click', function() {
		t.saveBtn();
		t.designBtn();
		$('#savebtndata').off('click');
	});
	
	$('#cancelbtndata').on('click', function() {
		$('#savebtndata').off('click');
	});
};

BtnBlock.prototype.saveBtn = function() {
	this.btntext = $('#btntext').val();
	this.btnlink = $('#btnlink').val();
	this.btnbgcolor = $('#field-btnbgcolor').val();
	this.btntextcolor = $('#field-btntextcolor').val();
	this.btnwithborderradius = $('#withborderradius')[0].checked;
	this.btnradius = $('#btnradius').val();
	this.btnwithbordercolor = $('#withbordercolor')[0].checked;
	this.btnbordercolor = $('#field-btnbordercolor').val();
	this.btnwithbgimage = $('#withbgimage')[0].checked;
	this.btnbgimage = $('#btnbgimage').val();
	this.btnwidth = $('#btnwidth').val();
	this.btnheight = $('#btnheight').val();
	this.btnalign = $('#btnalign').val();
	this.btnfontsize = $('#btnfontsize').val();
	this.btnfontfamily = $('#btnfontfamily').val();
};

BtnBlock.prototype.designBtn = function() {
	var content = this.content.find('.content-button');
	content.text(this.btntext);
	content.css('background-color', this.btnbgcolor);
	content.css('color', this.btntextcolor);

	if(this.btnwithborderradius) {
		content.css('border-radius', this.btnradius);
	}
	else {
		content.css('border-radius', 0);
	}
	
	if(this.btnwithbordercolor) {
		content.css('border-color', this.btnbordercolor);
		content.css('border-style', 'solid');
	}
	else {
		content.css('border-color', '');
		content.css('border-style', '');
	}
	
	if(this.btnwithbgimage) {
		content.css('background-image', 'url(' + config.imagesUrl + '/btn-' + this.btnbgimage + '.png)');
	}
	else {
		content.css('background-image', '');
	}
	
	content.css('width', this.btnwidth);
	content.css('height', this.btnheight);
	content.css('font-size', this.btnfontsize);
	content.css('font-family', this.btnfontfamily);
	
	content.removeClass('pull-center');
	content.removeClass('pull-left');
	content.removeClass('pull-right');
	content.addClass('pull-' + this.btnalign);
};

BtnBlock.prototype.colorField = function(field) {
	$('#field-' + field).on('change', function(){
		$('#' + field).colorpicker('setValue', $(this).val());
	});
};