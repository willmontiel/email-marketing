function SShareBlock(row) {
	this.row = row;
}

SShareBlock.prototype.drawHtml = function() {
	this.content = $('<td>\n\
						<div class="one-element">\n\
							<div class="elements-options">\n\
								<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
								<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
							</div>\n\
							\n\
						</div>\n\
					</td>');
	this.row.htmlData.find('tr').append(this.content);
	
	this.editBlock();
	this.removeBlock();
};

SShareBlock.prototype.editBlock = function() {
	var t = this;
	this.row.htmlData.find('td:last .edit-block').on('click', function() {
		
	});
};

SShareBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.htmlData.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};