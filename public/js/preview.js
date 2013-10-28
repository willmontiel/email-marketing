function verHTML(form) {
	var inf = form.content.value;
	win = open("", "DisplayWindow", "toolbar=0, titlebar=yes , status=1, directories=yes, menubar=0, location=yes, directories=yes, width=700, height=650, left=1, top=0");
	win.document.write("" + inf + "");
}