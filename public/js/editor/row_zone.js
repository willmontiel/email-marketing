function rowZone(dz) {
	this.dz = dz;
	this.listofblocks = [];
	this.background_color = "000000";
	this.border_width = 0;
	this.border_color = "000000";
	this.corner_top_left = 0;
	this.corner_top_right = 0;
	this.corner_bottom_left = 0;
	this.corner_bottom_right = 0;
	this.margin_top = 0;
	this.margin_bottom = 0;
	this.margin_left = 0;
	this.margin_right = 0;
};

rowZone.prototype.createRow = function() {
	var row = this.drawHtml();
	
	this.content = row;
	this.dz.$obj.append(row);
	this.content.data('smobj', this);
	
	this.addColumn();
	this.editRow();
	this.removeRow();

	for(var blk = 0; blk < this.listofblocks.length; blk++) {
		this.listofblocks[blk].createBlock();
	}
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
	
	return row;
};

rowZone.prototype.addBlock = function(block) {
	this.listofblocks.push(block);
	
	block.createBlock();
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

rowZone.prototype.removeRow = function() {
	var t = this;
	this.content.find('.remove-row').on('click', function(event) {
		t.dz.removeRow(this);
		t.content.remove();
	});
};

rowZone.prototype.updateBlockStyle = function(style, value) {
	this.content.css(style, value);
};

rowZone.prototype.updateContentStyle = function(style, value) {
	this.content.css(style, value);
};

rowZone.prototype.persist = function() {
	var obj = {
		background_color : this.background_color,
		border_width : this.border_width,
		border_color : this.border_color,
		corner_top_left : this.corner_top_left,
		corner_top_right : this.corner_top_right,
		corner_bottom_left : this.corner_bottom_left,
		corner_bottom_right : this.corner_bottom_right,
		margin_top : this.margin_top,
		margin_bottom : this.margin_bottom,
		margin_left : this.margin_left,
		margin_right : this.margin_right,
		content : []
	};
	
	for(var i = 0; i < this.listofblocks.length; i++) {
		obj.content.push(this.listofblocks[i].persist());
	}
	
	return obj;
};

rowZone.prototype.unpersist = function(obj) {
	this.background_color = obj.background_color;
	this.border_width = obj.border_width;
	this.border_color = obj.border_color;
	this.corner_top_left = obj.corner_top_left;
	this.corner_top_right = obj.corner_top_right;
	this.corner_bottom_left = obj.corner_bottom_left;
	this.corner_bottom_right = obj.corner_bottom_right;
	this.margin_top = obj.margin_top;
	this.margin_bottom = obj.margin_bottom;
	this.margin_left = obj.margin_left;
	this.margin_right = obj.margin_right;
	
	for(var i = 0; i < obj.content.length; i++) {
		switch (obj.content[i].type) {
			case 'text':
				var block = new TxtBlock(this);
				break;
			case 'image':
				var block = new ImgBlock(this);
				break;
		}
		this.listofblocks.push(block.unpersist(obj.content[i]));
	}
};