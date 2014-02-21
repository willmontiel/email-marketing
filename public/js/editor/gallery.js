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
	var obj = $("<a id = \"media" + this.id + "\" class=\"medias\" href=\"#\" data-toggle=\"tab\"><img class='image-from-gallery' src=\"" + this.srcThumb + "\" alt=\"" + this.title + "\"> </a>");
	obj.data('mediaObj', this);
	$('#gallery').append(obj);
};

Gallery.prototype.mediaSelected = function() {	
	var t = this;
	$('#gallery a#media' + this.id).on('click', function() {
		$('.image-galery-selected').removeClass('image-galery-selected');
		$(this).find('img').addClass('image-galery-selected');
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
	$('#accept_change').off('click');
	if(newsrc !== undefined) {
		var t = this;
		$('#accept_change').on('click', function() {
			$('.image-galery-selected').removeClass('image-galery-selected');
			if(t.block.content.find('img').hasClass('image-placeholder')) {
				t.block.content.find('img').removeClass('image-placeholder');
				t.block.content.find('img').removeAttr('data-toggle');
				t.block.content.find('img').removeAttr('data-backdrop');
				t.block.content.find('img').removeAttr('href');
			}
			t.block.changeAttrImgBlock('src', newsrc);
			t.block.changeAttrImgBlock('alt', title);
			var img = new Image();
			img.src = newsrc;
			$(img).on('load', function() {
				var width = (img.naturalWidth > t.block.widthZone) ? t.block.widthZone : img.naturalWidth;
				var height = width*img.naturalHeight/img.naturalWidth;
				t.block.setImageSrc(newsrc);
				t.block.setImageAlt(title);
				t.block.setAlignImgBlock("center");
				t.block.setVerticalAlignImgBlock("middle");
				t.block.setSizeImage(height, width);
				t.block.changeAttrImgBlock('height', height);
				t.block.changeAttrImgBlock('width', width);
				t.block.addStyleContentImgBlock('text-align', t.block.align);
			});
		});
	}
	$('#cancel_change').off('click');
	$('#cancel_change').on('click', function() {
		$('.image-galery-selected').removeClass('image-galery-selected');
	});
};

function NoMediaDisplayer() {
	media.setBlock(null);
	media.setGallery(null);
}