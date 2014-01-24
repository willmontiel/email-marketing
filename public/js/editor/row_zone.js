function rowZone(dz) {
	this.dz = dz;
	this.content = [];
};

rowZone.prototype.drawHtml = function() {
	var row = $('<div class="row-of-blocks">\n\
					<div class="row-icons-options">\n\
						<div class="edit-row tool"><span class="icon-pencil icon-white"></span></div>\n\
						<div class="remove-row tool"><span class="icon-minus icon-white"></span></div>\n\
						<div class="add-column tool"><span class="icon-plus icon-white"></span></div>\n\
						<div class="move-row tool"><span class="icon-move icon-white"></span></div>\n\
					</div>\n\
					<table class="row-options" border="0" cellpadding="0"><tr></tr></table>\n\
				</div>');
	
	this.htmlData = row;
	this.dz.$obj.append(row);
};

rowZone.prototype.addBlock = function(block) {
	this.content.push(block);
	
	block.drawHtml();
};

rowZone.prototype.removeBlock = function(block) {
	for(var i = 0; i < this.content.length; i++) {
		if(this.content[i] == block) {
			this.content.splice(i, 1);
		}
	}
	
	if( this.content.length === 0 ) {
		this.dz.removeRow(this);
		this.htmlData.remove();
	}
};

rowZone.prototype.removeBlock = function(block) {
	
};