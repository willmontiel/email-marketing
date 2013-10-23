//------------------------//------------------------//------------------------//
//------------------------//--------Gallery---------//------------------------//
//------------------------//------------------------//------------------------//

function Gallery(srcThumb, srcImage, id) {
	this.srcThumb = srcThumb;
	this.srcImage = srcImage;
	this.id = id;
}

Gallery.prototype.createMedia = function() {
	var obj = $("<a id = \"media" + this.id + "\" href=\"#\" data-toggle=\"tab\"><img src=\"" + this.srcThumb + "\" alt=\"64x64\"> </a>");
	
	obj.data('mediaObj', this);

	$('#gallery').append(obj);
};

Gallery.prototype.mediaSelected = function() {	
	var t = this
	
	$('#gallery a#media' + this.id).on('click', function() {
		
		media.setGallery(t);
		media.Selected(t.srcImage);
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

MediaDisplayer.prototype.Selected = function(newsrc) {

	$('#imagedisplayer').empty();

	if (this.block != null && this.block.hasOwnProperty('htmlData')) {
		
		if(newsrc == '/emarketing/images/image') {
			
			this.cleanMediaDisplayer();
			
			var msg = $('<p>Seleccione una imagen de la galeria</p>');
			
			$('#imagedisplayer').append(msg);
		}
		else {
			
			this.block.changeAttrBlock('src', newsrc);

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
					
					var widthDisplayer = 90;
					
					var heightDisplayer = 90*img.naturalHeight/img.naturalWidth;
					
					t.block.assignDisplayer({imagesrc: newsrc, percent: 100, width: widthDisplayer, height: heightDisplayer});
				}
				
				t.block.setSizeImage(realHeight, realWidth);
					
				t.block.changeAttrBlock('height', realHeight);
					
				t.block.changeAttrBlock('width', realWidth);
				
				t.valuesHW(realHeight, realWidth);
				
				img.width = widthDisplayer;
				
				img.height = heightDisplayer;
				
				t.image = img;
				
				t.createSlider();
				
				$('#imagedisplayer').append(img);
			
				$('#imagedisplayer').css('height', Math.round((90*img.naturalHeight/img.naturalWidth)*3) + 'px');
			});
		}
	}
	else {
		
		this.cleanMediaDisplayer();
		
		var msg = $('<p>Seleccione un elemento</p>');
		
		$('#imagedisplayer').append(msg);
	}
};

MediaDisplayer.prototype.createSlider = function() {
	
	var t = this;
	
	$('#imageslider').empty();
	
	var slr = $('<input type="text" id="sliderMedia" >');
	
	$('#imageslider').append(slr);
	
	var value = 100;

	if(t.block.displayer.hasOwnProperty('percent')) {
		
		value = t.block.displayer.percent;
	}

	$('#sliderMedia').slider({min: 10, max: 300, value: value, step: 10})
		.on('slide', function(ev){
		
		t.block.displayer.percent = ev.value;
			
		var widthNatural = Math.round(t.image.naturalWidth*(ev.value/100));
		var heightNatural = Math.round(t.image.naturalHeight*(ev.value/100));
		
		t.image.width = 90*(ev.value/100);
		t.image.height = 90*(ev.value/100);
		
		t.block.displayer.width = t.image.width;
		t.block.displayer.height = t.image.height;
		
		t.valuesHW(heightNatural, widthNatural);
		
		t.block.setSizeImage(heightNatural, widthNatural);

		t.block.changeAttrBlock('width', widthNatural); 
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
};