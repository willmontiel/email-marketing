function TxtBlock(row) {
	this.row = row;
}

TxtBlock.prototype.drawHtml = function() {
	this.content = $('<td>\n\
						<div class="one-element">\n\
							<div class="elements-options">\n\
								<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
								<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
							</div>\n\
							<div class="content-text">\n\
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>\n\
							</div>\n\
						</div>\n\
					</td>');
	
	this.row.htmlData.find('tr').append(this.content);
	
	this.editBlock();
	this.removeBlock();
};

TxtBlock.prototype.editBlock = function() {
	var t = this;
	this.row.htmlData.find('td:last .edit-block').on('click', function() {
		var toolbar = new Toolbar(t);
		toolbar.drawHtml();
	});
};

TxtBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.htmlData.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};