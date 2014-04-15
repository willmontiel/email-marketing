//$(function() {
//	
//});

function FormEditor() {
	this.content = {};
}

var formeditor = new FormEditor();

FormEditor.prototype.starEvents = function() {
	console.log('paso por aqui')
	$('.form-options').on('click', function() {
		console.log($(this).data('type'))
		var newfield= $('<div class="form-field form-field-' + $(this).data('id') + '">\n\
			<div class="row field-content-zone">\n\
				<div class="col-md-4 field-zone-name">\n\
					' + $(this).data('name') + '\n\
				</div>\n\
				<div class="col-md-7">\n\
					<input type="text" class="form-control" placeholder="Escribe ' + ($(this).data('name')).toLowerCase() + '">\n\
				</div>\n\
			</div>\n\
		</div>');

		$('.form-content-zone').append(newfield);
		$(this).addClass('field-option-disabled').off('click');
	});
};