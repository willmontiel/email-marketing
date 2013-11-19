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
	
	this.widthZone =  this.block.widthZone;
	
	if (this.block != null && this.block.hasOwnProperty('htmlData')) {
		
		if(newsrc == undefined) {
			
			delete this.oldImage;
	
			this.cleanMediaDisplayer();
			
			var msg = $('<p>Seleccione una imagen de la galeria</p>');
			
			$('#imagedisplayer').append(msg);
		}
		else {
			
			this.block.htmlData.find('img').removeClass('image-placeholder');
			
			this.block.htmlData.find('img').removeClass('image-text-placeholder');
			
			this.block.changeAttrImgBlock('src', newsrc);
			
			this.block.changeAttrImgBlock('alt', title);

			var img = new Image();

			img.src = newsrc;

			var t = this;

			$(img).on('load', function() {

				if(t.block.hasOwnProperty('displayer') && t.block.displayer.imagesrc === newsrc) {
					
					t.oldImage = JSON.stringify(t.block.persistImage());
					
					var realHeight = t.block.height;
					
					var realWidth = t.block.width;

					var widthDisplayer = t.block.displayer.width;
					
					var heightDisplayer = t.block.displayer.height;
				}
				else {
				
					if( t.block.typeBlock.search('text') > 0 ) {
						
						var realWidth = (img.naturalWidth > t.widthZone*3/4) ? t.widthZone*3/4 : img.naturalWidth;
					}
					else {
						
						var realWidth = (img.naturalWidth > t.widthZone) ? t.widthZone : img.naturalWidth;
					}
					
					var realHeight = realWidth*img.naturalHeight/img.naturalWidth;
					
					var widthDisplayer = 130;
					
					var heightDisplayer = 130*img.naturalHeight/img.naturalWidth;
					
					t.block.assignDisplayer({imagesrc: newsrc, percent: 100, width: widthDisplayer, height: heightDisplayer});
					
					t.block.setAlignImgBlock("pull-left");
				}
				
				t.block.setSizeImage(realHeight, realWidth);
				
				t.block.setTableColumn('width', realWidth);
				
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
	
	$('#imageslider').append($('<br/> <div>Aumentar o Disminuir el Tamaño de la Imagen</div>'));
	
	var slr = $('<input type="text" id="sliderMedia" >');
	
	$('#imageslider').append(slr);
	
	$('#align_image').show();
	
	var totalWidthBlock = (t.image.naturalWidth > t.widthZone) ? t.widthZone : t.image.naturalWidth;
	var maxWidthZone = t.widthZone;
	
	if( t.block.typeBlock.search('text') > 0 ) {
		
		totalWidthBlock = (t.image.naturalWidth > t.widthZone*3/4) ? t.widthZone*3/4 : t.image.naturalWidth;
		maxWidthZone = t.widthZone*3/4;
	}
	
	var maxwidthpx =maxWidthZone*100/t.image.naturalWidth;
	
	var realmax = Math.floor(maxwidthpx);
	
	var value = maxwidthpx*t.image.naturalWidth/totalWidthBlock;;
		
	if(t.block.displayer.hasOwnProperty('percent')) {
		
		value = t.block.displayer.percent;
	}
	
	$('#sliderMedia').slider({min: 10, max: realmax, value: value, step: 1})
		.on('slide', function(ev){
		
		t.block.displayer.percent = ev.value;
			
		var widthNatural = Math.floor(t.image.naturalWidth*(ev.value/100));
		var heightNatural = Math.floor(t.image.naturalHeight*(ev.value/100));
		
		t.image.width = 130*(ev.value/100);
		t.image.height = 130*(ev.value/100);
		
		t.block.displayer.width = t.image.width;
		t.block.displayer.height = t.image.height;
		
		t.block.setTableColumn('width', widthNatural);
		
		t.valuesHW(heightNatural, widthNatural);
		
		t.block.setSizeImage(heightNatural, widthNatural);
		
		t.block.changeAttrImgBlock('width', widthNatural);
		t.block.changeAttrImgBlock('height', heightNatural);
	});
	
	$('.chose_align').on('click', function() {
		t.block.addClassContentImgBlock("pull-" + $(this).attr('data-dropdown'));
		t.block.setAlignImgBlock("pull-" + $(this).attr('data-dropdown'));
	});
	
	$('#cancel_change').on('click', function() {
		t.block.unpersistImage(t.oldImage);
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
	$('#align_image').hide();
};

function NoMediaDisplayer() {
	
	media.setBlock(null);
	
	media.setGallery(null);

	media.cleanMediaDisplayer();
}