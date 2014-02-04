function Layout (id, name, description, icon, zones) {
	this.id = id;
	this.name = name;
	this.description = description;
	this.icon = icon;
	this.zones = zones;
}

Layout.prototype.createlayout = function() {
	var obj = $("<div class='layout' id='" + this.name + "' data-dismiss='modal'> \
					<a href='#' data-toggle='tab'>\
						<img src='" + this.icon + "' alt='64x64' />\
					</a>\
				</div>");
	
	obj.data('layoutObj', this);

	$('#select-layout .layout-list').append(obj);
};

var layouts = [
	new Layout(1, 'layout-simple', 'Layout Standard', config.imagesUrl + "/n1.png", [{name: 'preheader', width: 'full-width', widthval: 600}, {name: 'header', width: 'full-width', widthval: 600}, {name: 'body', width: 'full-width', widthval: 600}, {name: 'footer', width: 'full-width', widthval: 600}]),
	new Layout(2, 'layout-two-columns', 'Layout with 2 Columns', config.imagesUrl + "/1m.png", [{name: 'preheader', width: 'full-width', widthval: 600}, {name: 'header', width: 'full-width', widthval: 600}, {name: 'body', width: 'full-width', widthval: 600}, {name: 'column1', width: 'half-width', widthval: 300}, {name: 'column2', width: 'half-width', widthval: 300}, {name: 'footer', width: 'full-width', widthval: 600}]),
	new Layout(3, 'layout-three-columns', 'Layout with 3 Columns', config.imagesUrl + "/2m.png", [{name: 'preheader', width: 'full-width', widthval: 600}, {name: 'header', width: 'full-width', widthval: 600}, {name: 'body', width: 'full-width', widthval: 600}, {name: 'column1', width: 'third-width', widthval: 200}, {name: 'column2', width: 'third-width', widthval: 200}, {name: 'column3', width: 'third-width', widthval: 300}, {name: 'footer', width: 'full-width', widthval: 600}]),
	new Layout(4, 'layout-left-sidebar', 'Layout with Left Sidebar', config.imagesUrl + "/18m.png", [{name: 'preheader', width: 'full-width', widthval: 600}, {name: 'header', width: 'full-width', widthval: 600}, {name: 'sidebar', width: 'third-width', widthval: 200}, {name: 'body', width: 'twothird-width', widthval: 400}, {name: 'footer', width: 'full-width', widthval: 600}]),
	new Layout(5, 'layout-right-sidebar', 'Layout with Right Sidebar', config.imagesUrl + "/19m.png", [{name: 'preheader', width: 'full-width', widthval: 600}, {name: 'header', width: 'full-width', widthval: 600}, {name: 'body', width: 'twothird-width', widthval: 400}, {name: 'sidebar', width: 'third-width', widthval: 200}, {name: 'footer', width: 'full-width', widthval: 600}])
];