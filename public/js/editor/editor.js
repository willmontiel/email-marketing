function Editor() {
	this.layout = {};
	this.dz = {};
}
var editor = new Editor(); 

Editor.prototype.otherLayout = function() {
	var t = this;
	
	$('.layout').on('click', function() {

		layoutChosen();
		
		var oldLayout = t.layout;
		
		var newLayout = $(this).data('layoutObj');

		t.layout = newLayout;
		
		t.colorLayout();
		
		if(jQuery.isEmptyObject(oldLayout)) {
			
			t.newDropZones();
		}
		else {
			
			t.serializeDZ();
			
			t.changeLayout();
			
			NoMediaDisplayer();
		}
		
		parent.iframeResize();
		
		$('.drop-zone').sortable({ 
			handle: '.move-row', 
			placeholder: 'placeholder', 
			items: '> div.row-of-blocks',
			connectWith: '.drop-zone'
		});

	});

};

Editor.prototype.objectExists = function(objMail) {
	
	if(objMail != null) {
		
		this.layout = objMail.layout;
		this.dz = objMail.dz;
		this.editorColor = objMail.editorColor;
		
		this.colorLayout();
		
		this.changeLayout();
		
		NoMediaDisplayer();
		
		$('.drop-zone').sortable({ 
			handle: '.move-row', 
			placeholder: 'placeholder', 
			items: '> div.row-of-blocks',
			connectWith: '.drop-zone'
		});

		layoutChosen();
	}
};

Editor.prototype.changeLayout = function() {
	
	var objdz = {};

	for(var z = 0; z < this.layout.zones.length; z++) {
		
		var dzname = this.layout.zones[z].name;
		
		if(!jQuery.isEmptyObject(this.dz[dzname])) {

			var newdz = new DropzoneArea();

			newdz.setWidth(this.layout.zones[z].width);

			newdz.unpersist(this.dz[dzname]);
			
		}
		else {

			var newdz = new DropzoneArea(dzname, '#ffffff','#edit-area', this.layout.zones[z].width);;
			
		}

		objdz[newdz.name] = newdz;
	}
	
	this.createDZ(objdz);
	
	newRedactor();
};

Editor.prototype.serializeDZ = function() {

	this.deleteZones();
	
	for (var key in this.dz) {
		
		if(this.dz[key] instanceof DropzoneArea) {
			
			this.dz[key] = this.dz[key].persist();
		}
	}
	
};

Editor.prototype.colorLayout = function() {
	
	$('#accordion').empty();
	
	$('#edit-area').css('background-color', this.editorColor);
	
	this.createZoneStyle({name: 'pagina'}, this.zoneHtmlColor('pagina', this.editorColor));
	
	var t = this;
	this.oldColor = this.editorColor;
	
	$('#color-pagina').colorpicker().on('changeColor', function(ev){
		$('#edit-area').css('background-color', ev.color.toHex());
		t.editorColor = ev.color.toHex();
		t.oldColor = ev.color.toHex();
	});
	$('#field-color-pagina').on('change', function(){
		$('#color-pagina').colorpicker('setValue', $(this).val());
		$('#edit-area').css('background-color', $(this).val());
		t.editorColor = $(this).val();
		t.oldColor = $(this).val();
	});
	
	$('input[name=color-trans-pagina]').on('change', function(){
		if($( "input:checked" ).val()) {
			$('#edit-area').css('background-color', 'transparent');
			t.editorColor = 'transparent';
		}
		else {
			$('#edit-area').css('background-color', t.oldColor);
			t.editorColor = t.oldColor;
		}
	});
};

Editor.prototype.zoneHtmlColor = function(name, color) {
	
	color = typeof color !== 'undefined' ? color : '#ffffff';
	
	var text = "<div class='input-append color' data-color='" + color + "' data-color-format='hex' id='color-" + name + "'>\n\
					<input type='text' class='span8' value='' placeholder=" + color + " id='field-color-" + name + "'>\n\
					<span class='add-on'><i style='background-color: rgb(255, 146, 180)'></i></span>\n\
					<div class='color-transparent' style='margin-top: 5px;'><label>Transparente<input name='color-trans-" + name + "'  type='checkbox'style='margin-left: 15px;'></label></div>\n\
				</div>";
	return text;
};

Editor.prototype.createDZ = function(objdz) {
		
	for (var key in objdz) {
		
		if(objdz[key] instanceof DropzoneArea) {
			
			objdz[key].createHtmlZone();
			objdz[key].insertBlocks();
			objdz[key].ondrop();
			this.createZoneStyle(objdz[key], this.zoneHtmlColor(objdz[key].name, objdz[key].color));
			objdz[key].zoneColor();
		}
	}
	
	this.dz = objdz;
};

Editor.prototype.createZoneStyle = function(objdz, bodytext) {
	
	var text = "<div class='accordion-group'>\
					<div class='accordion-heading'>\n\
						<h4 class='panel-title'>\n\
						  <a class='accordion-toggle' data-toggle='collapse' data-parent='#accordion' href='#collapse" + objdz.name + "'>\n\
							" + objdz.name.charAt(0).toUpperCase() + objdz.name.substr(1).toLowerCase() + "\n\
						  </a>\n\
						</h4>\n\
					  </div>\n\
					  <div id='collapse" + objdz.name + "' class='accordion-body collapse'>\n\
						<div class='accordion-inner'>\n\
						" + bodytext + "</div>\n\
					  </div>\n\
					</div>\n\
				</div>";
	
	$('#accordion').append(text);
};

Editor.prototype.deleteZones = function() {
	
	for (var key in this.dz) {
		
		if(this.dz[key] instanceof DropzoneArea) {
			
			this.dz[key].deletezone();
		}
	}
};

Editor.prototype.newDropZones = function() {
	
	for(var z = 0; z < this.layout.zones.length; z++) {
		
		var dz = new DropzoneArea(this.layout.zones[z].name, '#ffffff', '#edit-area', this.layout.zones[z].width);;
		
		dz.createHtmlZone();
		
		dz.ondrop();
		
		this.createZoneStyle(dz, this.zoneHtmlColor(dz.name, dz.color));
		dz.zoneColor();
		
		this.dz[dz.name] = dz;
		
	}
};

Editor.prototype.deleteZoneByTool = function(name, objblk) {
	
	for(var i = 0; i < this.dz[name].content.length; i++) {
			
			if(this.dz[name].content[i] == objblk) {
				
				var oldObj = this.dz[name].content[i];
				
				oldObj.deleteBlock();
				
				parent.iframeResize();
				
				this.dz[name].content.splice(i, 1);
			}
		}	
};

function layoutChosen() {
	
	$('#none-layout').remove();
	$('#tabcomponents').show();
	$('#tabimages').show();
	$('#tabstyles').show();
	parent.$('.btnoptions').show();
	
	$('#layouts').removeClass('active');
	$('#tablayouts').removeClass('active');
	
	$('#styles').removeClass('active');
	$('#tabstyles').removeClass('active');
	
	$('#components').addClass('active');
	$('#tabcomponents').addClass('active');
}

Dropzone.autoDiscover = false;

$(function() {
	
	for(var l = 0; l < layouts.length; l++) {
		layouts[l].createlayout();
	}
	
	for(var l = 0; l < mediaGallery.length; l++) {
		mediaGallery[l].createMedia();
		mediaGallery[l].mediaSelected();
	}
	
	$('html').click(function(ev) {
		if($(ev.target).parents('.component-toolbar')[0] === undefined && $(ev.target).attr('class') !== 'component-toolbar' ){
			$('.component-toolbar').hide();
			$('.element-in-edition').removeClass('element-in-edition');
		}
		
		if($(ev.target).parents('.redactor_box')[0] === undefined && $(ev.target).attr('class') !== 'redactor_box' && $(ev.target).attr('class') !== undefined ){
			$('.redactor_editor').destroyEditor();
		}
	});
	
	editor.objectExists(parent.objMail);
	
	editor.otherLayout();
	
	$('#edit-area, #toolbar').css('height', '600px');
	
	$('#toolbar .module').draggable({
		connectToSortable: ".drop-zone",
		helper: "clone"
	});
	
	$('img').on('dragstart', function(event) { event.preventDefault(); });
	
	$('.gallery-modal').draggable({handle: ".gallery-header"});
	$('.button-modal').draggable({handle: ".button-header"});
	
	$('.module-cont').on('click', '.module > .tools > .remove-tool', function (event) {
		
		var parent = $(this).parents('.module');
		
		var grandparent = $(this).parents('.drop-zone');
		
		var name = grandparent.attr('id').replace("content-","");
		
		editor.deleteZoneByTool(name, parent.data('smobj'));
		
		parent.remove();
		
		NoMediaDisplayer();
		
	});

	$('#components .module').draggable({
		drag: function() {
			$('#edit-area .drop-zone .info-guide').show();

			$('#edit-area .sub-mod-cont').addClass('show-zones-draggable');
		},
		stop: function() {
			$('#edit-area .drop-zone .info-guide').css("display", "");

			$('#edit-area .sub-mod-cont').removeClass('show-zones-draggable');
		}
	});
	
	$('#saveTemplate').on('click', function() {

		editor.serializeDZ();
		var editorToSend = JSON.stringify(editor);

		$.ajax(
			{
			url: config.templateUrl,
			type: "POST",			
			data: { editor: editorToSend, name: $('#templatename').val(), category: $('#templatecategory').val()}
		});
		
		editor.objectExists(editor);
	});
	
	$('.module-cont').on('click', '.content-image > .media-object', function() {
		
		var content = $(this).parents('.module');

		content.data('smobj').createImage();
		
	});
	
	var myDropzone = new Dropzone("#my-dropzone");
	
	myDropzone.on("success", function(file, response) {
		
		var newMedia = new Gallery(response.thumb, response.filelink, response.title, response.id);
		
		newMedia.createMedia();
		newMedia.mediaSelected();

	});	
	
});