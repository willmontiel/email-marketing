function rowZone(dz) {
	this.dz = dz;
	this.listofblocks = [];
};

rowZone.prototype.drawHtml = function() {
	var row = $('<div class="row-of-blocks">\n\
					<div class="row-icons-options">\n\
						<div class="edit-row tool"><span class="icon-pencil icon-white"></span></div>\n\
						<div class="remove-row tool"><span class="icon-minus icon-white"></span></div>\n\
						<div class="add-column tool" data-toggle="modal" data-backdrop="static" href="#add-element-block"><span class="icon-plus icon-white"></span></div>\n\
						<div class="move-row tool"><span class="icon-move icon-white"></span></div>\n\
					</div>\n\
					<table class="row-options" border="0" cellpadding="0"><tr class="in-row"></tr></table>\n\
				</div>');
	
	this.content = row;
	this.dz.$obj.append(row);
	this.addColumn();
	this.editRow();
};

rowZone.prototype.addBlock = function(block) {
	this.listofblocks.push(block);
	
	block.drawHtml();
};

rowZone.prototype.removeBlock = function(block) {
	for(var i = 0; i < this.listofblocks.length; i++) {
		if(this.listofblocks[i] == block) {
			this.listofblocks.splice(i, 1);
		}
	}
	
	if( this.listofblocks.length === 0 ) {
		this.dz.removeRow(this);
		this.content.remove();
	}
};

rowZone.prototype.addColumn = function() {
	var t = this;
	this.content.find('.add-column').on('click', function() {
		$('#add-element-block .basic-elements').empty();
		$('#add-element-block .compounds-elements').empty();
		
		t.dz.createHtmlElement('text-only', 'Texto', 'Basic', new TxtBlock(t), t);
		t.dz.createHtmlElement('image-only', 'Imagen', 'Basic', new ImgBlock(t), t);
		t.dz.createHtmlElement('social-share', 'Compartir Redes', 'Basic', new SShareBlock(t), t);
		t.dz.createHtmlElement('social-follow', 'Seguir Redes', 'Basic', new SFollowBlock(t), t);
		t.dz.createHtmlElement('button', 'Botón', 'Basic', new BtnBlock(t), t);
	});
};

rowZone.prototype.editRow = function() {
	var t = this;
	this.content.find('.edit-row').on('click', function(event) {
		var toolbar = new Toolbar(t);
		toolbar.drawHtml();
		toolbar.createBackground();
		toolbar.createBorder();
		toolbar.createCorners();
		toolbar.createMargins();
		
		event.stopPropagation();
	});
};

rowZone.prototype.updateBlockStyle = function(style, value) {
	this.content.css(style, value);
};

rowZone.prototype.updateContentStyle = function(style, value) {
	this.content.css(style, value);
};
