//------------------------//------------------------//------------------------//
//------------------------//--------Gallery---------//------------------------//
//------------------------//------------------------//------------------------//

function Gallery(srcThumb, srcImage, title, id) {
	this.srcThumb = srcThumb;
	this.srcImage = srcImage;
	this.title = title;
	this.id = id;
}

Gallery.prototype.createMedia = function() {
	var obj = $("<a id = \"media" + this.id + "\" class=\"medias\" href=\"#\" data-toggle=\"tab\"><img src=\"" + this.srcThumb + "\" alt=\"" + this.title + "\"> </a>");
	
	obj.data('mediaObj', this);

	$('#gallery').append(obj);
};

Gallery.prototype.mediaSelected = function() {	
	var t = this;
	
	$('#gallery a#media' + this.id).on('click', function() {
		
		media.setGallery(t);
		media.Selected(t.srcImage, t.title);
	});
};


//------------------------//------------------------//------------------------//
//------------------------//-----Media-Displayer----//------------------------//
//------------------------//------------------------//------------------------//


function MediaDisplayer(block, gallery, image) {
	this.gallery = gallery;
	this.block = block;
	this.image = image;
}

var media = new MediaDisplayer();

MediaDisplayer.prototype.setBlock = function(block) {
	
	this.block = block;	
};

MediaDisplayer.prototype.setGallery = function(gallery) {
	
	this.gallery = gallery;	
};

MediaDisplayer.prototype.Selected = function(newsrc, title) {
	
	$('#imagedisplayer').empty();

	if (this.block != null && this.block.hasOwnProperty('htmlData')) {
		
		if(newsrc == '/emarketing/images/image') {
			
			this.cleanMediaDisplayer();
			
			var msg = $('<p>Seleccione una imagen de la galeria</p>');
			
			$('#imagedisplayer').append(msg);
		}
		else {
			
			this.block.changeAttrImgBlock('src', newsrc);
			
			this.block.changeAttrImgBlock('alt', title);

			var img = new Image();

			img.src = newsrc;

			var t = this;

			$(img).on('load', function() {

				if(t.block.hasOwnProperty('displayer') && t.block.displayer.imagesrc === newsrc) {
				
					var realHeight = t.block.height;
					
					var realWidth = t.block.width;

					var widthDisplayer = t.block.displayer.width;
					
					var heightDisplayer = t.block.displayer.height;
				}
				else {

					var realHeight = img.naturalHeight;
					
					var realWidth = img.naturalWidth;
					
					var widthDisplayer = 130;
					
					var heightDisplayer = 130*img.naturalHeight/img.naturalWidth;
					
					t.block.assignDisplayer({imagesrc: newsrc, percent: 100, width: widthDisplayer, height: heightDisplayer});
					
					t.block.setAlignImgBlock("pull-left");
				}
				
				t.block.setSizeImage(realHeight, realWidth);
					
				t.block.changeAttrImgBlock('height', realHeight);
					
				t.block.changeAttrImgBlock('width', realWidth);
				
				t.valuesHW(realHeight, realWidth);
				
				img.width = widthDisplayer;
				
				img.height = heightDisplayer;
				
				t.image = img;
				
				t.createSlider();
				
				$('#imagedisplayer').append(img);
				
				$('#imagedisplayer').css('height', Math.round((130*img.naturalHeight/img.naturalWidth)*2) + 'px');
			});
		}
	}
	else {
		
		this.cleanMediaDisplayer();
		
		var msg = $('<p>Seleccione un componente de imagen</p>');
		
		$('#imagedisplayer').append(msg);
	}
};

MediaDisplayer.prototype.createSlider = function() {
	
	var t = this;
	
	$('#imageslider').empty();
	
	var slr = $('<input type="text" id="sliderMedia" >');
	
	$('#imageslider').append(slr);
	
	$('#accept_cancel_image').show();
	$('#align_image').show();
	
	var value = 100;

	if(t.block.displayer.hasOwnProperty('percent')) {
		
		value = t.block.displayer.percent;
	}

	$('#sliderMedia').slider({min: 10, max: 200, value: value, step: 10})
		.on('slide', function(ev){
		
		t.block.displayer.percent = ev.value;
			
		var widthNatural = Math.round(t.image.naturalWidth*(ev.value/100));
		var heightNatural = Math.round(t.image.naturalHeight*(ev.value/100));
		
		t.image.width = 130*(ev.value/100);
		t.image.height = 130*(ev.value/100);
		
		t.block.displayer.width = t.image.width;
		t.block.displayer.height = t.image.height;
		
		t.valuesHW(heightNatural, widthNatural);
		
		t.block.setSizeImage(heightNatural, widthNatural);

		t.block.changeAttrImgBlock('width', widthNatural);
		t.block.changeAttrImgBlock('height', heightNatural);
	});
	
	$('.chose_align').on('click', function() {
		t.block.addClassContentImgBlock("pull-" + $(this).attr('data-dropdown'));
		t.block.setAlignImgBlock("pull-" + $(this).attr('data-dropdown'));
	});
};

MediaDisplayer.prototype.valuesHW = function(height, width) {
	
	$('#heightImg').empty();
	$('#heightImg').append('Alto: ' + height + 'px');
	
	$('#widthImg').empty();
	$('#widthImg').append('Ancho: ' + width + 'px');
};

MediaDisplayer.prototype.cleanMediaDisplayer = function() {
	
	$('#heightImg').empty();
	$('#widthImg').empty();
	$('#imageslider').empty();
	$('#imagedisplayer').css('height', '');
	$('#imagedisplayer').empty();
};

function NoMediaDisplayer() {
	
	media.setBlock(null);
	
	media.setGallery(null);

	media.cleanMediaDisplayer();
}