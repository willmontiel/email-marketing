function loadScore(container) { 
	$.getJSON(MyBaseURL + 'account/getscore',function(data){ 
		$("#" + container).empty();
		$("#" + container).append("" + data.score);
	});
};
