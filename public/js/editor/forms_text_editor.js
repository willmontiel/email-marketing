if (typeof RedactorPlugins === 'undefined') var RedactorPlugins = {};

RedactorPlugins.advanced = {
	init: function()
	{
		this.buttonAdd('form-updating', 'Formularios de actualización', this.showMyModal);
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
        this.insertHtml('<a href="' + $(html.target).attr('value') + '" >Actualice sus datos aquí</a>');
        this.modalClose();
    }
};