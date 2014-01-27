function ImgBlock(row) {
	this.row = row;
	this.background_color = "000000";
	this.border_width = 0;
	this.border_color = "000000";
	this.corner_top_left = 0;
	this.corner_top_right = 0;
	this.corner_bottom_left = 0;
	this.corner_bottom_right = 0;
	this.margin_top = 0;
	this.margin_bottom = 0;
	this.margin_left = 0;
	this.margin_right = 0;
}

ImgBlock.prototype.drawHtml = function() {
	this.content = $('<td>\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
										<img data-toggle="modal" data-backdrop="static" href="#images" class="media-object image-placeholder" />\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	this.row.content.find('.in-row').append(this.content);
	
	this.editBlock();
	this.removeBlock();
	this.createImage();
};

ImgBlock.prototype.editBlock = function() {
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

ImgBlock.prototype.addClassContentImgBlock = function(value) {
	var content = this.content.find('.one-element');
	content.removeClass('pull-center');
	content.removeClass('pull-left');
	content.removeClass('pull-right');
	content.addClass(value);
};

ImgBlock.prototype.persist = function() {
	var obj = {	height: this.height,
				width: this.width, 
				align: this.align,
				vertalign: this.vertalign,
				imagesrc: this.displayer.imagesrc,
				imglink: this.imglink,
				heightDisplayer: this.displayer.height, 
				widthDisplayer: this.displayer.width, 
				percent: this.displayer.percent };
	return obj;
};

ImgBlock.prototype.unpersist = function(obj) {
	if(obj !== undefined) {
		var objimage = JSON.parse(obj);
		this.height = objimage.height;
		this.width = objimage.width;
		this.align = objimage.align;
		this.vertalign = objimage.vertalign;
		this.imglink = objimage.imglink;
		this.displayer.height = objimage.heightDisplayer;
		this.displayer.width = objimage.widthDisplayer;
		this.displayer.imagesrc = objimage.imagesrc;
		this.displayer.percent = objimage.percent;

		this.changeAttrImgBlock('height', this.height);
		this.changeAttrImgBlock('width', this.width);
		this.changeAttrImgBlock('src', this.displayer.imagesrc);
		this.addClassContentImgBlock(this.align);
		this.addVerticalAlignToImage(this.vertalign);
	}
	else {
		delete this.height;
		delete this.width;
		delete this.align;
		delete this.vertalign;
		delete this.displayer;

		this.content.find('img').removeAttr('height');
		this.content.find('img').removeAttr('width');
		this.content.find('img').removeAttr('src');
		this.content.find('img').removeAttr('alt');
		
		if(this.typeBlock.search('image-only') > 0) {
			this.content.find('img').addClass('image-placeholder');
		}
		else {
			this.content.find('img').addClass('image-text-placeholder');
		}
	}
};