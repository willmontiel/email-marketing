function ImgBlock(row) {
	this.row = row;
}

ImgBlock.prototype.drawHtml = function() {
	this.content = $('<td>\n\
						<div class="one-element">\n\
							<div class="elements-options">\n\
								<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
								<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
							</div>\n\
							<img data-toggle="modal" data-backdrop="static" href="#images" class="media-object image-placeholder" />\n\
						</div>\n\
					</td>');
	this.row.htmlData.find('tr').append(this.content);
	
	this.editBlock();
	this.removeBlock();
	this.createImage();
};

ImgBlock.prototype.editBlock = function() {
	var t = this;
	this.row.htmlData.find('td:last .edit-block').on('click', function() {
		var toolbar = new Toolbar(t);
		toolbar.drawHtml();
	});
};

ImgBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.htmlData.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};

ImgBlock.prototype.createImage = function() {
//	switch (this.parentBlock.width) {
//		case 'full-width':
//			this.widthZone =  550;
//			break;
//		case 'half-width':
//			this.widthZone = 550/2;
//			break;
//		case 'third-width':
//			this.widthZone = Math.floor(550/3);
//			break;
//		case 'twothird-width':
//			this.widthZone = Math.floor(550*2/3);
//			break;
//		default:
//			console.log('3')
			this.widthZone =  550;
//			break;
//	}
	
	media.setBlock(this);
	media.Selected(this.content.find('img').attr('src'));
};