function verHTML(form) {
	var inf = form.content.value;
	win = open("","DisplayWindow","toolbar=yes,directories=yes,menubar=yes,location=yes,directories=yes,width=800,height=570,left=1,top=1");
	win.document.write("" + inf + "");
}