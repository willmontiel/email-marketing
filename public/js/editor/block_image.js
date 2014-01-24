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
//	switch (this.parentBlock.width) {
//		case 'full-width':
//			this.widthZone =  550;
//			break;
//		case 'half-width':
//			this.widthZone = 550/2;
//			break;
//		case 'third-width':
//			this.widthZone = Math.floor(550/3);
//			break;
//		case 'twothird-width':
//			this.widthZone = Math.floor(550*2/3);
//			break;
//		default:
//			console.log('3')
			this.widthZone =  550;
//			break;
//	}
	
	media.setBlock(this);
	media.Selected(this.content.find('img').attr('src'));
};