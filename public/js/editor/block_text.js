function TxtBlock(row) {
	this.row = row;
	this.content_text = $('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>');
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

TxtBlock.prototype.createBlock = function() {
	this.content = this.drawHtml();
	this.row.content.find('.in-row').append(this.content);
	this.updateChanges();
	this.editBlock();
	this.removeBlock();
	this.newRedactor();
};


TxtBlock.prototype.drawHtml = function() {
	var block = $('<td class="in-column">\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element clearfix">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
										<div class="content-text">\n\
										</div>\n\
										<div class="content-text-block"></div>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	block.find('.content-text').append(this.content_text);
	return block;
};

TxtBlock.prototype.editBlock = function() {
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

TxtBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
		removeTextEditor();
		t.row.removeBlock(t);
		t.content.remove();
	});
};

TxtBlock.prototype.updateText = function(text) {
	this.content.find('.content-text').html(text)
};

TxtBlock.prototype.updateBlockStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

TxtBlock.prototype.updateContentStyle = function(style, value) {
	this.content.find('.content-text').css(style, value);
};

TxtBlock.prototype.updateColumnStyle = function(style, value) {
	this.content.css(style, value);
};

TxtBlock.prototype.persist = function() {
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
		content : $.trim(this.content.find('.content-text').html()),
		type : 'Text'
	};
	return obj;
};

TxtBlock.prototype.unpersist = function(obj) {
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
	this.content_text = obj.content;
	
	return this;
};

TxtBlock.prototype.updateChanges = function() {
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
};

TxtBlock.prototype.newRedactor = function() {
	var t = this;
	this.content.find('.content-text-block').on('click', function() {
		$('.element-text-in-edition').removeClass('element-text-in-edition');
		t.content.find('.one-element').addClass('element-text-in-edition');
		t.textToolbar();
	});
};

TxtBlock.prototype.textToolbar = function() {
	$('#my-text-component-toolbar').remove();
	$('.component-toolbar-text').remove();

	var toolbar =  $('<div class="component-toolbar-text" id="my-text-component-toolbar" />');
	$('#edit-area').prepend(toolbar);
	var position = this.content.offset();
	toolbar.css('position', 'absolute');
	toolbar.css('top', position.top + this.content.height() - 50);
	toolbar.css('left', 225);
	toolbar.append('<div class="content-text-toolbar"></div>');
	toolbar.find('.content-text-toolbar').append(this.content.find('.content-text').html());
	
	var t = this;
	$(toolbar.find('.content-text-toolbar')).redactor({
		focus: true,
		buttons: [
			'save', '|', 'formatting', '|', 
			'bold', 'italic', 'deleted', '|', 
			'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 
			'link', '|', 
			'alignment' 
		],

		plugins: ['fontcolor', 'fontfamily', 'fontsize', 'clips', 'advanced'],

		buttonsCustom: {
			save: {
				title: 'Save',
				callback: function() {
					var content = $(toolbar.find('.content-text-toolbar'));
					t.content.find('.content-text').html(content.html());
					content.destroyEditor();
					$('#my-text-component-toolbar').remove();
					$('.redactor-link-tooltip').remove();
					$('.element-text-in-edition').removeClass('element-text-in-edition');
				}
			}
		}
	});
	
	this.content.find('#my-text-component-toolbar').css('top', -20);
	this.content.find('#my-text-component-toolbar').css('left', -100);
};