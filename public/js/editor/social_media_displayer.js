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
		App.fbimage = t.gallery.id;
//		$('#fbimagepublication').val(t.gallery.id);
	});
};

//Dropzone.autoDiscover = false;
//	var myDropzone = new Dropzone("#my-dropzone");
//	myDropzone.on("success", function(file, response) {
//		var newMedia = new Gallery(response.thumb, response.filelink, response.title, response.id);
//		newMedia.createMedia();
//		newMedia.mediaSelected();
//		media.setGallery(newMedia);
//		media.imageSelected(response.filelink, response.title);
//	});

function new_sn_account(redirect){
	$.ajax({
		url: config.baseUrl + "mail/savetmpdata",
		type: "POST",			
		data: $('#setupform').serialize(),
		success: function(){
			window.location.href = redirect;
		}
	});
}