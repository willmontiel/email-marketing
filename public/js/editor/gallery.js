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
		media.imageSelected(t.srcImage, t.title);
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

MediaDisplayer.prototype.imageSelected = function(newsrc, title) {
	
	$('#imagedisplayer').empty();
	
	this.widthZone =  this.block.widthZone;
	
	if (this.block !== null && this.block.hasOwnProperty('content')) {

		if(newsrc === undefined) {
			
			delete this.oldImage;
	
			this.cleanMediaDisplayer();
			
			var msg = $('<p>Seleccione una imagen de la galeria</p>');
			
			$('#imagedisplayer').append(msg);
		}
		else {

			this.block.content.find('img').removeClass('image-placeholder');
			
			this.block.changeAttrImgBlock('src', newsrc);
			
			this.block.changeAttrImgBlock('alt', title);

			var img = new Image();

			img.src = newsrc;

			var t = this;

			$(img).on('load', function() {

				if(t.block.hasOwnProperty('displayer') && t.block.displayer.imagesrc === newsrc) {
					
					t.oldImage = JSON.stringify(t.block.persist());
					
					var realHeight = t.block.height;
					
					var realWidth = t.block.width;

					var widthDisplayer = t.block.displayer.width;
					
					var heightDisplayer = t.block.displayer.height;
				}
				else {
				
					var realWidth = (img.naturalWidth > t.widthZone) ? t.widthZone : img.naturalWidth;
					
					var realHeight = realWidth*img.naturalHeight/img.naturalWidth;
					
					var widthDisplayer = 130;
					
					var heightDisplayer = 130*img.naturalHeight/img.naturalWidth;
					
					t.block.setImageSrc(newsrc);
					
					t.block.assignDisplayer({imagesrc: newsrc, percent: 100, width: widthDisplayer, height: heightDisplayer});
					
					t.block.setAlignImgBlock("center");
					
					t.block.setVerticalAlignImgBlock("middle");
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
				t.block.addStyleContentImgBlock('text-align', t.block.align);
				$('#imagedisplayer').append('<span class="image-displayer-helper" />');
				$('#imagedisplayer').append(img);
				$('#imagedisplayer').css('text-align', t.block.align);
				$('#imagedisplayer .image-displayer-helper').css('vertical-align', t.block.vertalign);
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
	this.cleanslider();
	var totalWidthBlock = (this.image.naturalWidth > this.widthZone) ? this.widthZone : this.image.naturalWidth;
	var maxWidthZone = this.widthZone;
	console.log(this.widthZone)
	var maxwidthpx =maxWidthZone*100/this.image.naturalWidth;
	var realmax = Math.round(maxwidthpx);
	var value = (this.block.displayer.hasOwnProperty('percent')) ? this.block.displayer.percent : maxwidthpx*this.image.naturalWidth/totalWidthBlock;
	this.activateSlider(value, realmax);
	this.displayerEvents();
};

MediaDisplayer.prototype.cleanslider = function() {
	$('#imageslider').empty();
	$('#link_image').empty();
	$('#imageslider').append($('<br/> <div>Aumentar o Disminuir el Tamaño de la Imagen</div>'));
	$('#imageslider').append($('<input type="text" id="sliderMedia" >'));
	$('#align_image').show();
	$('#align_vertical_image').show();
	$('#link_image').show();
	$('#link_image').append('<label>Agregar Hipervinculo<br/><input id="link_to_image" type="text" placeholder="Escriba Link"></label>');
	$('#link_to_image').val(this.block.imglink);
	$('#imageslider').append($('<div class="maxwidth"><label class="checkbox"><input id="maxwidthimg" class="target" type="checkbox">Tamaño Maximo</label></div>'));
	if(this.block.width === this.block.row.dz.widthval/this.block.row.listofblocks.length) {
		$('#maxwidthimg')[0].checked = true;
	}
};

MediaDisplayer.prototype.activateSlider = function(value, realmax) {
	var t = this;
	$('#sliderMedia').slider({min: 10, max: realmax, value: value, step: 1})
		.on('slide', function(ev){
	
		$('#maxwidthimg')[0].checked = false;
		
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
};

MediaDisplayer.prototype.displayerEvents = function() {
	var t = this;
	$('.chose_align').on('click', function() {
		$('#imagedisplayer').css('text-align', $(this).attr('data-dropdown'));
		t.block.addStyleContentImgBlock('text-align', $(this).attr('data-dropdown'));
		t.block.setAlignImgBlock($(this).attr('data-dropdown'));
	});
	
	$('.chose_vertical_align').on('click', function() {
		$('#imagedisplayer .image-displayer-helper').css('vertical-align', $(this).attr('data-dropdown'));
		t.block.addVerticalAlignToImage($(this).attr('data-dropdown'));
		t.block.setVerticalAlignImgBlock($(this).attr('data-dropdown'));
	});
	
	$('#cancel_change').on('click', function() {
		t.block.unpersist(t.oldImage);
		t.block.changeAttrImgBlock('height', t.block.height);
		t.block.changeAttrImgBlock('width', t.block.width);
		t.block.changeAttrImgBlock('src', t.block.imgsrc);
		t.block.addStyleContentImgBlock('text-align', t.block.align);
		t.block.addVerticalAlignToImage(t.block.vertalign);
	});
	
	$('#link_to_image').on('change', function() {
		t.block.setLinkToImage($(this).val());
	});
	
	$('#maxwidthimg').on('change', function(value) {
		if($('#maxwidthimg')[0].checked) {
			var width = t.block.row.dz.widthval/t.block.row.listofblocks.length;
			var widthNatural = t.widthZone;
			var heightNatural = Math.floor(t.image.naturalHeight*t.widthZone/t.image.naturalWidth);
			t.block.setSizeImage( Math.floor(t.image.naturalHeight*width/t.image.naturalWidth), width);
			var height =  Math.floor(t.image.naturalHeight*width/t.image.naturalWidth);
		}
		else {
			var widthNatural = Math.floor(t.image.naturalWidth*(t.block.displayer.percent/100));
			var heightNatural = Math.floor(t.image.naturalHeight*(t.block.displayer.percent/100));
			t.block.setSizeImage(heightNatural, widthNatural);
			var width = widthNatural;
			var height = heightNatural;
		}
		
		t.block.setTableColumn('width', widthNatural);

		t.valuesHW(height, width);

		t.block.changeAttrImgBlock('width', widthNatural);
		t.block.changeAttrImgBlock('height', heightNatural);
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
	$('#link_image').empty();
	$('#imagedisplayer').css('height', '');
	$('#imagedisplayer').empty();
	$('#align_image').hide();
	$('#align_vertical_image').hide();
	$('#link_image').hide();
};

function NoMediaDisplayer() {
	media.setBlock(null);
	media.setGallery(null);
	media.cleanMediaDisplayer();
}