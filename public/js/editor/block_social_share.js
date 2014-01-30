function SShareBlock(row) {
	this.row = row;
	this.content_fb = $('<img class="soc_net_share button_facebook" src="' + config.imagesUrl + '/share_facebook_image.png" alt="64x64" />');
	this.content_tw = $('<img class="soc_net_share button_twitter" src="' + config.imagesUrl + '/share_twitter_image.png" alt="64x64" />');
	this.content_li = $('<img class="soc_net_share button_linkedin" src="' + config.imagesUrl + '/share_linkedin_image.png" alt="64x64" />');
	this.content_gp = $('<img class="soc_net_share button_google_plus" src="' + config.imagesUrl + '/share_google_plus_image.png" alt="64x64" />');
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

SShareBlock.prototype.createBlock = function() {
	
	this.content = this.drawHtml();
	
	this.row.content.find('.in-row').append(this.content);
	
	this.editBlock();
	this.removeBlock();
};

SShareBlock.prototype.drawHtml = function() {
	var block = $('<td>\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
										<div class="content-social-share media-object"></div>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	block.find('.content-social-share').append(this.content_fb);
	block.find('.content-social-share').append(this.content_tw);
	block.find('.content-social-share').append(this.content_li);
	block.find('.content-social-share').append(this.content_gp);
	return block;
};

SShareBlock.prototype.editBlock = function() {
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

SShareBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};

SShareBlock.prototype.updateBlockStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

SShareBlock.prototype.updateContentStyle = function(style, value) {
	this.content.find('.content-text').css(style, value);
};