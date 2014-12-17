if (!RedactorPlugins) var RedactorPlugins = {};

RedactorPlugins.fontfamily = {
	init: function ()
	{
		var fonts = [ 'Arial', 'Helvetica', 'Georgia', 'Lucida', 'Tahoma', 'Verdana', 'Times New Roman', 'Monospace', 'Calibri, sans-serif', 'Comic Sans MS, sans-serif', 'Courier New, sans-serif', 'Trebuchet MS, sans-serif', 'Franklin Gothic Medium, sans-serif', 'Garamond, sans-serif', 'Century, sans-serif', 'Century Gothic, sans-serif'];
		var that = this;
		var dropdown = {};

		$.each(fonts, function(i, s)
		{
			dropdown['s' + i] = { title: s, callback: function() { that.setFontfamily(s); }};
		});

		dropdown['remove'] = { title: 'Remove font', callback: function() { that.resetFontfamily(); }};

		this.buttonAdd('fontfamily', 'Change font family', false, dropdown);
	},
	setFontfamily: function (value)
	{
		this.inlineSetStyle('font-family', value);
	},
	resetFontfamily: function()
	{
		this.inlineRemoveStyle('font-family');
	}
};