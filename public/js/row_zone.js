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
	
	var column = $('<td>\n\
						<div class="one-element">\n\
							<div class="elements-options">\n\
								<div class="edit-block tool"><span class="icon-pencil icon-white"></span></div>\n\
								<div class="remove-block tool"><span class="icon-minus icon-white"></span></div>\n\
							</div>\n\
						</div>\n\
					</td>');
	
	this.htmlData.find('tr').append(column);
	
	block.drawHtml();
};