function Editor() {
	this.layout = {};
	this.dz = {};
}
var editor = new Editor(); 

Editor.prototype.otherLayout = function() {
	var t = this;
	
	$('.layout').on('click', function() {

		var oldLayout = t.layout;
		
		var newLayout = $(this).data('layoutObj');

		t.layout = newLayout;
		
		if(jQuery.isEmptyObject(oldLayout)) {
			
			t.newDropZones();
		}
		else {
			
			t.serializeDZ();
			
			t.changeLayout();
		}

		$('.drop-zone').sortable({ 
			handle: '.handle-tool', 
			placeholder: 'placeholder', 
			items: '> div.module',
			connectWith: '.drop-zone'
		});

	});

};

Editor.prototype.changeLayout = function() {
	
	var objdz = {};

	for(var z = 0; z < this.layout.zones.length; z++) {
		
		var dzname = this.layout.zones[z].name;
		
		if(!jQuery.isEmptyObject(this.dz[dzname])) {

			var newdz = new Dropzone();

			newdz.setWidth(this.layout.zones[z].width);

			newdz.unpersist(this.dz[dzname]);
			
		}
		else {

			var newdz = new Dropzone(dzname,'#edit-area', this.layout.zones[z].width);;
			
		}

		objdz[newdz.name] = newdz;
	}
	console.log(objdz);
	this.createDZ(objdz);		
	
};

Editor.prototype.serializeDZ = function() {

	this.deleteZones();
	
	for (var key in this.dz) {
		
		if(this.dz[key] instanceof Dropzone) {
			
			this.dz[key] = this.dz[key].persist();
		}
	}
	
	
	
};

Editor.prototype.createDZ = function(objdz) {
	
	for (var key in objdz) {
		
		if(objdz[key] instanceof Dropzone) {
			
			objdz[key].createHtmlZone();
			objdz[key].insertBlocks();
			objdz[key].ondrop();
		}
	}
	
	this.dz = objdz;
};

Editor.prototype.deleteZones = function() {
	
	for (var key in this.dz) {
		
		if(this.dz[key] instanceof Dropzone) {
			
			this.dz[key].deletezone();
		}
	}
};

Editor.prototype.newDropZones = function() {
	
	for(var z = 0; z < this.layout.zones.length; z++) {
		
		var dz = new Dropzone(this.layout.zones[z].name,'#edit-area', this.layout.zones[z].width);;
		
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

				this.dz[name].content.splice(i, 1);
			}
		}	
};

$(function() {
	
	for(var l = 0; l < layouts.length; l++) {
		layouts[l].createlayout();
	}
	
	editor.otherLayout();
	
	$('#toolbar .module').draggable({
		connectToSortable: ".drop-zone",
		helper: "clone"
	});

	$('.module-cont').on('click', '.module > .remove-tool', function (event) {
		
		var parent = $(this).parents('.module');
		
		var grandparent = $(this).parents('.drop-zone');
		
		var name = grandparent.attr('id').replace("content-","");
		
		editor.deleteZoneByTool(name, parent.data('smobj'));
		
		parent.remove();
		
	});


	$('.module-cont').on('click', '.module > .edit-tool', function (event) 
	{
		var textcontent = $(this).parents('.module').find('.content-text');

		$(this).parents('.module').find('.save-tool').show();

		textcontent.redactor({ 
			focus: true,
			buttons: [
				'html', '|', 
				'formatting', '|', 
				'bold', 'italic', 'deleted', '|', 
				'unorderedlist', 'orderedlist', 'outdent', 'indent', '|', 
				'link', '|', 
				'fontcolor', 'backcolor', '|', 
				'alignment'
			]
		});
	});


	$('.module-cont').on('click', '.module > .save-tool', function (event) 
	{		
		var textcontent = $(this).parents('.module').find('.content-text');

		textcontent.redactor('destroy');
		
		$(this).hide();
		
	});
	
	$('#components .module').draggable({
		drag: function() {
			$('#edit-area .drop-zone .info-guide').css("display", "block");
			
			$('#edit-area .sub-mod-cont').addClass('show-zones-draggable');
		},
		stop: function() {
			$('#edit-area .drop-zone .info-guide').css("display", '');

			$('#edit-area .sub-mod-cont').removeClass('show-zones-draggable');
		}
	});
	
	$('#guardar').on('click', function() {
		console.log(editor);
	});
	
//	$('.module-cont').on('click', '.module > .edit-image-tool', function() {
//		$('#images').addClass('active');
//		$('#tab-images').addClass('active');
//	})
});
	

