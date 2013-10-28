function Block (parentBlock, typeBlock, contentData, htmlData) {
	this.parentBlock = parentBlock;
	this.typeBlock = typeBlock;
	this.contentData = contentData;
	this.htmlData = htmlData;
	
	newRedactor();
}

function newRedactor() {
	
	$('.content-text').redactor({
        air: true,
		airButtons: [
			'formatting', '|', 
			'bold', 'italic', 'deleted', '|', 
			'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 
			'link', '|', 
			'alignment'
		],
		
		plugins: ['clips', 'fontcolor', 'fontfamily', 'fontsize']
    });
}

Block.prototype.deleteBlock = function() {
	for (var key in this)
      delete this[key];	
};

Block.prototype.setHtmlData = function(htmlData) {
	
	this.htmlData = htmlData;
	
	this.htmlData.append(this.contentData);	
	
	newRedactor();
};

Block.prototype.persist = function() {
	
	var obj = {
			type: this.typeBlock,
			contentData: $('<div/>').html(this.contentData).html(),
			htmlData: this.htmlData.html()
		};
	
	if(this.hasOwnProperty('height') && this.hasOwnProperty('width')) {
		obj.height = this.height;
		obj.width = this.width;
		obj.displayer = this.displayer;
	}
	
	return obj;
};

Block.prototype.unpersist = function(obj, dz) {
	
	if(obj.hasOwnProperty('height') && obj.hasOwnProperty('width') && obj.hasOwnProperty('displayer')) {
		this.displayer = obj.displayer;
		this.height = obj.height;
		this.width = obj.width;
	}
	
	this.typeBlock = obj.type;
	this.parentBlock = dz;
	this.contentData = $('<div/>');
	this.contentData = this.contentData.html(obj.contentData).children();
	
	this.htmlData = $('<div/>').html(
						"<div class=\"" + this.typeBlock + "\" style=\"display: block;\">\n\
						<div class=\"tools\" style=\"float: left;\">\
							<div class=\"handle-tool icon-move tool\"></div>\
							<div class=\"remove-tool icon-trash tool\"></div>\
						</div>\
						<div class=\"content clearfix\"></div></div>").children();
	
	if(this.typeBlock.search('image') > 0) {
		
		this.htmlData.find('.tools').append('<div class="edit-image-tool icon-picture tool"></div>');
	}
	
	this.htmlData.find('.content').append(this.contentData);
	
	this.htmlData.data('smobj', this);
};

Block.prototype.createBlock = function() {
	return this.htmlData;
};

Block.prototype.createImage = function() {
	
	media.setBlock(this);
	media.Selected(this.htmlData.find('img').attr('src'));	
};

Block.prototype.assignDisplayer = function(displayer) {
	
	this.displayer = displayer;
};

Block.prototype.setSizeImage = function(height, width) {
	
	this.height = height;
	this.width = width;
};

Block.prototype.changeAttrImgBlock = function(attr, value) {
	
	this.htmlData.find('img').attr(attr, value);
};

Block.prototype.setMediaDisplayer = function() {
	
	if(this.typeBlock.search("image") > 0) {
		
		this.createImage();
	}
	else {
		NoMediaDisplayer();
	}
};