function Block (parentBlock, typeBlock, contentData, id, htmlData) {
	this.parentBlock = parentBlock;
	this.typeBlock = typeBlock;
	this.contentData = contentData;
	this.id = id;
	this.htmlData = htmlData;
}

Block.prototype.deleteBlock = function() {
	for (var key in this)
      delete this[key];	
}
