importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js");

firebase.initializeApp({
    messagingSenderId:
        "{{core()->getConfigData('pwa.settings.push-notification.messaging-id')}}",
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    // Customize notification here
    var notificationTitle = payload.data.title;
    var notificationOptions = {
        body: payload.data.body,
        icon: payload.data.icon,
        data: {
            click_action: payload.data.click_action,
        },
    };

    return self.registration.showNotification(
        notificationTitle,
        notificationOptions,
    );
});
// [END background_handler]

self.addEventListener("notificationclick", function (event) {
    var action_click = event.notification.data.click_action;
    event.notification.close();

    event.waitUntil(clients.openWindow(action_click));
});
