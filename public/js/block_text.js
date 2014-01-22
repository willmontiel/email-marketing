function TxtBlock(row) {
	this.row = row;
}

TxtBlock.prototype.drawHtml = function() {
	this.content = $('<div class="content-text"><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p></div>');
	this.row.htmlData.find('td:last .one-element').append(this.content);
};