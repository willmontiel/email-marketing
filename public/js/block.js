function Block (parentBlock, typeBlock, contentData, htmlData) {
	this.parentBlock = parentBlock;
	this.typeBlock = typeBlock;
	this.contentData = contentData;
	this.htmlData = htmlData;
}

Block.prototype.deleteBlock = function() {
	for (var key in this)
      delete this[key];	
};


Block.prototype.persist = function() {
	return {
		type: this.typeBlock,
		contentData: $('<div/>').html(this.contentData.html()),
		htmlData: this.htmlData.html()
	};
	
};

Block.prototype.unpersist = function(obj, dz) {
	this.typeBlock = obj.type;
	this.parentBlock = dz;
	this.contentData = $('<div/>');
	this.contentData = this.contentData.html(obj.contentData).children();
	
	this.htmlData = $('<div/>').html(
						"<div class=\"" + this.typeBlock + "\" style=\"display: block;\">\n\
						<div class=\"handle-tool icon-move tool\"></div>\
						<div class=\"edit-tool icon-pencil tool\"></div>\
						<div class=\"remove-tool icon-trash tool\"></div>\
						<div class=\"save-tool icon-ok\" style=\"display: none;\"></div>\
						<div class=\"content\"></div></div>").children();
	
	this.htmlData.find('.content').append(this.contentData);
	
};