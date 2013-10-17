function Layout (id, name, description, icon, zones) {
	this.id = id;
	this.name = name;
	this.description = description;
	this.icon = icon;
	this.zones = zones;
}

Layout.prototype.createlayout = function() {
	var obj = $("<div class='layout' id='" + this.name + "'> \
					<a href='#' data-toggle='tab'>\
						<img src='" + this.icon + "' alt='64x64' />\
					</a>\
				</div>");
	
	obj.data('layoutObj', this);

	$('#layouts').append(obj);
};

var layouts = [
	new Layout(1, 'layout-simple', 'Layout Standard', "/emarketing/images/n1.png", [{name: 'pre-header', width: 'full-width'}, {name: 'header', width: 'full-width'}, {name: 'body', width: 'full-width'}, {name: 'footer', width: 'full-width'}]),
	new Layout(2, 'layout-two-columns', 'Layout with 2 Columns', "/emarketing/images/1m.png", [{name: 'pre-header', width: 'full-width'}, {name: 'header', width: 'full-width'}, {name: 'body', width: 'full-width'}, {name: 'column1', width: 'half-width'}, {name: 'column2', width: 'half-width'}, {name: 'footer', width: 'full-width'}]),
	new Layout(3, 'layout-three-columns', 'Layout with 3 Columns', "/emarketing/images/2m.png", [{name: 'pre-header', width: 'full-width'}, {name: 'header', width: 'full-width'}, {name: 'body', width: 'full-width'}, {name: 'column1', width: 'third-width'}, {name: 'column2', width: 'third-width'}, {name: 'column3', width: 'third-width'}, {name: 'footer', width: 'full-width'}]),
	new Layout(4, 'layout-left-sidebar', 'Layout with Left Sidebar', "/emarketing/images/18m.png", [{name: 'pre-header', width: 'full-width'}, {name: 'header', width: 'full-width'}, {name: 'sidebar', width: 'third-width'}, {name: 'body', width: 'twothird-width'}, {name: 'footer', width: 'full-width'}]),
	new Layout(5, 'layout-right-sidebar', 'Layout with Right Sidebar', "/emarketing/images/19m.png", [{name: 'pre-header', width: 'full-width'}, {name: 'header', width: 'full-width'}, {name: 'body', width: 'twothird-width'}, {name: 'sidebar', width: 'third-width'}, {name: 'footer', width: 'full-width'}]),
];