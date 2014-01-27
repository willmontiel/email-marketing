function TxtBlock(row) {
	this.row = row;
	this.content_text = $('<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>');
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

TxtBlock.prototype.createBlock = function() {
	
	this.content = this.drawHtml();
	
	this.row.content.find('.in-row').append(this.content);
	
	this.editBlock();
	this.removeBlock();
	
	this.newRedactor();
};


TxtBlock.prototype.drawHtml = function() {
	var block = $('<td>\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
										<div class="content-text">\n\
										</div>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	block.find('.content-text').append(this.content_text)
	return block;
	
};

TxtBlock.prototype.newRedactor = function() {
	var st = this;
	this.content.find('.content-text').on('click', function() {
		var t = this;
		
		if (!$(t).hasClass('redactor_editor')) {
			
			$('.redactor_editor').destroyEditor();
			
			$(t).redactor({
				focus: true,
				buttons: [
					'save', '|', 'formatting', '|', 
					'bold', 'italic', 'deleted', '|', 
					'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 
					'link', '|', 
					'alignment' 
				],

				plugins: ['fontcolor', 'fontfamily', 'fontsize', 'clips'],

				buttonsCustom: {
					save: {
						title: 'save',
						callback: function() {
							$(t).destroyEditor();
						}
					}
				}
			});
		}
		
		var component = st.content.find('.one-element');
		var position = component.position();
		st.content.find('.redactor_toolbar').css('top', position.top - 13);
		st.content.find('.redactor_toolbar').css('left', position.left - 14);
	});
};

TxtBlock.prototype.editBlock = function() {
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

TxtBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};

TxtBlock.prototype.updateBlockStyle = function(style, value) {
	this.content.find('.full-block-element').css(style, value);
};

TxtBlock.prototype.updateContentStyle = function(style, value) {
	this.content.find('.content-text').css(style, value);
};

TxtBlock.prototype.persist = function() {
	var obj = {
		background_color : this.background_color,
		border_width : this.border_width,
		border_color : this.border_color,
		corner_top_left : this.corner_top_left,
		corner_top_right : this.corner_top_right,
		corner_bottom_left : this.corner_bottom_left,
		corner_bottom_right : this.corner_bottom_right,
		margin_top : this.margin_top,
		margin_bottom : this.margin_bottom,
		margin_left : this.margin_left,
		margin_right : this.margin_right,
		content : this.content.find('.content-text').html(),
		type : 'text'
	};
	return obj;
};

TxtBlock.prototype.unpersist = function(obj) {
	this.background_color = obj.background_color,
	this.border_width = obj.border_width;
	this.border_color = obj.border_color;
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