function Block (parentBlock, typeBlock, contentData, htmlData) {
	this.parentBlock = parentBlock;
	this.typeBlock = typeBlock;
	this.contentData = contentData;
	this.htmlData = htmlData;
	
	newRedactor();
}

function newRedactor() {
	$('.content-text').on('click', function() {
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

				plugins: ['fontcolor', 'fontfamily', 'fontsize'],

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
	});
}

Block.prototype.deleteBlock = function() {
	for (var key in this)
      delete this[key];	
};

Block.prototype.setHtmlData = function(htmlData) {
	
	this.htmlData = htmlData;
	
	//newRedactor();
};

Block.prototype.persist = function() {
	
	var obj = {
			type: this.typeBlock,
			//htmlData: this.htmlData.html()
		};
	
	if(this.typeBlock.search('text') > 0 && this.typeBlock.search('image') > 0) {
		obj.contentData = {image: $.trim(this.contentData.image.html()), text: $.trim(this.contentData.text.html())};
	}
	else {
		obj.contentData = $.trim(this.contentData.html());	
	}
	
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
	
	if(this.typeBlock.search('text') > 0 && this.typeBlock.search('image') > 0) {
		
		var contentText = $('<div class="content-text"></div>');
		contentText = contentText.html(obj.contentData.text);
		
		var contentImage = $('<div class="content-image"></div>');
		contentImage = contentImage.html(obj.contentData.image);
		
		var table = $('<table><tr></tr><table/>');
		
		var column1 = $('<td/>');
		var column2 = $('<td/>');
		
		if(this.typeBlock.search('text-image') > 0) {
			column1 = column1.append(contentText);
			column2 = column2.append(contentImage);
		}
		else {
			column1 = column1.append(contentImage);
			column2 = column2.append(contentText);
		}
		
		table.find('tr').append(column1);
		table.find('tr').append(column2);
		
		contentData = $('<div/>');
		contentData = contentData.append(table);
		
		this.contentData = {image: contentImage, text: contentText};
	}
	else if(this.typeBlock.search('text') > 0) {
		var contentData = $('<div/>');
		contentData = contentData.html('<div class="content-text full-content">' + obj.contentData + '</div>');
		
		this.contentData = contentData.children();
	}
	else if(this.typeBlock.search('image') > 0){
		var contentData = $('<div/>');
		contentData = contentData.html('<div class="content-image full-content">' + obj.contentData + '</div>');
		
		this.contentData = contentData.children();
	}
	else {
		var contentData = $('<div/>');
		contentData = contentData.html('<hr>');
		
		this.contentData = contentData.children();
	}
	
	this.htmlData = $('<div/>').html(
						"<div class=\"" + this.typeBlock + "\" style=\"display: block;\">\n\
						<div class=\"tools\" style=\"float: left;\">\
							<div class=\"handle-tool icon-move tool\"></div>\
							<div class=\"remove-tool icon-trash tool\"></div>\
						</div>\
						<div class=\"content clearfix\"></div></div>").children();
	
//	if(this.typeBlock.search('image') > 0) {
//		
//		this.htmlData.find('.tools').append('<div class="edit-image-tool icon-picture tool"></div>');
//	}
	
	this.htmlData.find('.content').append(contentData.children());
	
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

Block.prototype.addClassContentImgBlock = function(value) {
	var content = this.htmlData.find('.content-image');
	
	content.removeClass('pull-center');
	content.removeClass('pull-left');
	content.removeClass('pull-right');
	
	content.addClass(value);
};

Block.prototype.setMediaDisplayer = function() {
	
	if(this.typeBlock.search("image") > 0) {
		
		this.createImage();
	}
	else {
		NoMediaDisplayer();
	}
};