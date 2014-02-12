function ImgBlock(row) {
	this.row = row;
	this.content_img = $('<img data-toggle="modal" data-backdrop="static" href="#images" class="media-object-img image-placeholder" />');
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

ImgBlock.prototype.createBlock = function() {
	this.content = this.drawHtml();
	this.row.content.find('.in-row').append(this.content);
	this.editBlock();
	this.removeBlock();
	this.createImage();
};

ImgBlock.prototype.drawHtml = function() {
	var block = $('<td class="in-column">\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	block.find('.one-element').append(this.content_img);
	return block;
};

ImgBlock.prototype.editBlock = function() {
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

ImgBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};

ImgBlock.prototype.updateBlockStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

ImgBlock.prototype.updateContentStyle = function(style, value) {
	this.content.find('img').css(style, value);
};

ImgBlock.prototype.updateColumnStyle = function(style, value) {
	this.content.css(style, value);
};

ImgBlock.prototype.createImage = function() {
	var t = this;
	this.content.find('img').on('click', function() {
		t.widthZone =  t.content.width();
		media.setBlock(t);
		media.imageSelected(t.content.find('img').attr('src'));
	});
};

ImgBlock.prototype.changeAttrImgBlock = function(attr, value) {
	this.content.find('img').attr(attr, value);
};

ImgBlock.prototype.assignDisplayer = function(displayer) {
	this.displayer = displayer;
};

ImgBlock.prototype.setSizeImage = function(height, width) {
	this.height = Math.floor(height);
	this.width = Math.floor(width);
};

ImgBlock.prototype.setImageSrc = function(imgsrc) {
	this.imgsrc = imgsrc;
};

ImgBlock.prototype.setAlignImgBlock = function(align) {
	this.align = align;
};

ImgBlock.prototype.setLinkToImage = function(link) {
	this.imglink = link;
};

ImgBlock.prototype.setVerticalAlignImgBlock = function(vertalign) {
	this.vertalign = vertalign;
};

ImgBlock.prototype.addVerticalAlignToImage = function(vertalign) {
	this.content.css('vertical-align', vertalign);
};

ImgBlock.prototype.setTableColumn = function(name, value) {
	this.content.attr(name, value);
};

ImgBlock.prototype.addStyleContentImgBlock = function(style, value) {
	this.content.find('.one-element').css(style, value);
};

ImgBlock.prototype.persist = function() {
	var obj = {	
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
		height: this.height,
		width: this.width, 
		align: this.align,
		vertalign: this.vertalign,
		imgsrc: this.imgsrc,
		imgalt: this.content.find('img').attr('alt'),
		imglink: this.imglink,
		type : 'Image'
	};

	if(this.displayer !== undefined) {
		obj.srcDisplayer= this.displayer.imagesrc;
		obj.heightDisplayer= this.displayer.height; 
		obj.widthDisplayer= this.displayer.width;
		obj.percent= this.displayer.percent;
	}
	return obj;
};

ImgBlock.prototype.unpersist = function(obj) {
	if(obj !== undefined) {
		if(typeof(obj) === 'string') {
			var obj = JSON.parse(obj);
		}
		else {
			this.displayer = {};
		}
		this.height = obj.height;
		this.width = obj.width;
		this.align = obj.align;
		this.vertalign = obj.vertalign;
		this.imglink = obj.imglink;
		this.imgsrc = obj.imgsrc;
		this.displayer.height = obj.heightDisplayer;
		this.displayer.width = obj.widthDisplayer;
		this.displayer.imagesrc = obj.srcDisplayer;
		this.displayer.percent = obj.percent;
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
		if ( this.imgsrc !== undefined ) {
			this.content_img = $('<img data-toggle="modal" data-backdrop="static" href="#images" alt="' + obj.imgalt + '" class="media-object-img" src="' + this.imgsrc + '" height="' + this.height + '" width="' + this.width + '" />');
		}
	}
	else {
		delete this.height;
		delete this.width;
		delete this.align;
		delete this.vertalign;
		delete this.displayer;
		delete this.imgsrc;
		delete this.imglink;

		this.content.find('img').removeAttr('height');
		this.content.find('img').removeAttr('width');
		this.content.find('img').removeAttr('src');
		this.content.find('img').removeAttr('alt');
		this.content.find('img').addClass('image-placeholder');
	}
	
	return this;
};

ImgBlock.prototype.updateChanges = function() {
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
	this.addStyleContentImgBlock('text-align', this.align);
	this.addVerticalAlignToImage(this.vertalign);
};

ImgBlock.prototype.updateSize = function() {
	if(this.width > this.content.width()) {
		this.setSizeImage((this.height * this.content.width()) / this.width, this.content.width());
		
		this.setSizeImage((this.height * this.content.width()) / this.width, this.content.width());
		
		this.changeAttrImgBlock('width', this.content.width());
		this.changeAttrImgBlock('height', (this.height * this.content.width()) / this.width);
	}
};
