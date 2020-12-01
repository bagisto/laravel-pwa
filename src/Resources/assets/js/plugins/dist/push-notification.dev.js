"use strict";

require("@firebase/auth");

require("@firebase/messaging");

var _app = _interopRequireDefault(require("@firebase/app"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var topic;
var serverAPI;
var messagingId;
var topicKey = "pwa.settings.push-notification.topic";
var serverAPIKey = "pwa.settings.push-notification.api-key";
var messagingIdKey = "pwa.settings.push-notification.messaging-id";

var isSafari = function isSafari() {
  return window.navigator.vendor == "Apple Computer, Inc." ? true : false;
};

var isTokenSentToServer = function isTokenSentToServer() {
  return window.localStorage.getItem('sentToServer') === '1';
};

var setTokenSentToServer = function setTokenSentToServer(sent) {
  window.localStorage.setItem('sentToServer', sent ? '1' : '0');
};

if (!isSafari()) {
  var url = "".concat(window.config.app_base_url, "/api/config?_config=").concat(topicKey, ",").concat(serverAPIKey, ",").concat(messagingIdKey);
  fetch(url, {
    method: 'GET',
    headers: new Headers({
      'Accept': 'application/json',
      'Content-Type': 'application/json'
    })
  }).then(function (response) {
    return response.json();
  }).then(function (response) {
    topic = response.data[topicKey];
    serverAPI = response.data[serverAPIKey];
    messagingId = response.data[messagingIdKey];

    _app["default"].initializeApp({
      messagingSenderId: messagingId
    });

    var messaging = _app["default"].messaging();

    Notification.requestPermission().then(function (permission) {
      if (permission === 'granted') {
        console.log('Notification permission granted.'); // TODO(developer): Retrieve an Instance ID token for use with FCM.

        retriveCurrentToken(messaging);
      } else {
        console.log('Unable to get permission to notify.');
      }
    });
    messaging.onMessage(function (payload) {
      console.log('onMessage:', payload);
    });
  })["catch"](function (error) {
    console.log(error);
  });
}

function retriveCurrentToken(messaging) {
  // Get Instance ID token. Initially this makes a network call, once retrieved
  // subsequent calls to getToken will return from cache.
  messaging.getToken().then(function (currentToken) {
    if (currentToken) {
      sendTokenToServer(currentToken);
      subscribeToTopic(currentToken); // updateUIForPushEnabled(currentToken);
    } else {
      // Show permission request.
      console.log('No Instance ID token available. Request permission to generate one.'); // Show permission UI.
      // updateUIForPushPermissionRequired();

      setTokenSentToServer(false);
    }
  })["catch"](function (err) {
    console.log('An error occurred while retrieving token. ', err); // showToken('Error retrieving Instance ID token. ', err);

    setTokenSentToServer(false);
  });
  messaging.onTokenRefresh(function () {
    messaging.getToken().then(function (refreshedToken) {
      console.log('Token refreshed.'); // Indicate that the new Instance ID token has not yet been sent to the
      // app server.

      sendTokenToServer(refreshedToken); // Send Instance ID token to app server.
    })["catch"](function (err) {
      console.log('Unable to retrieve refreshed token ', err);
      setTokenSentToServer(false);
    });
  });
} // Send the Instance ID token your application server, so that it can:
// - send messages back to this app
// - subscribe/unsubscribe the token from topics


function sendTokenToServer(currentToken) {
  if (!isTokenSentToServer()) {
    console.log('Sending token to server...'); // TODO(developer): Send the current token to your server.

    setTokenSentToServer(true);
    console.log('Token ID sent to server');
  } else {
    console.log('Token already sent to server so won\'t send it again ' + 'unless it changes');
  }
}

function subscribeToTopic(currentToken) {
  var post = {
    topic: topic,
    currentToken: currentToken
  };
  var url = "https://iid.googleapis.com/iid/v1/".concat(currentToken, "/rel/topics/").concat(topic);
  fetch(url, {
    method: 'POST',
    headers: new Headers({
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Authorization': "key=".concat(serverAPI)
    }),
    body: JSON.stringify(post)
  }).then(function (response) {
    if (response.status < 200 || response.status >= 400) {
      throw 'Error subscribing to topic: ' + response.status + ' - ' + response.body;
    }

    console.log(response.status);
    console.log('Subscribed to "' + topic + '"');
  })["catch"](function (error) {
    console.log(error);
  });
}