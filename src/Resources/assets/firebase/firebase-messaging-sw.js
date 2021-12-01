importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js');

var messagingId = "709349998192";

firebase.initializeApp({messagingSenderId: messagingId});
const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function(payload) {
	// Customize notification here
	var notificationTitle = payload.data.title;
	var notificationOptions = {
		body: payload.data.body,
		icon: payload.data.icon,
		data: {
			click_action: payload.data.click_action
		}
	};

	return self.registration.showNotification(notificationTitle, notificationOptions);
});

// [END background_handler]
self.addEventListener('notificationclick', function (event) {
	var action_click = event.notification.data.click_action;

	event.notification.close();

	event.waitUntil(
		clients.openWindow(action_click)
	);
});