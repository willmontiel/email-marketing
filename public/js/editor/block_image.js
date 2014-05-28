function ImgBlock(row) {
	this.row = row;
	this.content_img = $('<img data-toggle="modal" data-backdrop="static" href="#images" class="media-object-img image-placeholder" />');
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
		removeTextEditor();
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
		t.setImageGalleryPosition();
		removeTextEditor();
		if($(this).attr('src') === undefined) {
			t.widthZone =  t.content.width();
			media.setBlock(t);
			media.imageSelected(t.content.find('img').attr('src'));
		}
		else {
			t.createImgToolbar();
		}
	});
};

ImgBlock.prototype.createImgToolbar = function() {
	$('#my-img-component-toolbar').remove();
	$('.component-toolbar-image').remove();

	var toolbar =  $('<div class="component-toolbar-image" id="my-img-component-toolbar" />');
	$('#edit-area').prepend(toolbar);
	var position = this.content.offset();
	toolbar.css('position', 'absolute');
//	toolbar.css('top', position.top + this.content.height() - 80);
	toolbar.css('top', position.top - 150);
	toolbar.css('left', 140);
	
	$('.element-img-in-edition').removeClass('element-img-in-edition');
	this.content.find('.one-element').addClass('element-img-in-edition');

	toolbar.append('<ul class="img-components-list"/>');
	
	var slider = $('<input type="text" id="sliderMedia">');
	var elementslider = $('<li class="toolbar-elements" />');
	elementslider.append(slider);
	
	var align = $('<div class="img-align-toolbar-container">\n\
					<div class="img-align-container">\n\
						<div class="align-btn-toolbar align-left"><span class="icon-align-left icon-white"></span></div>\n\
						<div class="align-btn-toolbar align-center"><span class="icon-align-center icon-white"></span></div>\n\
						<div class="align-btn-toolbar align-right"><span class="icon-align-right icon-white"></span></div>\n\
					</div>\n\
					</div>');
	var elementalign = $('<li class="toolbar-elements" />');
	elementalign.append(align);
	
	var verticalalign = $('<div class="img-align-toolbar-container">\n\
					<div class="img-align-container">\n\
						<div class="vert-align-btn-toolbar vert-align-top"><span class="image-sprite image-vertical-align-top"></span></div>\n\
						<div class="vert-align-btn-toolbar vert-align-middle"><span class="image-sprite image-vertical-align-middle"></span></div>\n\
						<div class="vert-align-btn-toolbar vert-align-bottom"><span class="image-sprite image-vertical-align-bottom"></span></span></div>\n\
					</div>\n\
					</div>');
	var elementvertalign = $('<li class="toolbar-elements" />');
	elementvertalign.append(verticalalign);
	
	var imgmedia = $('<div class="media-image-displayer"><div class="media-image-container"><div data-toggle="modal" data-backdrop="static" href="#images" class="media-btn-image-displayer"><span class="icon-picture icon-white"></span></div></div></div>');
	var imglink = $('<div class="link-img-container"><input id="link_to_image" type="text" placeholder="Escriba Link"></div>');
	var elementmedia = $('<li class="toolbar-elements" />');
	elementmedia.append(imgmedia);
	elementmedia.append(imglink);
	
	toolbar.find('.img-components-list').append(elementslider);
	toolbar.find('.img-components-list').append(elementalign);
	toolbar.find('.img-components-list').append(elementvertalign);
	toolbar.find('.img-components-list').append(elementmedia);
	
	this.activateSlider();
	this.activateEvents();
};

ImgBlock.prototype.activateSlider = function() {
	var fullwidth = this.row.dz.widthval/this.row.listofblocks.length;
	var value =  Math.floor(this.width/fullwidth * 100);
	var t = this;
	$('#sliderMedia').slider({min: 10, max: 100, value: value, step: 1})
		.on('slide', function(ev){
		var width = Math.floor(fullwidth*(ev.value/100));
		var height = Math.floor(( width * t.height ) / t.width);
		t.setSizeImage(height, width);
		t.changeAttrImgBlock('width', width);
		t.changeAttrImgBlock('height', height);
	});
};

ImgBlock.prototype.activateEvents = function() {
	var t = this;
	$('.align-btn-toolbar.align-' + this.align).addClass('align-selected-toolbar');
	this.eventAlign('left');
	this.eventAlign('center');
	this.eventAlign('right');
	$('.vert-align-btn-toolbar.vert-align-' + this.vertalign).addClass('vert-align-selected-toolbar');
	this.eventVertAlign('top');
	this.eventVertAlign('middle');
	this.eventVertAlign('bottom');
	$('.media-btn-image-displayer').on('click', function() {
		t.widthZone =  t.content.width();
		media.setBlock(t);
	});
	$('#link_to_image').val(this.imglink);
	$('#link_to_image').on('change', function() {
		t.setLinkToImage($(this).val());
	});
};

ImgBlock.prototype.eventAlign = function(align) {
	var t = this;
	$('.align-btn-toolbar.align-' + align).on('click', function() {
		t.addStyleContentImgBlock('text-align', align);
		t.setAlignImgBlock(align);
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
};

ImgBlock.prototype.eventVertAlign = function(vertalign) {
	var t = this;
	$('.vert-align-btn-toolbar.vert-align-' + vertalign).on('click', function() {
		t.addVerticalAlignToImage(vertalign);
		t.setVerticalAlignImgBlock(vertalign);
		$('.vert-align-selected-toolbar').removeClass('vert-align-selected-toolbar');
		$(this).addClass('vert-align-selected-toolbar');
	});
};

ImgBlock.prototype.changeAttrImgBlock = function(attr, value) {
	this.content.find('img').attr(attr, value);
};

ImgBlock.prototype.setSizeImage = function(height, width) {
	this.height = Math.floor(height);
	this.width = Math.floor(width);
};

ImgBlock.prototype.setImageSrc = function(imgsrc) {
	this.imgsrc = imgsrc;
};

ImgBlock.prototype.setImageAlt = function(imgalt) {
	this.imgalt = imgalt;
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
		imgalt: this.imgalt,
		imglink: this.imglink,
		type : 'Image'
	};
	return obj;
};

ImgBlock.prototype.unpersist = function(object) {
	if(typeof(object) === 'string') {
		var obj = JSON.parse(object);
	}
	else {
		var obj = object;
	}
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
	
	if(obj.imgsrc !== undefined) {
		this.height = obj.height;
		this.width = obj.width;
		this.align = obj.align;
		this.vertalign = obj.vertalign;
		this.imglink = obj.imglink;
		this.imgsrc = obj.imgsrc;
		this.imgalt = obj.imgalt;
		this.content_img = $('<img alt="' + this.imgalt + '" class="media-object-img" src="' + this.imgsrc + '" height="' + this.height + '" width="' + this.width + '" />');
	}
	else {
		delete this.height;
		delete this.width;
		delete this.align;
		delete this.vertalign;
		delete this.imgsrc;
		delete this.imgalt;
		delete this.imglink;
		if(typeof(object) === 'string') {
			this.content.find('img').removeAttr('height');
			this.content.find('img').removeAttr('width');
			this.content.find('img').removeAttr('src');
			this.content.find('img').removeAttr('alt');
			this.content.find('img').addClass('image-placeholder');
			this.content.find('img').attr('data-toggle', 'modal');
			this.content.find('img').attr('data-backdrop', 'static');
			this.content.find('img').attr('href', '#images');
		}
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


ImgBlock.prototype.setImageGalleryPosition = function() {
	var pos = this.content.offset();
	if( pos.top < 330 ) {
		$('#images').css('top', '5%');
	}
	else if((pos.top - 300) + 610 > $(document).height()) {
		$('#images').css('top', $(document).height() - 660);
	}
	else {
		$('#images').css('top', pos.top - 300);
	}
};