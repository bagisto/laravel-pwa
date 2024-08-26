import { initializeApp } from "firebase/app";
import { getMessaging, onMessage, getToken } from "firebase/messaging";

// Initialize variables
var topic;
var serverAPI;
var messagingId;
var authDomain;
var databaseUrl;
var projectId;
var appId;
var webAPIKey;

var topicKey = "pwa.settings.push-notification.topic";
var serverAPIKey = "pwa.settings.push-notification.api-key";
var messagingIdKey = "pwa.settings.push-notification.messaging-id";
var authDomainKey = "pwa.settings.push-notification.auth-domain";
var databaseUrlKey = "pwa.settings.push-notification.database-url";
var projectIdKey = "pwa.settings.push-notification.project-id";
var appIdKey = "pwa.settings.push-notification.app-id";
var webAPIKeyKey = "pwa.settings.push-notification.web-api-key";

var isSafari = () => {
    return window.navigator.vendor == "Apple Computer, Inc.";
};

var isTokenSentToServer = () => {
    return window.localStorage.getItem("sentToServer") === "1";
};

var setTokenSentToServer = (sent) => {
    window.localStorage.setItem("sentToServer", sent ? "1" : "0");
};

if (!isSafari()) {
    const configKeys = [
        topicKey,
        serverAPIKey,
        messagingIdKey,
        authDomainKey,
        databaseUrlKey,
        projectIdKey,
        appIdKey,
        webAPIKeyKey,
    ];

    // let url = `${window.config.app_base_url}api/v1/core-configs?_config=[${topicKey},${serverAPIKey},${messagingIdKey},${authDomainKey},${databaseUrlKey},${projectIdKey},${appIdKey},${webAPIKeyKey},]`;

    const params = new URLSearchParams();
    configKeys.forEach((key) => params.append("_config[]", key));

    let url = `${
        window.config.app_base_url
    }api/v1/core-configs?${params.toString()}`;

    fetch(url, {
        method: "GET",
        headers: new Headers({
            Accept: "application/json",
            "Content-Type": "application/json",
        }),
    })
        .then((response) => response.json())
        .then((response) => {
            serverAPI = response.data[serverAPIKey];
            authDomain = response.data[authDomainKey];
            projectId = response.data[projectIdKey];
            databaseUrl = response.data[databaseUrlKey];
            messagingId = response.data[messagingIdKey];
            appId = response.data[appIdKey];
            topic = response.data[topicKey];
            webAPIKey = response.data[webAPIKeyKey];

            const firebaseConfig = {
                apiKey: serverAPI,
                authDomain: authDomain,
                projectId: projectId,
                storageBucket: databaseUrl,
                messagingSenderId: messagingId,
                appId: appId,
            };

            // Initialize Firebase App
            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);

            Notification.requestPermission().then((permission) => {
                console.log("log in notificaiton", permission);

                if (permission === "granted") {
                    console.log("Notification permission granted.");
                    // Retrieve an Instance ID token for use with FCM
                    retriveCurrentToken(messaging);
                } else {
                    console.log("Unable to get permission to notify.");
                }
            });

            onMessage(messaging, (payload) => {
                console.log("onMessage:", payload);

                const notificationTitle = payload.notification.title;
                const notificationOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };

                var notification = new Notification(
                    notificationTitle,
                    notificationOptions,
                );

                notification.onclick = function (event) {
                    event.preventDefault(); // prevent the browser from focusing the Notification's tab
                    window.open(payload.notification.click_action, "_blank");
                    notification.close();
                };
            });
        })
        .catch((error) => {
            console.log(error);
        });
}

function retriveCurrentToken(messaging) {
    // Get Instance ID token. Initially this makes a network call, once retrieved
    // subsequent calls to getToken will return from cache.

    getToken(messaging, {
        vapidKey: serverAPI,
    })
        .then((currentToken) => {
            if (currentToken) {
                sendTokenToServer(currentToken);
                subscribeToTopic(currentToken);
            } else {
                console.log(
                    "No Instance ID token available. Request permission to generate one.",
                );
                setTokenSentToServer(false);
            }
        })
        .catch((err) => {
            console.log("An error occurred while retrieving token. ", err);
            setTokenSentToServer(false);
        });
}

// Send the Instance ID token to your application server
function sendTokenToServer(currentToken) {
    if (!isTokenSentToServer()) {
        console.log("Sending token to server...");
        setTokenSentToServer(true);
        console.log("Token ID sent to server");
    } else {
        console.log(
            "Token already sent to server so won't send it again unless it changes",
        );
    }
}

// Subscribe the token to a topic
function subscribeToTopic(currentToken) {
    let url = `https://iid.googleapis.com/iid/v1/${currentToken}/rel/topics/${topic}`;

    fetch(url, {
        method: "POST",
        headers: new Headers({
            "Content-Type": "application/json",
            Accept: "application/json",
            Authorization: `key=${serverAPI}`,
        }),
    })
        .then((response) => {
            if (response.status < 200 || response.status >= 400) {
                throw new Error(
                    "Error subscribing to topic: " +
                        response.status +
                        " - " +
                        response.statusText,
                );
            }
            console.log('Subscribed to "' + topic + '"');
        })
        .catch((error) => {
            console.log(error);
        });
}
