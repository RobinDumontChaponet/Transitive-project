Chat = function (logElement, inputElement, messageTemplate, socket) {
	if(!logElement)
		throw ('Chat : No log element');

	this.logElement = logElement;
	this.inputElement = inputElement;
	this.messageTemplate = messageTemplate;
	this.socket = socket;

	this.socket.subscribe(this);

	var that = this;
	this.inputElement.onkeypress = function(evt) {
		evt = evt || window.event;
		if (evt.keyCode == 13) {
			if (!this.value)
				return false;

			that.send(this.value);

			this.value = '';
			this.focus();
		}
	};
}

Chat.prototype.receive = function (data) {
	if(data.request=='message' && data.method == 'post')
		this.log(data.content.sender, data.content.content, data.time);
}

Chat.prototype.send = function (message) {
	this.log('Me', message, Date.now());

	this.socket.send({
		'request' : 'message',
		'method'  : 'post',
		'content' : message
	});
}

Chat.prototype.onOpen = function () {
//	this.log('system', 'Connected.', Date.now());
}

Chat.prototype.onClose = function () {
//	this.log('system', 'Connection lost.', Date.now());
	this.inputElement.disabled = 'disabled';
}

Chat.prototype.log = function (user, content, time) {
	var date = new Date((time.toString().length==10)?time*1000 : time);
	time = {
		hours : date.getHours(),
		minutes : ('0' + date.getMinutes()).substr(-2),
		seconds : ('0' + date.getSeconds()).substr(-2),
	}
	time.humanReadable = time.hours + ':' + time.minutes + ':' + time.seconds

	this.logElement.innerHTML += this.messageTemplate.execute({
		'hours'     : time.hours,
		'minutes'   : time.minutes,
		'seconds'   : time.seconds,
		'userId'    : user,
		'userName'  : user,
		'message'   : content,
		'humanTime' : time.humanReadable
	});

	this.logElement.scrollTop = this.logElement.scrollHeight;
}