function SShareBlock(row) {
	this.row = row;
	this.content_fb = {html: $('<img class="soc_net_share button_facebook" src="' + config.imagesUrl + '/share_facebook_image_32.png" alt="64x64" />'), selected: true};
	this.content_tw = {html: $('<img class="soc_net_share button_twitter" src="' + config.imagesUrl + '/share_twitter_image_32.png" alt="64x64" />'), selected: true};
	this.content_li = {html: $('<img class="soc_net_share button_linkedin" src="' + config.imagesUrl + '/share_linkedin_image_32.png" alt="64x64" />'), selected: true};
	this.content_gp = {html: $('<img class="soc_net_share button_google_plus" src="' + config.imagesUrl + '/share_google_plus_image_32.png" alt="64x64" />'), selected: true};
	this.align = 'left';
	this.size = '32';
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
	
	var t = this;
	this.content.find('.content-social-share').on('click', function() {
		t.createToolbar();
	});
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
	block.find('.content-social-share').append(this.content_fb.html);
	block.find('.content-social-share').append(this.content_tw.html);
	block.find('.content-social-share').append(this.content_li.html);
	block.find('.content-social-share').append(this.content_gp.html);
	block.find('.content-social-share').css('text-align', this.align);
	return block;
};

SShareBlock.prototype.editBlock = function() {
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

SShareBlock.prototype.persist = function() {
	var content = [];
	content.push({socialname: 'Facebook', selected: this.content_fb.selected});
	content.push({socialname: 'Twitter', selected: this.content_tw.selected});
	content.push({socialname: 'LinkedIn', selected: this.content_li.selected});
	content.push({socialname: 'Google Plus', selected: this.content_gp.selected});
	
	var obj = {
		contentData : content,
		align: this.align,
		size: this.size,
	
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
		type : 'Social-Share'
	};
	return obj;
};

SShareBlock.prototype.unpersist = function(obj) {
	this.align = obj.align;
	this.size = obj.size;
	
	for(var i=0; i < obj.contentData.length; i++) {
		switch (obj.contentData[i].socialname) {
			case 'Facebook':
				this.content_fb.selected = obj.contentData[i].selected;
				this.content_fb.html.attr('src', config.imagesUrl + '/share_facebook_image_' + this.size + '.png');
				if(!this.content_fb.selected) {
					this.content_fb.html.hide();
				}
				break;
			case 'Twitter':
				this.content_tw.selected = obj.contentData[i].selected;
				this.content_tw.html.attr('src', config.imagesUrl + '/share_twitter_image_' + this.size + '.png');
				if(!this.content_tw.selected) {
					this.content_tw.html.hide();
				}
				break;
			case 'LinkedIn':
				this.content_li.selected = obj.contentData[i].selected;
				this.content_li.html.attr('src', config.imagesUrl + '/share_linkedin_image_' + this.size + '.png');
				if(!this.content_li.selected) {
					this.content_li.html.hide();
				}
				break;
			case 'Google Plus':
				this.content_gp.selected = obj.contentData[i].selected;
				this.content_gp.html.attr('src', config.imagesUrl + '/share_google_plus_image_' + this.size + '.png');
				if(!this.content_gp.selected) {
					this.content_gp.html.hide();
				}
				break;
		}				
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
	
	return this;
};

SShareBlock.prototype.updateChanges = function() {
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
};

SShareBlock.prototype.createToolbar = function() {
	$('#my-social-share-component-toolbar').remove();

	var toolbar =  $('.component-toolbar-social').clone().attr('id', 'my-social-share-component-toolbar');
	toolbar.empty();
	toolbar.show();
	this.content.find('.one-element').append(toolbar);
	
	toolbar.append('<table><tr><td class="first_row"><ul class="first_elements"></ul></td></tr></table>');

	var withfb = (this.content_fb.selected) ? 'checked' : '';
	var withtw = (this.content_tw.selected) ? 'checked' : '';
	var withli = (this.content_li.selected) ? 'checked' : '';
	var withgp = (this.content_gp.selected) ? 'checked' : '';
	var fb = $('<div class="social_share_net_container fb-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-fb-toolbar" ' + withfb + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-fb-toolbar"><img class="soc_net_share button_facebook" src="' + config.imagesUrl + '/share_facebook_image_32.png" alt="64x64" /></label></div></div>');
	var tw = $('<div class="social_share_net_container tw-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-tw-toolbar" ' + withtw + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-tw-toolbar"><img class="soc_net_share button_twitter" src="' + config.imagesUrl + '/share_twitter_image_32.png" alt="64x64" /></label></div></div>');
	var li = $('<div class="social_share_net_container li-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-li-toolbar" ' + withli + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-li-toolbar"><img class="soc_net_share button_linkedin" src="' + config.imagesUrl + '/share_linkedin_image_32.png" alt="64x64" /></label></div></div>');
	var gp = $('<div class="social_share_net_container gp-container-in-toolbar social-network-selected"><div class="with-social-net"><input type="checkbox" id="with-gp-toolbar" ' + withgp + '></div><div class="social_img_container"><label class="not-label-bottom" for="with-gp-toolbar"><img class="soc_net_share button_google_plus" src="' + config.imagesUrl + '/share_google_plus_image_32.png" alt="64x64" /></label></div></div>');
	var elements = $('<li class="toolbar-elements" />');
	elements.append(fb);
	elements.append(tw);
	elements.append(li);
	elements.append(gp);
	toolbar.find('.first_row ul').append(elements);
	
	
	var size = $('<div class="social-size-toolbar-container"><div class="social-toolbar-title">Tamaño</div>\n\
					<div class="medium-large-select social-size-container">\n\
						<select id="size-social-options-toolbar">\n\
							<option value="16" selected>Pequeño</option>\n\
							<option value="24">Mediano</option>\n\
							<option value="32" selected>Grande</option>\n\
							<option value="128">Gigante</option>\n\
						</select>\n\
					</div>\n\
					</div></div>');
	var align = $('<div class="social-align-toolbar-container">\n\
					<div class="social-align-container">\n\
						<div class="align-btn-toolbar align-left"><span class="icon-align-left icon-white"></span></div>\n\
						<div class="align-btn-toolbar align-center"><span class="icon-align-center icon-white"></span></div>\n\
						<div class="align-btn-toolbar align-right"><span class="icon-align-right icon-white"></span></div>\n\
					</div>\n\
					</div>');
	var elements = $('<li class="toolbar-elements" />');
	elements.append(size);
	elements.append(align);
	toolbar.find('.first_row ul').append(elements);	
	
	var position = this.content.find('.one-element').position();
	toolbar.css('top', position.top + this.content.find('.one-element').height() - 8);
	toolbar.css('left', position.left - 136);
	
	this.eventsChange();
};

SShareBlock.prototype.eventsChange = function() {
	var t = this;
	this.withSocialNetwork('with-fb-toolbar', 'fb');
	this.withSocialNetwork('with-tw-toolbar', 'tw');
	this.withSocialNetwork('with-li-toolbar', 'li');
	this.withSocialNetwork('with-gp-toolbar', 'gp');
	
	$('.align-btn-toolbar.align-' + this.align).addClass('align-selected-toolbar')
	$('.align-btn-toolbar.align-left').on('click', function() {
		t.content.find('.content-social-share').css('text-align', 'left');
		t.align = 'left';
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
	$('.align-btn-toolbar.align-center').on('click', function() {
		t.content.find('.content-social-share').css('text-align', 'center');
		t.align = 'center';
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
	$('.align-btn-toolbar.align-right').on('click', function() {
		t.content.find('.content-social-share').css('text-align', 'right');
		t.align = 'right';
		$('.align-selected-toolbar').removeClass('align-selected-toolbar');
		$(this).addClass('align-selected-toolbar');
	});
	
	$('#size-social-options-toolbar').val(this.size);
	$('#size-social-options-toolbar').on('change', function() {
		var imgs = t.content.find('.content-social-share img');
		for (var i=0; i < imgs.length; i++ ) {
			var src = $(imgs[i]).attr('src');
			var newsrc = src.replace(t.size, $(this).val());
			t.content.find('.content-social-share img[src="' + src + '"]').attr('src', newsrc);
		}
		t.size = $(this).val();
	});
};

SShareBlock.prototype.withSocialNetwork = function(id, container) {
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