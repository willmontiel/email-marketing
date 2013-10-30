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
			handle: '.handle-tool', 
			placeholder: 'placeholder', 
			items: '> div.module',
			connectWith: '.drop-zone'
		});

	});

};

Editor.prototype.objectExists = function(objMail) {
	
	if(objMail != null) {
		
		editor.layout = objMail.layout;
		editor.dz = objMail.dz;

		editor.changeLayout();

		NoMediaDisplayer();
		
		$('.drop-zone').sortable({ 
			handle: '.handle-tool', 
			placeholder: 'placeholder', 
			items: '> div.module',
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

			var newdz = new DropzoneArea(dzname,'#edit-area', this.layout.zones[z].width);;
			
		}

		objdz[newdz.name] = newdz;
	}
	
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
			objdz[key].insertBlocks();
			objdz[key].ondrop();
		}
	}
	
	this.dz = objdz;
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
		
		var dz = new DropzoneArea(this.layout.zones[z].name,'#edit-area', this.layout.zones[z].width);;
		
		dz.createHtmlZone();
		
		dz.ondrop();
		
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
	$('#tabcomponents').show();
	$('#tabimages').show();

	$('#layouts').removeClass('active');
	$('#tablayouts').removeClass('active');
	
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
	
	editor.objectExists(parent.objMail)
	
	editor.otherLayout();
	
	$('#toolbar .module').draggable({
		connectToSortable: ".drop-zone",
		helper: "clone"
	});
	
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
			$('#edit-area .drop-zone .info-guide').hide();

			$('#edit-area .sub-mod-cont').removeClass('show-zones-draggable');
		}
	});
	
	$('#guardar').on('click', function() {
		
		editor.serializeDZ();
		var editorToSend = JSON.stringify(editor);
		
		$.ajax(
			{
			url: config.sendUrl,
			type: "POST",			
			data: { editor: editorToSend}
		});
	});
	
	$('.module-cont').on('click', '.module > .tools > .edit-image-tool', function() {
		
		$('#components').removeClass('active');
		$('#tabcomponents').removeClass('active');
		
		$('#layouts').removeClass('active');
		$('#tablayouts').removeClass('active');
		
		$('#images').addClass('active');
		$('#tabimages').addClass('active');
		
		var content = $(this).parents('.module');
		
		content.data('smobj').createImage();
		
	});
	
	$('.module-cont').on('click', '.module  .content-image > .edit-image-tool', function() {
		
		$('#components').removeClass('active');
		$('#tabcomponents').removeClass('active');
		
		$('#layouts').removeClass('active');
		$('#tablayouts').removeClass('active');
		
		$('#images').addClass('active');
		$('#tabimages').addClass('active');
		
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