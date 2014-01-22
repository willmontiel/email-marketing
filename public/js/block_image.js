function ImgBlock(row) {
	this.row = row;
}

ImgBlock.prototype.drawHtml = function() {
	this.content = $('<img data-toggle="modal" data-backdrop="static" href="#images" class="media-object image-placeholder" />');
	this.row.htmlData.find('td:last .one-element').append(this.content);
};
