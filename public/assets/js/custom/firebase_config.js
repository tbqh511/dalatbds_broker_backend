
(function () {
    function getElValue(id) {
        var el = document.getElementById(id);
        return el ? el.value : '';
    }

    var firebaseConfig = {
        apiKey: getElValue('apiKey') || '',
        authDomain: getElValue('authDomain') || '',
        projectId: getElValue('projectId') || '',
        storageBucket: getElValue('storageBucket') || '',
        messagingSenderId: getElValue('messagingSenderId') || '',
        appId: getElValue('appId') || '',
        measurementId: getElValue('measurementId') || ''
    };

    // If no apiKey (or other required values), skip initialization to avoid ReferenceError
    if (!firebaseConfig.apiKey) {
        console.warn('Firebase config not found in DOM. Skipping Firebase init.');
        return;
    }

    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }

    var messaging = null;
    try {
        messaging = firebase.messaging();
    } catch (e) {
        console.warn('Firebase messaging unavailable', e);
    }

    if (!messaging) {
        return;
    }

    messaging.requestPermission()
        .then(function () {
            console.log('Notification permission granted.');
            getRegToken();
        })
        .catch(function (err) {
            console.log('Unable to get permission to notify.', err);
            try {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Allow Notification Permission!',
                        icon: 'error',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                }
            } catch (e) {
                // ignore
            }
        });

    function getRegToken() {
        messaging.getToken()
            .then(function (currentToken) {
                if (currentToken) {
                    saveToken(currentToken);
                }
            })
            .catch(function (err) {
                console.log('An error occurred while retrieving token. ', err);
            });
    }

    function saveToken(currentToken) {
        console.log('FCM token:', currentToken);
        if (typeof jQuery !== 'undefined') {
            jQuery.ajax({
                url: 'updateFCMID',
                method: 'GET',
                data: {
                    token: currentToken,
                    id: 1
                }
            }).done(function () {});
        } else if (window.fetch) {
            var params = new URLSearchParams({ token: currentToken, id: 1 });
            fetch('updateFCMID?' + params.toString(), { method: 'GET', credentials: 'same-origin' }).then(function () {});
        }
    }

    messaging.onMessage(function (payload) {
        try {
            var notificationTitle = payload && payload.data ? payload.data.title : 'Notification';
            var notificationOptions = {
                body: payload && payload.data ? payload.data.body : '',
                icon: payload && payload.data ? payload.data.icon : '',
                data: { time: new Date().toString() }
            };
            if (window.Notification) {
                new Notification(notificationTitle, notificationOptions);
            }
        } catch (e) {
            console.warn('Error showing notification', e);
        }
    });
})();




