function Dropzone (name, parent, width) {
	this.name = name;
	this.parent = parent;
	this.width = width;
	this.content = [];

	this.text = "<div id='content-" + this.name + "' class='sub-mod-cont drop-zone " + this.width +"'>\n\
					<div class='info-guide'>\n\
						<span class='label label-info'>" + this.name + "</span>\n\
					</div>\n\
				</div>";
};

Dropzone.prototype.createHtmlZone = function() {
	
	$(this.parent).append(this.text);
	
};

Dropzone.prototype.deletezone = function() {
	
	$("#content-" + this.name).remove();
	
};

Dropzone.prototype.setWidth = function(newWidth) {
	
	$("#content-" + this.name).removeClass(this.width).addClass(newWidth);
	
	this.width = newWidth;
};

Dropzone.prototype.insertBlocks = function() {
	
	for (var bl = 0; bl < this.content.length; bl++) {
		//console.log(this.content[bl].contentData.html());
		$("#content-" + this.name).append(this.content[bl].htmlData);
	}
};

Dropzone.prototype.createBlock = function(clase, content) {
	return new Block(this, clase, content, null);
}

Dropzone.prototype.ondrop = function() {
	var parentBlock;
	var newobj;
	var t = this;
	
	$("#content-" + this.name).sortable({
		
		sort: function() {
			$('#edit-area .drop-zone .info-guide').show();
			$('#edit-area .sub-mod-cont').addClass('show-zones-draggable');			
		},
		
		update: function(event, object) {
			var blkobj = object.item.data('smobj');
//			console.log(object.item.index());
//			console.log(blkobj);
//			console.log(t.content);

			for(var i = 0; i < t.content.length; i++) {
				
				if(t.content[i] == blkobj) {
					
					t.content.splice(i);
					
				}
			}
			
			var pos = object.item.index() - 1;
			var newobj = t.createBlock(object.item.attr('class'), $(object.item).children('.content'));

			object.item.data('smobj', newobj);
			t.content.splice(pos, 0, newobj);
		},

		stop: function(event, object) {
			
			if (object.item.data('smobj') == undefined) {
				var newobj = t.createBlock(object.item.attr('class'), $(object.item).children('.content'));
				object.item.data('smobj', newobj);
			}

			$('#edit-area .drop-zone .info-guide').hide();
			
			$('#edit-area .sub-mod-cont').removeClass('show-zones-draggable');
		},
				
		receive: function(event, object) {

			//t.content.push(newobj);

			if (object.sender == object.item) {
				console.log('Son el mismo!!!');
			}
			else {
				console.log('NO son el mismo!!!');
				var newobj = t.createBlock(object.item.attr('class'), $(object.item).children('.content'));
				object.item.data('smobj', newobj);
			}
		},

		remove: function(event, object) {
			
			var blkobj = object.item.data('smobj');
			
			for(var i = 0; i < t.content.length; i++) {
				
				if(t.content[i] == blkobj) {
					
					t.content[i].deleteBlock();
					
					t.content.splice(i);
				}
			}			
		}
	});
};