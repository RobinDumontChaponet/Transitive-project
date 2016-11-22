Pad = function (areaElement, linesElement, wordsCounterElement, readingTimeElement, socket) {
	if(!areaElement)
		throw ('Pad : No area element');

	this.areaElement = areaElement;
	this.linesElement = linesElement;
	this.wordsCounterElement = wordsCounterElement;
	this.readingTimeElement = readingTimeElement;

	this.areaCount = document.createElement('textarea');
	this.areaCount.id = 'countHelperElement';
	this.areaCount.setAttribute('aria-hidden', 'true');
	this.areaCount.style.visibility = 'hidden';
	this.areaCount.style.position = 'absolute';
	this.areaCount.style.padding = this.areaElement.style.padding;
	document.body.appendChild(this.areaCount);

	this.statusElement = document.createElement('section');
	this.statusElement.className = 'pad status';
	this.areaElement.parentNode.insertBefore(this.statusElement, this.areaElement.nextSibling);

	this.socket = socket;

	this.buffer = Array();

	this.diacritics = ['¨', '`', '´', 'ˎ', '｀', '＾', '⩯', '⨣', '^'];
	this.diacritic = false;

	this.linesCount = 0;
	this.connectedOnce = false;
	this.areaElement.disabled = true;

	this.selectionStart = 0;
	this.selectionEnd   = 0;
	this.selected = false;
	this.length   = 0;

	this.socket.subscribe(this);

	var that = this;
    this.areaElement.addEventListener('input', function(){that.refresh()});
    this.areaElement.addEventListener('input', function(e){that.write(e)});
    this.areaElement.addEventListener('input', throttle(function(){that.send()}, 500));

    this.areaElement.addEventListener('keyup', function(){that.updateSelection()});
	this.areaElement.addEventListener('mouseup', function(){that.updateSelection()});

	this.areaElement.addEventListener('scroll', function(){that.followScroll()});

/*
	this.refresh();
*/

	this.statusElement.classList.add('loading');
	this.statusElement.innerHTML = 'Connecting...';
};

Pad.prototype.refresh = function() {
	this.refreshLinesCount();
	this.refreshCounters();
}

Pad.prototype.updateSelection = function () {
	this.selectionStart = this.areaElement.selectionStart;
	this.selectionEnd   = this.areaElement.selectionEnd;

	if(this.selectionStart < this.selectionEnd)
		this.selected = true;
	else
		this.selected = false;

	this.length = this.areaElement.value.length;
}

Pad.prototype.write = function(event) {
	var content = {
		'action'   : (this.selected||this.diacritic)?'replace':'add',
		'position' : {
			'start' : (this.diacritic)?this.selectionStart-1:this.selectionStart,
			'end'	: (this.diacritic)?2:(this.selected)?this.selectionEnd:Math.abs(this.areaElement.value.length-this.length),
			'carret': getCaretPosition(this.areaElement)
		},
		'time'     : event.timeStamp
	};
	content.content = (this.selected)?this.areaElement.value.substr(content.position.start, content.position.carret-content.position.start):this.areaElement.value.substr(content.position.start, content.position.end);

	if(content.action != 'replace' && content.content === '')
		content.action = 'delete';

		console.log(this.diacritic);

	this.buffer.push(content);

	this.updateSelection();

	if(this.diacritics.indexOf(content.content)!=-1)
		this.diacritic = true;
	else
		this.diacritic = false;
}

Pad.prototype.send = function () {
	var content = {
		'request' : 'pad-content',
		'method'  : 'patch',
		'content' : this.buffer
	};

	this.socket.send(content);

	this.buffer = [];
}

Pad.prototype.followScroll = function () {
	this.linesElement.scrollTop = this.areaElement.scrollTop;
}

Pad.prototype.refreshLinesCount = function () {
	var count = 0;

	this.areaCount.style.width = this.areaElement.clientWidth;
	this.areaCount.style.height = this.areaElement.clientHeight;
	this.areaCount.value = '';

	var value = this.areaElement.value;
	var words = value.split(/\W/);

	console.log(words.length);

	this.areaCount.value = words[0];
	var areaCountHeight = this.areaCount.scrollHeight;

	console.log(this.areaCount.scrollHeight);

	for(var i = 1; i < words.length; i++) {
		this.areaCount.value += ' ' + words[i];
		if(this.areaCount.scrollHeight > areaCountHeight) {
			areaCountHeight = this.areaCount.scrollHeight;

			count++;
		}
	}

	console.log(count);

	if(this.linesCount != count) {
		var tmp ='';
		for (var i=1; i <= count; i++)
			tmp += i + "." + "<br />";
		this.linesElement.innerHTML = tmp;
		this.followScroll();
		this.linesCount = count;
	}
}

Pad.prototype.refreshCounters = function () {
	var value  = this.areaElement.value,
	charsCount = value.length;
	if(charsCount) {
		var wordsCount = (value.trim())?value.trim().match(/(\S+)/g).length:0;
//		linesCount = (charsCount)?value.split("\n").length:0;

		this.wordsCounterElement.innerHTML = charsCount+' character'+((charsCount>1)?'s':'')+',  '+wordsCount+' word'+((wordsCount>1)?'s':'')+',  '+this.linesCount+' line'+((this.linesCount>1)?'s':'')+'.';

		var timeSeconds = this.countReadingTime(wordsCount),
		timeMinutes = Math.floor(timeSeconds / 60);
		timeHours = Math.floor(timeMinutes / 60);

		if(timeHours>0)
			this.readingTimeElement.innerHTML = timeHours +' h '+ Math.floor(timeMinutes % 60) +' m '+ Math.floor(timeSeconds % 60) +' s';
		else if(timeMinutes>0)
			this.readingTimeElement.innerHTML = timeMinutes +' m '+ Math.floor(timeSeconds % 60) +' s';
		else
			this.readingTimeElement.innerHTML = Math.floor(timeSeconds) +' s';

	} else
		this.wordsCounterElement.innerHTML = '- empty -'
}

Pad.prototype.countReadingTime = function (wordsCount) {
	var wordsPerMinute = 270;

	return wordsCount / (wordsPerMinute / 60);
}

Pad.prototype.receive = function (data) {
	if(data.request == 'pad-content') {
//		console.log('pad received', data.content);

		if(data.method == 'patch') {
			var that = this;

			data.content.forEach(function(data) {
				if(data.action == 'add')
					that.areaElement.value = that.areaElement.value.substr(0, data.position.start) + data.content + that.areaElement.value.substr(data.position.start);
				else if(data.action == 'replace')
					that.areaElement.value = that.areaElement.value.substr(0, data.position.start) + data.content + that.areaElement.value.substr(data.position.end);
				else if(data.action == 'delete')
					that.areaElement.value = that.areaElement.value.substr(0, data.position.start-data.position.end) + that.areaElement.value.substr(data.position.start);
			});
		} else if(data.method == 'put')
			this.areaElement.value = data.content.content;

		this.updateSelection();
		this.refresh();

		this.setStatusOk();
		this.areaElement.disabled = false;
	}
}

Pad.prototype.onClose = function () {
	if(this.connectedOnce)
		this.setStatusError('Connection lost.');
	else
		this.setStatusError('Can\'t connect to server.');

	this.areaElement.disabled = true;
}

Pad.prototype.onOpen = function () {
	this.connectedOnce = true;

	this.socket.send({
		'request' : 'pad-title',
		'method'  : 'get'
	});

	this.socket.send({
		'request' : 'pad-content',
		'method'  : 'get'
	});

	this.setStatusLoading('Connected. Pulling data.');

	var that = this;
	setTimeout(function(){
		if(that.statusElement.innerHTML == 'Connected. Pulling data.') // Uhuh.
			that.setStatusLoading('Waiting for data.');
	}, 800);
}

Pad.prototype.setStatusLoading = function (status) {
	this.statusElement.innerHTML = status;
	this.statusElement.style.display = 'block';
	this.statusElement.classList.add('loading');
}

Pad.prototype.setStatusError = function (status) {
	this.statusElement.innerHTML = status;
	this.statusElement.style.display = 'block';
	this.statusElement.classList.remove('loading');
}

Pad.prototype.setStatusOk = function () {
	this.statusElement.classList.remove('loading');
	this.statusElement.style.display = 'none';
	this.statusElement.innerHTML = 'Ok.';
}

/*
Pad.prototype.resizeArea = function (el) {
	var height = this.areaElement.scrollHeight,
	parentElement = this.areaElement.parentNode;
	if (height < parentElement.clientHeight)
			this.areaElement.style.height = parentElement.clientHeight+'px';
	else
		this.areaElement.style.height = height+'px';
}
*/