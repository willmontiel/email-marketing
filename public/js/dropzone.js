function DropzoneArea (name, color, parent, width) {
	this.name = name;
	this.color = color;
	this.parent = parent;
	this.width = width;
	this.content = [];
	this.$obj = "";

};

DropzoneArea.prototype.createHtmlZone = function() {
	
	var htmltext = "<div id='content-" + this.name + "' class='sub-mod-cont drop-zone " + this.width +"' style='background-color:" + this.color + ";'>\n\
					<div class='info-guide'>\n\
						<span class='label label-info'>" + this.name + "</span>\n\
					</div>\n\
				</div>";
	
	$(this.parent).append(htmltext);
	
	this.$obj = $("#content-" + this.name);
	
	this.$obj.data('smobj', this);
	
};

DropzoneArea.prototype.deletezone = function() {

	this.$obj.remove();
	
};

DropzoneArea.prototype.setWidth = function(newWidth) {
	
	this.width = newWidth;
};

DropzoneArea.prototype.insertBlocks = function() {
	
	for (var bl = 0; bl < this.content.length; bl++) {
		this.$obj.append(this.content[bl].createBlock());
	}
};

DropzoneArea.prototype.createBlock = function(clase, content, html) {
	return new Block(this, clase, content, html);
};

DropzoneArea.prototype.persist = function() {
	var obj = {
		name: this.name,
		color: this.color,
		width: this.width,
		parent: this.parent,
		content: []
	};
	
	for (var i=0; i< this.content.length; i++) {
		obj.content.push(this.content[i].persist());
	}
	return obj;
};

DropzoneArea.prototype.unpersist = function(obj) {
	
	this.name = obj.name;
	this.color = obj.color;
	this.parent = obj.parent;
	
	if(this.width === undefined) {
		
		this.width = obj.width;
	}
	
	this.$obj = $('<div id="' + this.name + '" class="sub-mod-cont drop-zone ' + this.width + ' ui-sortable"></div>');
	
	for (var i=0; i< obj.content.length; i++) {
		
		var newblk = new Block();
		
		this.$obj.append(newblk.unpersist(obj.content[i], this));

		this.content.push(newblk);
	}
	
	return this.$obj;
};

DropzoneArea.prototype.zoneColor = function() {
	var t = this;
	
	$('#color-' + this.name).colorpicker().on('changeColor', function(ev){
		t.$obj.css('background-color', ev.color.toHex());
		t.color = ev.color.toHex();
	});
};

DropzoneArea.prototype.ondrop = function() {
	var t = this;
	
	this.$obj.sortable({
		
		sort: function() {
			$('#edit-area .drop-zone .info-guide').show();
			$('#edit-area .sub-mod-cont').addClass('show-zones-draggable');			
		},
				
		stop: function(event, object) {

			parent.iframeResize();

			if (object.item.data('smobj') == undefined) {
				
					if(object.item.attr('class').search('text') > 0 && object.item.attr('class').search('image') > 0){
						var content = {image: $(object.item).find('.content-image'), text: $(object.item).find('.content-text')};
					}
					else {
						var content = $(object.item).find('.full-content');
					}

					var newobj = t.createBlock(object.item.attr('class'), content, object.item);
					
					newobj.setMediaDisplayer();
				
				object.item.data('smobj', newobj);
				
			}
			else {
				
				object.item.data('smobj').setMediaDisplayer();
			}
			
			var objblk = object.item.data('smobj');
			
			for(var i = 0; i < t.content.length; i++) {
				
				if(t.content[i] == objblk) {
					
					t.content.splice(i, 1);
				
				}
			}
			
			objblk.parentBlock.content.splice(object.item.index() - 1, 0, objblk);
			
			$('#edit-area .drop-zone .info-guide').css("display", "");
			
			$('#edit-area .sub-mod-cont').removeClass('show-zones-draggable');
		},
				
		receive: function(event, object) {

			if (object.sender != object.item) {
				
				var newobj = new Block();
				
				newobj.unpersist($(object.sender).data('smobj').objSer, t);
				
				newobj.setHtmlData(object.item);

				object.item.data('smobj', newobj);
			}
		},

		remove: function(event, object) {
			
			var blkobj = object.item.data('smobj');
			
			t.objSer = blkobj.persist();
			
			for(var i = 0; i < t.content.length; i++) {
				
				if(t.content[i] == blkobj) {

					t.content[i].deleteBlock();
					
					t.content.splice(i, 1);
				
				}
			}
		}
	});
};