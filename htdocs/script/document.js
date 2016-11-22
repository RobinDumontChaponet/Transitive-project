function init (userString) {
	var logTemplate = new HtmlTemplate(document.querySelector('#log-template').innerHTML),
		wrapperElement = document.querySelector('#wrapper'),
		discussionElement = document.querySelector('#discussion'),

		socket = new Socket('ws://pad:9000/'+userString,
			function (user, content, time) {
				discussionElement.innerHTML += logTemplate.execute({
					'hours'     : time.hours,
					'minutes'   : time.minutes,
					'seconds'   : time.seconds,
					'message'   : content,
					'humanTime' : time.humanReadable,
				});

				discussionElement.scrollTop = discussionElement.scrollHeight;
			}
		),
		pad = new Pad(document.querySelector('#area'), document.querySelector('#lines'), document.querySelector('#count'), document.querySelector('#reading-time'), socket),

		chat = new Chat(discussionElement, document.querySelector('#speak'), new HtmlTemplate(document.querySelector('#message-template').innerHTML), socket),

		userList = new UserList(document.querySelector('#users'), new HtmlTemplate(document.querySelector('#user-li-template').innerHTML), socket);

	function toggleLeft () {
		wrapperElement.classList.toggle('noleft');
	}
	function toggleRight () {
		wrapperElement.classList.toggle('noright');
	}

	document.querySelector('#noleft').addEventListener('click', toggleLeft);
	document.querySelector('#noright').addEventListener('click', toggleRight);

	document.onkeydown = function(evt) {
		evt = evt || window.event;
		if (evt.keyCode == 27) {
			toggleLeft();
			toggleRight();

			return false;
		}
	};
}