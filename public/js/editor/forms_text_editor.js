if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};

RedactorPlugins.advanced = {
	init: function()
	{
		this.buttonAdd('form-updating', 'Formularios de actualización', this.showMyModal);
//		this.buttonAwesome('advanced', 'fa-bullhorn');
	},
	showMyModal: function()
    {
        var callback = $.proxy(function()
        {
            this.selectionSave();
			$('.selected-form-class').click($.proxy(this.insertFromMyModal, this));

        }, this);

        this.modalInit('Formularios', '#forms-update', 500, callback);
    },
    insertFromMyModal: function(html)
    {
        this.selectionRestore();
        this.insertHtml($(html.target).attr('value'));
        this.modalClose();
		$.gritter.add({title: 'Recuerde', text: 'El formulario que acabo de agregar debe pertenecer a la base de datos de los destinatarios', sticky: false, time: 4000});
    }
};