function DropzoneArea (name, color, parent, width, widthval) {
	this.name = name;
	this.color = color;
	this.parent = parent;
	this.width = width;
	this.widthval = widthval;
	this.listofrows = [];
	this.content = "";
	
	this.background_color = "FFFFFF";
	this.border_width = 0;
	this.border_color = "FFFFFF";
	this.border_style = "none";
	this.corner_top_left = 0;
	this.corner_top_right = 0;
	this.corner_bottom_left = 0;
	this.corner_bottom_right = 0;
};

DropzoneArea.prototype.createHtmlZone = function() {
	
	var htmltext = "<div id='content-" + this.name + "' class='sub-mod-cont drop-zone " + this.width +"' style='background-color:" + this.background_color + ";'>\n\
						<div class='dropzone-container dropzone-container-border'>\n\
							<div class='info-guide'>\n\
								<span>" + this.name + "</span>\n\
							</div>\n\
							<div class='dz-icons-options'>\n\
								<div class='edit-zone tool'><span class='icon-pencil icon-white'></span></div>\n\
							</div>\n\
							<div class='add-row-block' data-toggle='modal' data-backdrop='static' href='#add-element-block'>\n\
								<div class='image-add icon-plus icon-white icon-2x'></div>\n\
								<div class='add-element'>Adicionar Elemento</div>\n\
							</div>\n\
						</div>\n\
					</div>";
	
	$(this.parent).append(htmltext);
	
	this.content = $("#content-" + this.name);
	
	this.content.data('smobj', this);
	
	this.addElementToZone();
	this.updateChanges();
	this.editZone();
	
};

DropzoneArea.prototype.addElementToZone = function() {
	var t = this;
	this.content.find('.add-row-block').on('click', function() {
		$('#add-element-block .basic-elements').empty();
		$('#add-element-block .compounds-elements').empty();
		
		var row = new rowZone(t);
		
		t.createHtmlElement('text-only', 'Texto', 'Basic', new TxtBlock(row), row);
		t.createHtmlElement('image-only', 'Imagen', 'Basic', new ImgBlock(row), row);
		t.createHtmlElement('separator', 'Separador', 'Basic', new HrBlock(row), row);
		t.createHtmlElement('social-share', 'Compartir Redes', 'Basic', new SShareBlock(row), row);
		t.createHtmlElement('social-follow', 'Seguir Redes', 'Basic', new SFollowBlock(row), row);
		t.createHtmlElement('button', 'Botón', 'Basic', new BtnBlock(row), row);
		
		t.createHtmlElement('text-image', 'Texto - Imagen', 'Compound', [new TxtBlock(row), new ImgBlock(row)], row);
		t.createHtmlElement('image-text', 'Imagen - Texto', 'Compound', [new ImgBlock(row), new TxtBlock(row)], row);
		
		parent.iframeResize();
	});
};

DropzoneArea.prototype.editZone = function() {
	var t = this;
	this.content.find('.dz-icons-options .edit-zone').on('click', function(event) {
		var toolbar = new Toolbar(t);
		toolbar.drawHtml('dropzone-container-border');
		toolbar.createBackground();
		toolbar.createBorder();
		toolbar.createCorners();
		toolbar.setWidthSize('510');
		
		event.stopPropagation();
	});
};

DropzoneArea.prototype.createHtmlElement = function(module, description, category, block, row) {
	var element = $('<div class="element-block" data-dismiss="modal">\n\
						<div class="module module-' + module + '"></div>\n\
						<div class="module-information">\n\
							<p>' + description + '</p>\n\
						</div>\n\
					</div>');
	
	if(category === 'Basic') {
		$('#add-element-block .basic-elements').append(element);
	}
	else if(category === 'Compound') {
		$('#add-element-block .compounds-elements').append(element);
	}
	
	var t = this;
	
	element.on('click', function() {
		if(row.listofblocks.length === 0) {
			t.listofrows.push(row);
			row.createRow();
		}
		if(category === 'Compound') {
			for(var i = 0; i < block.length; i++) {
				row.addBlock(block[i]);
			}
		}
		else {
			row.addBlock(block);
		}
		row.updateImagesSize();
	});
	
};

DropzoneArea.prototype.removeRow = function(row) {
	for(var i = 0; i < this.listofrows.length; i++) {
		if(this.listofrows[i] == row) {
			this.listofrows.splice(i, 1);
		}
	}
};

DropzoneArea.prototype.updateBlockStyle = function(style, value) {
	this.content.css(style, value);
};

DropzoneArea.prototype.updateContentStyle = function(style, value) {
	this.content.css(style, value);
};

DropzoneArea.prototype.deletezone = function() {

	this.content.remove();
	
};

DropzoneArea.prototype.setWidth = function(newWidth, newWidthval) {
	
	this.width = newWidth;
	this.widthval = newWidthval;
};

DropzoneArea.prototype.insertRows = function() {
	
	for (var row = 0; row < this.listofrows.length; row++) {
		this.content.append(this.listofrows[row].createRow());
		this.listofrows[row].updateChanges();
	}
};

DropzoneArea.prototype.createBlock = function(clase, content, html) {
	return new Block(this, clase, content, html);
};

DropzoneArea.prototype.persist = function() {
	var obj = {
		name: this.name,
		width: this.width,
		widthval: this.widthval,
		parent: this.parent,
		background_color : this.background_color,
		border_width : this.border_width,
		border_color : this.border_color,
		border_style : this.border_style ,
		corner_top_left : this.corner_top_left,
		corner_top_right : this.corner_top_right,
		corner_bottom_left : this.corner_bottom_left,
		corner_bottom_right : this.corner_bottom_right,
		content: []
	};
	
	for (var i=0; i< this.listofrows.length; i++) {
		obj.content.push(this.listofrows[i].persist());
	}
	return obj;
};

DropzoneArea.prototype.unpersist = function(obj) {
	
	this.name = obj.name;
	this.color = obj.color;
	this.parent = obj.parent;
	
	this.background_color = (obj.background_color === undefined) ? "FFFFFF" : obj.background_color;
	this.border_width = (obj.border_width === undefined) ? 0 : obj.border_width;
	this.border_color = (obj.border_color === undefined) ? 0 : obj.border_color;
	this.border_style = (obj.border_style === undefined) ? 'none' : obj.border_style;
	this.corner_top_left = (obj.corner_top_left === undefined) ? 0 : obj.corner_top_left;
	this.corner_top_right = (obj.corner_top_right === undefined) ? 0 : obj.corner_top_right;
	this.corner_bottom_left = (obj.corner_bottom_left === undefined) ? 0 : obj.corner_bottom_left;
	this.corner_bottom_right = (obj.corner_bottom_right === undefined) ? 0 : obj.corner_bottom_right;
	
	if(this.width === undefined) {
		
		this.width = obj.width;
		this.widthval = obj.widthval;
	}
	
	this.content = $('<div id="' + this.name + '" class="sub-mod-cont drop-zone ' + this.width + ' ui-sortable"></div>');
	
	for (var i=0; i< obj.content.length; i++) {
		
		var newrow = new rowZone(this);
		
		this.content.append(newrow.unpersist(obj.content[i]));
		
		this.listofrows.push(newrow);
	}
	
	return this.content;
};

DropzoneArea.prototype.updateChanges = function() {
	this.updateBlockStyle('background-color', this.background_color);
	
	this.updateBlockStyle('border-color', this.border_color);
	this.updateBlockStyle('border-width', this.border_width);
	this.updateBlockStyle('border-style', this.border_style);
	
	this.updateBlockStyle('border-top-left-radius', this.corner_top_left);
	this.updateBlockStyle('border-top-right-radius', this.corner_top_right);
	this.updateBlockStyle('border-bottom-left-radius', this.corner_bottom_left);
	this.updateBlockStyle('border-bottom-right-radius', this.corner_bottom_right);
	
	this.updateContentStyle('margin-top', this.margin_top);
	this.updateContentStyle('margin-bottom', this.margin_bottom);
	this.updateBlockStyle('padding-left', this.margin_left);
	this.updateBlockStyle('padding-right', this.margin_right);
};

DropzoneArea.prototype.zoneColor = function() {
	var t = this;
	this.oldColor = this.color;
	
	$('#color-' + this.name).colorpicker().on('changeColor', function(ev){
		t.content.css('background-color', ev.color.toHex());
		t.color = ev.color.toHex();
		t.oldColor = ev.color.toHex();
	});
	
	$('#field-color-' + this.name).on('change', function(){
		$('#color-' + t.name).colorpicker('setValue', $(this).val());
		t.content.css('background-color', $(this).val());
		t.color = $(this).val();
		t.oldColor = $(this).val();
	});
	
	$('input[name=color-trans-' + this.name + ']').on('change', function(){
		if($( "input:checked" ).val()) {
			t.content.css('background-color', 'transparent');
			t.color = 'transparent';
		}
		else {
			t.content.css('background-color', t.oldColor);
			t.color = t.oldColor;
		}
	});
};

DropzoneArea.prototype.ondrop = function() {
	var t = this;
	
	this.content.find('.dropzone-container').sortable({
		sort: function() {
			$('#edit-area .drop-zone .info-guide').show();
			$('#edit-area .sub-mod-cont').addClass('show-zones-draggable');	
		},
		stop: function(event, object) {
			parent.iframeResize();
			var objrow = object.item.data('smobj');
			
			for(var i = 0; i < t.listofrows.length; i++) {
				if(t.listofrows[i] === objrow) {
					t.removeRow(t.listofrows[i]);
				}
			}
			objrow.dz.listofrows.splice(object.item.index() - 3, 0, objrow);
			$('#edit-area .drop-zone .info-guide').css("display", "");
			$('#edit-area .sub-mod-cont').removeClass('show-zones-draggable');
		},
				
		receive: function(event, object) {
			if (object.sender !== object.item) {
				$(object.item).data('smobj').dz = t;
			}
		},

		remove: function(event, object) {
			var rowobj = object.item.data('smobj');
			t.objSer = rowobj;
			for(var i = 0; i < t.listofrows.length; i++) {
				if(t.listofrows[i] === rowobj) {
					t.removeRow(t.listofrows[i]);
				}
			}
		}
	});
};