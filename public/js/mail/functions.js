/* 
 * Funciones utilizadas en el proceso de edición del correo
 */

function validateSender(mail){
	var sender = mail.get('sender');
	
	if (sender === undefined || sender === null || sender === '') {
		return false;
	}
	
	return true;
}

function getArrayValue(value) {
	if( value !== null && value !== undefined ) {
		var array = [];
		var obj = value.toArray();
		for (var i = 0; i < obj.length; i++) {
			array.push(obj[i].id);
		}
		return array.toString();
	}
	return '';
}

function setExpandAttr(self, expand) {
	if(self.get(expand)) {
		self.set(expand, false);
	}
	else {
		self.set(expand, true);
	}
}

function setTargetValues(values, select) {
	var newArray = [];
	if(select !== undefined) {
		var array = values.split(",");
		for (var i = 0; i < select.length; i++) {
			for (var j = 0; j < array.length; j++) {
				array[j] = (typeof array[j] === 'string')?parseInt(array[j]):array[j];
				if (select[i].id === array[j]) {
					newArray.push(select[i]);
				}
			}
		}
	}
	return newArray;
}

function setTargetValue(value, select) {
	var object;
	for (var j = 0; j < select.length; j++) {
		if (select[j].id === value) {
			object = select[j];
		}
	}
	
	return object;
}

function setGoogleAnalyticsValues(values, select) {
	var array = values.split(",");
	var newArray = [];
	for (var i = 0; i < select.length; i++) {
		for (var j = 0; j < array.length; j++) {
			if (select[i].name === array[j]) {
				newArray.push(select[i]);
			}
		}
	}
	return newArray;
}

function setFilterValues(values, checked, self) {
	values = (values === '')?null:values;
	if (values) {
		self.set(checked, 'display: block;');
		return true;
	}	
	self.set(checked, 'display: none;');
	return false;
}

function getTime(date) {
	var hour = '' + date.hour();
	hour = (hour.length === 1)? '0' + hour: hour;
	var minutes = '' + date.minute();
	minutes = (minutes.length === 1)? '0' + minutes: minutes;
		
	var time = hour + ':' + minutes;
	return time;
}

function getMonth(date) {
	var m = date.month() + 1;
	var month = moment('' + m).lang('es').format('MMMM');	
	return month;	
}

function getNumberMonth(date) {
	var m = date.month() + 1;	
	return m;
}

function getDay(date) {
	var day = date.date();
	return day;
}

function getYear(date) {
	var year = date.year();
	return year;
}