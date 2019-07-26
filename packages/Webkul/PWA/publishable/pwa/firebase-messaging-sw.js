importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js');


firebase.initializeApp({messagingSenderId: '1007045788486'});

const messaging = firebase.messaging();

// if(firebase.messaging.isSupported()){
messaging.setBackgroundMessageHandler(function (payload) {

	var data = payload.data;

	data = JSON.parse(data.data);
	let _notifier = data.notification;

	return self.registration.showNotification(
		_notifier.title, {
			title: _notifier.title,
			body: _notifier.body,
			click_action: _notifier.url,
			icon: _notifier.image,
			time_to_live: 30,
			tag: _notifier.url
		}
	);
});

self.addEventListener('notificationclick', function(event) {

	let url = event.notification.tag;//.click_action;
	console.log( event.notification )
	event.notification.close(); // Android needs explicit close.
	event.waitUntil(
		clients.matchAll({type: 'window'}).then( windowClients => {
			// Check if there is already a window/tab open with the target URL
			for (var i = 0; i < windowClients.length; i++) {
				var client = windowClients[i];
				// If so, just focus it.
				if (client.url === url && 'focus' in client) {
					return client.focus();
				}
			}
			// If not, then open the target URL in a new window/tab.
			if (clients.openWindow) {
				return clients.openWindow(url);
			}
		})
	);
});


