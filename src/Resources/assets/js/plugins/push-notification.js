import firebase from 'firebase/compat/app';
import 'firebase/compat/auth';
import 'firebase/compat/messaging';
var topic;
var serverAPI;
var messagingId;
var api;
var authdomain;
var databaseurl;
var projectid;
var appid;

var topicKey = "pwa.settings.push-notification.topic";
var serverAPIKey = "pwa.settings.push-notification.api-key";
var APIKey = "pwa.settings.push-notification.web-api-key";
var messagingIdKey = "pwa.settings.push-notification.messaging-id";
var authDomain = "pwa.settings.push-notification.auth-domain";
var databaseURL = "pwa.settings.push-notification.database-url";
var projectId = "pwa.settings.push-notification.project-id";
var appId = "pwa.settings.push-notification.app-id";

var isSafari = () => {
    return window.navigator.vendor == "Apple Computer, Inc." ? true : false;
}

var isTokenSentToServer = () => {
    return window.localStorage.getItem('sentToServer') === '1';
}

var setTokenSentToServer = sent => {
    window.localStorage.setItem('sentToServer', sent ? '1' : '0');
}

if (! isSafari()) {
    let url = `${window.config.app_base_url}/api/config?_config=${topicKey},${APIKey},${serverAPIKey},${messagingIdKey},${authDomain},${databaseURL},${projectId},${appId}`;
    fetch(url, {
        method: 'GET',
        headers: new Headers({
            'Accept'        : 'application/json',
            'Content-Type'  : 'application/json',
        }),
    })
    .then(response => response.json())
    .then(response => {

console.log(response.data);
        topic = response.data[topicKey];
        api = response.data[APIKey];
        serverAPI = response.data[serverAPIKey];
        messagingId = response.data[messagingIdKey];
        authdomain = response.data[authDomain];
        databaseurl = response.data[databaseURL];
        projectid = response.data[projectId];
        appid = response.data[appId];

        firebase.initializeApp({ 
            apiKey: api,
            authDomain: authdomain,
            databaseURL: databaseurl,
            projectId: projectid,
            storageBucket: "",
            messagingSenderId: messagingId,
            appId: appid
    });
        const messaging = firebase.messaging();

        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');
                // TODO(developer): Retrieve an Instance ID token for use with FCM.
                retriveCurrentToken(messaging);
            } else {
                console.log('Unable to get permission to notify.');
            }
        });

        messaging.onMessage(function(payload){
            console.log('onMessage:', payload);

            const notificationTitle = payload.data.title;
            const notificationOptions = {
                body: payload.data.body,
                icon: payload.data.icon,        
            };

            var notification = new Notification(notificationTitle, notificationOptions);

            notification.onclick = function(event) {
                event.preventDefault(); // prevent the browser from focusing the Notification's tab
                window.open(payload.data.click_action , '_blank');
                notification.close();
            }
        });
    })
    .catch((error) => {
        console.log(error);
    });
}

function retriveCurrentToken(messaging) {
    // Get Instance ID token. Initially this makes a network call, once retrieved
    // subsequent calls to getToken will return from cache.
    messaging.getToken().then((currentToken) => {
        if (currentToken) {
            sendTokenToServer(currentToken);
            subscribeToTopic(currentToken);
            // updateUIForPushEnabled(currentToken);
        } else {
            // Show permission request.
            console.log('No Instance ID token available. Request permission to generate one.');
            // Show permission UI.
            // updateUIForPushPermissionRequired();
            setTokenSentToServer(false);
        }
    }).catch((err) => {
        console.log('An error occurred while retrieving token. ', err);
        // showToken('Error retrieving Instance ID token. ', err);
        setTokenSentToServer(false);
    });
//     messaging.onTokenRefresh(function () {
//         messaging
//             .getToken()
//             .then(function (refreshedToken) {
//                 console.log('Token refreshed.');
//                 // Indicate that the new Instance ID token has not yet been sent to the
//                 // app server.
//                 sendTokenToServer(refreshedToken);
//                 // Send Instance ID token to app server.
//             })
//             .catch(function (err) {
//                 console.log('Unable to retrieve refreshed token ', err);
//                 setTokenSentToServer(false);
//             });
//   });

        messaging
            .getToken()
            .then(function (refreshedToken) {
                console.log('Token refreshed.');
                sendTokenToServer(refreshedToken);
            })
            .catch(function (err) {
                console.log('Unable to retrieve refreshed token ', err);
                setTokenSentToServer(false);
            });
}
function sendTokenToServer(currentToken) {
    if (!  isTokenSentToServer()) {
        console.log('Sending token to server...');
        // TODO(developer): Send the current token to your server.
        setTokenSentToServer(true);
        console.log('Token ID sent to server');
    } else {
        console.log('Token already sent to server so won\'t send it again ' +
            'unless it changes');
    }
}

function subscribeToTopic(currentToken) {
    let post = {
        topic,
        currentToken: currentToken,
    }

    let url = `https://iid.googleapis.com/iid/v1/${currentToken}/rel/topics/${topic}`;

    fetch( url, {
        method: 'POST',
        headers: new Headers({
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization':`key=${serverAPI}`,
        }),
        body : JSON.stringify(post)
    })
    .then(response => {
        if (response.status < 200 || response.status >= 400) {
            throw 'Error subscribing to topic: '+response.status + ' - ' + response.body;
        }

        console.log(response.status);
        console.log('Subscribed to "'+topic+'"');
    })
    .catch((error) => {
        console.log(error);
    });
}
