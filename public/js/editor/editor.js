function Editor() {
	this.layout = {};
	this.dz = {};
	this.content = {};
}
var editor = new Editor(); 

Editor.prototype.otherLayout = function() {
	var t = this;
	
	$('.layout').on('click', function() {
		parent.$('.btnoptions').show();
		var oldLayout = t.layout;
		var newLayout = $(this).data('layoutObj');
		t.layout = newLayout;
		t.createEditStyle();
		
		if(jQuery.isEmptyObject(oldLayout)) {
			t.newDropZones();
		}
		else {
			t.serializeDZ();
			t.changeLayout();
			NoMediaDisplayer();
		}
		parent.iframeResize();
		
		$('.dropzone-container').sortable({ 
			handle: '.move-row', 
			placeholder: 'placeholder', 
			items: '> div.row-of-blocks',
			connectWith: '.dropzone-container'
		});
	});

};

Editor.prototype.objectExists = function(objMail) {
	if(objMail != null) {
		this.layout = objMail.layout;
		this.dz = objMail.dz;
		this.editorColor = objMail.editorColor;
		this.changeLayout();
		NoMediaDisplayer();
		
		$('.dropzone-container').sortable({ 
			handle: '.move-row', 
			placeholder: 'placeholder', 
			items: '> div.row-of-blocks',
			connectWith: '.dropzone-container'
		});
		parent.$('.btnoptions').show();
	}
	else {
		this.layout = layouts[0];
		this.createEditStyle();
		this.newDropZones();
		parent.$('.btnoptions').show();
	}
};

Editor.prototype.changeLayout = function() {
	var objdz = {};
	for(var z = 0; z < this.layout.zones.length; z++) {
		var dzname = this.layout.zones[z].name;
		
		if(!jQuery.isEmptyObject(this.dz[dzname])) {
			var newdz = new DropzoneArea();
			newdz.setWidth(this.layout.zones[z].width, this.layout.zones[z].widthval);
			newdz.unpersist(this.dz[dzname]);
		}
		else {
			var newdz = new DropzoneArea(dzname, '#ffffff','#edit-area', this.layout.zones[z].width, this.layout.zones[z].widthval);
		}
		objdz[newdz.name] = newdz;
	}
	this.createEditStyle();
	this.createDZ(objdz);
};

Editor.prototype.serializeDZ = function() {
	this.deleteZones();
	
	for (var key in this.dz) {
		if(this.dz[key] instanceof DropzoneArea) {
			this.dz[key] = this.dz[key].persist();
		}
	}
};

Editor.prototype.createDZ = function(objdz) {
	for (var key in objdz) {
		if(objdz[key] instanceof DropzoneArea) {
			objdz[key].createHtmlZone();
			objdz[key].insertRows();
			objdz[key].ondrop();
			objdz[key].zoneColor();
		}
	}
	this.dz = objdz;
};

Editor.prototype.createEditStyle = function() {
	var t = this;
	$('.dropzone-container-border').remove();
	$('.layout-icons-options').remove();
	$('.toolbar-to-edit').remove();
	var edit = '<div class="layout-icons-options">\n\
					<div class="edit-layout tool"><span class="icon-pencil icon-white"></span></div>\n\
				</div>\n\
				<div class="toolbar-to-edit"></div>';
	$('#edit-area').append(edit);
	var edition = new EditionArea($('#edit-area'), this);
	$('.edit-layout').on('click', function(event) {
		var toolbar = new Toolbar(edition);
		toolbar.drawHtml(false);
		toolbar.createBackground();
		toolbar.createLayout();
		toolbar.setWidthSize('160');
		toolbar.setHeightSize('85');
		event.stopPropagation();
	});
};

function EditionArea(content, parent) {
	this.content = content;
	this.parent = parent;
}

EditionArea.prototype.updateBlockStyle = function(style, value) {
	this.content.css(style, value);
	this.parent.editorColor = value;
};

EditionArea.prototype.updateContentStyle = function(style, value) {
	this.content.css(style, value);
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
		var dz = new DropzoneArea(this.layout.zones[z].name, '#ffffff', '#edit-area', this.layout.zones[z].width, this.layout.zones[z].widthval);
		dz.createHtmlZone();
		dz.ondrop();
		dz.zoneColor();
		this.dz[dz.name] = dz;
	}
};

Dropzone.autoDiscover = false;

$(function() {
	$('#select-layout .layout-list').empty();
	for(var l = 0; l < layouts.length; l++) {
		layouts[l].createlayout();
	}
	
	for(var l = 0; l < mediaGallery.length; l++) {
		mediaGallery[l].createMedia();
		mediaGallery[l].mediaSelected();
	}
	
	initEvents();
	editor.objectExists(parent.objMail);
	editor.otherLayout();
	
	var myDropzone = new Dropzone("#my-dropzone");
	myDropzone.on("success", function(file, response) {
		var newMedia = new Gallery(response.thumb, response.filelink, response.title, response.id);
		newMedia.createMedia();
		newMedia.mediaSelected();
	});	
});

function initEvents() {
	$('html').click(function(ev) {
		if($(ev.target).parents('#my-component-toolbar')[0] === undefined && $(ev.target).attr('class') !== 'my-component-toolbar' ){
			$('#my-component-toolbar').remove();
			$('.element-in-edition').removeClass('element-in-edition');
		}
		if($(ev.target).parents('.one-element').find('#my-btn-component-toolbar')[0] === undefined && $(ev.target).parents('#my-btn-component-toolbar')[0] === undefined && $(ev.target).attr('class') !== 'my-btn-component-toolbar' ){
			$('#my-btn-component-toolbar').remove();
		}
		if($(ev.target).parents('.one-element').find('#my-social-share-component-toolbar')[0] === undefined && $(ev.target).parents('#my-social-share-component-toolbar')[0] === undefined && $(ev.target).attr('class') !== 'my-social-share-component-toolbar' ){
			$('#my-social-share-component-toolbar').remove();
		}
		if($(ev.target).parents('.one-element').find('#my-social-follow-component-toolbar')[0] === undefined && $(ev.target).parents('#my-social-follow-component-toolbar')[0] === undefined && $(ev.target).attr('class') !== 'my-social-follow-component-toolbar' ){
			$('#my-social-follow-component-toolbar').remove();
		}
		if($(ev.target).parents('.redactor_box')[0] === undefined && $(ev.target).attr('class') !== 'redactor_box' && $(ev.target).attr('class') !== undefined ){
			$('.redactor_editor').destroyEditor();
		}
	});
	$('#saveTemplate').on('click', function() {
		editor.serializeDZ();
		var editorToSend = JSON.stringify(editor);
		$.ajax({
			url: config.templateUrl,
			type: "POST",			
			data: { editor: editorToSend, name: $('#templatename').val(), category: $('#templatecategory').val()}
		});
		editor.objectExists(editor);
	});
	$('#toolbar .module').draggable({
		connectToSortable: ".drop-zone",
		helper: "clone"
	});
	$('#edit-area, #toolbar').css('height', '600px');
	$('img').on('dragstart', function(event) { event.preventDefault(); });
	$('.gallery-modal').draggable({handle: ".gallery-header"});
	$('.button-modal').draggable({handle: ".button-header"});
}