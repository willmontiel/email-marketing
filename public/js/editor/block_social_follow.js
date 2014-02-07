function SFollowBlock(row) {
	this.row = row;
	this.content_fb = {html: $('<td class="soc_net_follow_container"><img class="soc_net_follow button_facebook" src="' + config.imagesUrl + '/follow_facebook_image.png" alt="64x64" /><p class="follow-text-container-fb">Facebook</p></td>'), selected: true, text: 'Facebook'};
	this.content_tw = {html: $('<td class="soc_net_follow_container"><img class="soc_net_follow button_twitter" src="' + config.imagesUrl + '/follow_twitter_image.png" alt="64x64" /><p class="follow-text-container-tw">Twitter</p></td>'), selected: true, text: 'Twitter'};
	this.content_li = {html: $('<td class="soc_net_follow_container"><img class="soc_net_follow button_linkedin" src="' + config.imagesUrl + '/follow_linkedin_image.png" alt="64x64" /><p class="follow-text-container-li">LinkedIn</p></td>'), selected: true, text: 'LinkedIn'};
	this.content_gp = {html: $('<td class="soc_net_follow_container"><img class="soc_net_follow button_google_plus" src="' + config.imagesUrl + '/follow_google_plus_image.png" alt="64x64" /><p class="follow-text-container-gp">Google+</p></td>'), selected: true, text: 'Google+'};
	this.align = 'left';
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

SFollowBlock.prototype.createBlock = function() {
	this.content = this.drawHtml();
	this.row.content.find('.in-row').append(this.content);
	this.editBlock();
	this.removeBlock();
	
	var t = this;
	this.content.find('.content-social-follow').on('click', function() {
		t.createToolbar();
	});
};

SFollowBlock.prototype.drawHtml = function() {
	var block = $('<td class="in-column">\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element clearfix">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
										<table class="content-social-follow media-object"><tr></tr></table>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	block.find('.content-social-follow tr').append(this.content_fb.html);
	block.find('.content-social-follow tr').append(this.content_tw.html);
	block.find('.content-social-follow tr').append(this.content_li.html);
	block.find('.content-social-follow tr').append(this.content_gp.html);
	block.find('.content-social-follow').css('text-align', this.align);
	return block;
};

SFollowBlock.prototype.editBlock = function() {
	var t = this;
	this.row.content.find('.row-options .in-row > td:last .edit-block').on('click', function(event) {
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

SFollowBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('.row-options .in-row > td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};

SFollowBlock.prototype.updateBlockStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

SFollowBlock.prototype.updateContentStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

SFollowBlock.prototype.updateColumnStyle = function(style, value) {
	this.content.css(style, value);
};

SFollowBlock.prototype.persist = function() {
	var content = [];
	content.push({socialname: 'Facebook', selected: this.content_fb.selected, text: this.content_fb.text});
	content.push({socialname: 'Twitter', selected: this.content_tw.selected, text: this.content_tw.text});
	content.push({socialname: 'LinkedIn', selected: this.content_li.selected, text: this.content_li.text});
	content.push({socialname: 'Google Plus', selected: this.content_gp.selected, text: this.content_gp.text});
	
	var obj = {
		contentData : content,
		align: this.align,
		
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
		type : 'Social-Follow'
	};
	return obj;
};

SFollowBlock.prototype.unpersist = function(obj) {
	this.align = obj.align;
	this.content_fb.text = obj.contentData[0].text;
	this.content_fb.selected = obj.contentData[0].selected;
	this.content_tw.text = obj.contentData[1].text;
	this.content_tw.selected = obj.contentData[1].selected;
	this.content_li.text = obj.contentData[2].text;
	this.content_li.selected = obj.contentData[2].selected;
	this.content_gp.text = obj.contentData[3].text;
	this.content_gp.selected = obj.contentData[3].selected;
	
	this.background_color = obj.background_color;
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

SFollowBlock.prototype.updateChanges = function() {
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
	
	this.content.find('.content-social-follow').attr('align', this.align);
	if(!this.content_fb.selected) {
		this.content_fb.html.hide();
	}
	if(!this.content_tw.selected) {
		this.content_tw.html.hide();
	}
	if(!this.content_li.selected) {
		this.content_li.html.hide();
	}
	if(!this.content_gp.selected) {
		this.content_gp.html.hide();
	}
};

SFollowBlock.prototype.createToolbar = function() {
	$('#my-social-follow-component-toolbar').remove();
	$('.component-toolbar-social').remove();

	var toolbar =  $('<div class="component-toolbar-social" id="my-social-follow-component-toolbar" />');
	$('#edit-area').prepend(toolbar);
	var position = this.content.offset();
	toolbar.css('position', 'absolute');
	toolbar.css('top', position.top + this.content.height() - 20);
	toolbar.css('left', 177);
	
	$('.element-follow-in-edition').removeClass('element-follow-in-edition');
	this.content.find('.one-element').addClass('element-follow-in-edition');
	
	toolbar.append('<table><tr><td class="first_row"><ul class="first_elements"></ul></td></tr></table>');

	var withfb = (this.content_fb.selected) ? 'checked' : '';
	var withtw = (this.content_tw.selected) ? 'checked' : '';
	var withli = (this.content_li.selected) ? 'checked' : '';
	var withgp = (this.content_gp.selected) ? 'checked' : '';
	var fb = $('<div class="full-container-social-network-toolbar"><div class="social_follow_net_container fb-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-fb-toolbar" ' + withfb + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-fb-toolbar"><img class="soc_net_follow button_facebook" src="' + config.imagesUrl + '/follow_facebook_image.png" alt="64x64" /></label></div></div><div class="social-follow-text"><input type="text" id="text-container-for-fb-follow-toolbar" value="' + this.content_fb.text + '"></div></div>');
	var tw = $('<div class="full-container-social-network-toolbar"><div class="social_follow_net_container tw-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-tw-toolbar" ' + withtw + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-tw-toolbar"><img class="soc_net_follow button_twitter" src="' + config.imagesUrl + '/follow_twitter_image.png" alt="64x64" /></label></div></div><div class="social-follow-text"><input type="text" id="text-container-for-tw-follow-toolbar" value="' + this.content_tw.text + '"></div></div>');
	var li = $('<div class="full-container-social-network-toolbar"><div class="social_follow_net_container li-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-li-toolbar" ' + withli + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-li-toolbar"><img class="soc_net_follow button_linkedin" src="' + config.imagesUrl + '/follow_linkedin_image.png" alt="64x64" /></label></div></div><div class="social-follow-text"><input type="text" id="text-container-for-li-follow-toolbar" value="' + this.content_li.text + '"></div></div>');
	var gp = $('<div class="full-container-social-network-toolbar"><div class="social_follow_net_container gp-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-gp-toolbar" ' + withgp + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-gp-toolbar"><img class="soc_net_follow button_google_plus" src="' + config.imagesUrl + '/follow_google_plus_image.png" alt="64x64" /></label></div></div><div class="social-follow-text"><input type="text" id="text-container-for-gp-follow-toolbar" value="' + this.content_gp.text + '"></div></div>');
	var elements = $('<li class="toolbar-elements" />');
	elements.append(fb);
	elements.append(tw);
	elements.append(li);
	elements.append(gp);
	toolbar.find('.first_row ul').append(elements);
	

	var align = $('<div class="social-align-toolbar-container">\n\
					<div class="social-align-container">\n\
						<div class="align-btn-toolbar align-left"><span class="icon-align-left icon-white"></span></div>\n\
						<div class="align-btn-toolbar align-center"><span class="icon-align-center icon-white"></span></div>\n\
						<div class="align-btn-toolbar align-right"><span class="icon-align-right icon-white"></span></div>\n\
					</div>\n\
					</div>');
	var elements = $('<li class="toolbar-elements" />');
	elements.append(align);
	toolbar.find('.first_row ul').append(elements);	
	
	this.eventsChange();
};

SFollowBlock.prototype.eventsChange = function() {
	var t = this;
	this.withSocialNetwork('with-fb-toolbar', 'fb');
	this.withSocialNetwork('with-tw-toolbar', 'tw');
	this.withSocialNetwork('with-li-toolbar', 'li');
	this.withSocialNetwork('with-gp-toolbar', 'gp');
	
	$('.align-btn-toolbar.align-' + this.align).addClass('align-selected-toolbar')
	$('.align-btn-toolbar.align-left').on('click', function() {
		t.content.find('.content-social-follow').attr('align', 'left');
		t.align = 'left';
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
	$('.align-btn-toolbar.align-center').on('click', function() {
		t.content.find('.content-social-follow').attr('align', 'center');
		t.align = 'center';
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
	$('.align-btn-toolbar.align-right').on('click', function() {
		t.content.find('.content-social-follow').attr('align', 'right');
		t.align = 'right';
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
	
	this.SocialFollowText('fb');
	this.SocialFollowText('tw');
	this.SocialFollowText('li');
	this.SocialFollowText('gp');
	
};

SFollowBlock.prototype.SocialFollowText = function(id) {
	var t = this;
	$('#text-container-for-' + id + '-follow-toolbar').on('change', function() {
		t.content.find('.follow-text-container-' + id).text($(this).val());
		t['content_' + id].text = $(this).val();
	});
};

SFollowBlock.prototype.withSocialNetwork = function(id, container) {
	var t = this;
	$('#' + id).on('change', function() {
		if($(this)[0].checked) {
			$('.' + container + '-container-in-toolbar').addClass('social-network-selected');
			t['content_'+container].html.show();
		}
		else {
			$('.' + container + '-container-in-toolbar').removeClass('social-network-selected');
			t['content_'+container].html.hide();
		}
		t['content_'+container].selected = $(this)[0].checked;
	});
	
	if(t['content_'+container].selected) {
		$('.' + container + '-container-in-toolbar').addClass('social-network-selected');
	}
	else {
		$('.' + container + '-container-in-toolbar').removeClass('social-network-selected');
	}
};