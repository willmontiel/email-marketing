function Editor() {
	this.layout = {};
	this.dz = {};
}
var editor = new Editor(); 

Editor.prototype.newlayout = function() {
	
	this.layout.createlayout();
	
};

Editor.prototype.otherLayout = function() {
	var t = this;
	
	$('.layout').on('click', function() {

		var oldLayout = t.layout;
		
		var newLayout = $(this).data('layoutObj');

		t.layout = newLayout;
		
		t.changeLayout(oldLayout);

		$('.drop-zone').sortable({ 
			handle: '.handle-tool', 
			placeholder: 'placeholder', 
			items: '> div.module',
			connectWith: '.drop-zone'
		});

	});

};

Editor.prototype.changeLayout = function(oldLayout) {
	console.log(oldLayout)
	if(jQuery.isEmptyObject(oldLayout)) {
		
		this.newDropZones();
	} 
	else {
		
		for(var z = 0; z < this.layout.zones.length; z++) {
			
			if(!jQuery.isEmptyObject(this.dz[this.layout.zones[z].name])) {
				
				var newdz = this.dz[this.layout.zones[z].name];
				
				this.dz[this.layout.zones[z].name].deletezone();
				
				newdz.createHtmlZone();
				newdz.setWidth(this.layout.zones[z].width);
				newdz.insertBlocks();
				newdz.ondrop();
				
				this.dz[newdz.name] = newdz;

			}
			else {
				var dz = new Dropzone(this.layout.zones[z].name,'#edit-area', this.layout.zones[z].width);;
		
				dz.createHtmlZone();

				dz.ondrop();

				this.dz[dz.name] = dz;
			}
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

		for(var i = 0; i < editor.dropzones[name].content.length; i++) {
			
			if(editor.dropzones[name].content[i].id === parent.data('smobj')) {
				
				var oldObj = editor.dropzones[name].content[i];

				oldObj.deleteBlock();

				editor.dropzones[name].content.splice(i);
			}
		}	
		
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
		
		var parent = $(this).parents('.module');
		
		var grandparent = $(this).parents('.drop-zone');
		
		var name = grandparent.attr('id').replace("content-","");

		for(var i = 0; i < editor.dropzones[name].content.length; i++) {
			
			if(editor.dropzones[name].content[i].id === parent.data('smobj')) {
				
				var obj = editor.dropzones[name].content[i];

				obj.contentData = $(this).siblings('.content');
			}
		}
		
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
});
	

