//------------------------//------------------------//------------------------//
//------------------------//-Social-Media-Displayer-//------------------------//
//------------------------//------------------------//------------------------//


function SocialMediaDisplayer(block, gallery, image) {
	this.gallery = gallery;
	this.block = block;
	this.image = image;
}

var media = new SocialMediaDisplayer();

SocialMediaDisplayer.prototype.setGallery = function(gallery) {
	this.gallery = gallery;	
};

SocialMediaDisplayer.prototype.imageSelected = function() {
	$('#accept_change').off('click');
	var t = this;
	$('#accept_change').on('click', function() {
		$('#fb-share-image').attr('src', t.gallery.srcImage);
		$('#fbimagepublication').val(t.gallery.srcImage);
	});
};

Dropzone.autoDiscover = false;

$(function() {
	$('#fb-share-image').attr('src', $('#fbimagepublication').val());
	var myDropzone = new Dropzone("#my-dropzone");
	myDropzone.on("success", function(file, response) {
		var newMedia = new Gallery(response.thumb, response.filelink, response.title, response.id);
		newMedia.createMedia();
		newMedia.mediaSelected();
		media.setGallery(newMedia);
		media.imageSelected(response.filelink, response.title);
	});	
});