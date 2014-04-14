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