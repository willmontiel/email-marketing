function TxtBlock(row) {
	this.row = row;
}

TxtBlock.prototype.drawHtml = function() {
	this.content = $('<td>\n\
						<table class="full-block-element" border="0" cellpadding="0">\n\
							<tr>\n\
								<td>\n\
									<div class="one-element">\n\
										<div class="elements-options">\n\
											<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
											<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
										</div>\n\
										<div class="content-text">\n\
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>\n\
										</div>\n\
									</div>\n\
								</td>\n\
							</tr>\n\
						</table>\n\
					</td>');
	
	this.row.htmlData.find('tr').append(this.content);
	
	this.editBlock();
	this.removeBlock();
	
	this.newRedactor();
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
	this.row.htmlData.find('td:last .edit-block').on('click', function(event) {
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
	this.row.htmlData.find('td:last .remove-block').on('click', function() {
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