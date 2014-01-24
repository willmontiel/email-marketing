function BtnBlock(row) {
	this.row = row;
}

BtnBlock.prototype.drawHtml = function() {
	this.content = $('<td>\n\
						<div class="one-element">\n\
							<div class="elements-options">\n\
								<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
								<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
							</div>\n\
							<span data-toggle="modal" data-backdrop="static" href="#buttonaction" class="content-button pull-center" style="background-image:url(\'' + config.imagesUrl + '/btn-blue.png\');border:1px solid #1e3650;border-radius:4px;">Clic Aqui!</span>\n\
						</div>\n\
					</td>');
	this.row.content.find('.in-row').append(this.content);
	
	this.editBlock();
	this.removeBlock();
};

BtnBlock.prototype.editBlock = function() {
	var t = this;
	this.row.content.find('td:last .edit-block').on('click', function() {
		
	});
};

BtnBlock.prototype.removeBlock = function() {
	var t = this;
	this.row.content.find('td:last .remove-block').on('click', function() {
		t.row.removeBlock(t);
		t.content.remove();
	});
};