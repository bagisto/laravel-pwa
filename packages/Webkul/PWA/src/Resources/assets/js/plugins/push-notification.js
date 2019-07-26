import '@firebase/auth';
import '@firebase/messaging';
import firebase from 'firebase/app';

Notification.requestPermission().then((permission) => {
    if (permission === 'granted') {
        console.log('Notification permission granted.');
        // TODO(developer): Retrieve an Instance ID token for use with FCM.
        retriveCurrentToken();
      } else {
        console.log('Unable to get permission to notify.');
    }
});

function retriveCurrentToken()
{

    firebase.initializeApp({messagingSenderId: '444825614301'});

    const messaging = firebase.messaging();

    messaging.requestPermission()
        .then(() => {
            return messaging.getToken();
        });
    //     .then(token => {

    //         // Write your logic to send token to your server
    //         if (token) {
    //             sendTokenToServer(token);
    //             subscriberToTopic(token,'laravel');

    //         } else {
    //             console.log('No Instance ID token available. Request permission to generate one.');
    //             setTokenSentToServer(false);
    //         }
    //     })
    //     .catch(error => {
    //         if (error.code === "messaging/permission-blocked") {
    //             console.log("Please Unblock Notification Request Manually");
    //         } else {
    //             console.log("Error Occurred", error);
    //         }

    //         setTokenSentToServer(false);

    //     });

    // messaging.onTokenRefresh(function () {
    //     messaging.getToken()
    //         .then(function (refreshedToken) {
    //             console.log('Token refreshed.');
    //             // Indicate that the new Instance ID token has not yet been sent to the
    //             // app server.
    //             sendTokenToServer(refreshedToken);
    //             // Send Instance ID token to app server.
    //         })
    //         .catch(function (err) {
    //             console.log('Unable to retrieve refreshed token ', err);
    //             setTokenSentToServer(false);
    //         });
    // });

    // messaging.onMessage((payload) => {
    //     console.log('Message Re. ', payload);
    //     // ...
    // });
}