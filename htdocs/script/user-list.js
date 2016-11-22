UserList = function (listElement, liTemplate, socket) {
	if(!listElement)
		throw ('UserList : No list element');

	this.listElement = listElement;
	this.liTemplate = liTemplate;
	this.socket = socket;

	this.socket.subscribe(this);

	this.list = Array();

	var that = this;
}

UserList.prototype.receive = function (data) {
	var that = this;

	if(data.request=='user-list')
		if(data.method == 'put')
			data.content.forEach(function(user) {
				that.add(user);
			})
		else if(data.method == 'post')
			data.content.forEach(function(user) {
				that.add(user);
			})
		else if(data.method == 'delete')
			data.content.forEach(function(user) {
				that.remove(user);
			})
}

UserList.prototype.onOpen = function () {
	this.socket.send({
		'request' : 'user-list',
		'method' : 'get'
	});
}

UserList.prototype.onClose = function () {
	this.list = Array();

	this.listElement.innerHTML = '';
}


UserList.prototype.add = function (user) {
	this.list.push(user);

	this.refreshDom();
}

UserList.prototype.remove = function (user) {
	for(var i=this.list.length-1; i>=0; i--)
		if(this.list[i].login == user.login)
			this.list.splice(i, 1);

	this.refreshDom();
}

UserList.prototype.refreshDom = function () {
	var content = '',
	that = this;

	this.list.forEach(function(user) {
		content += that.liTemplate.execute(user);
	});

	this.listElement.innerHTML = content;
}