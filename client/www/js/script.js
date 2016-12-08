(function() {

// var DOMAIN = '';
// var DOMAIN = 'http://lbchatapp.esy.es/';
// var DOMAIN = 'http://lbchatapp.hol.es/';
// var DOMAIN = 'http://locationbasedapp.esy.es/';
var DOMAIN = 'http://lbchatapp3.esy.es/';
var MESSAGE_RECEIVING_INTERVAL = 2000;
var NOTIFICATION_RECEIVING_INTERVAL = 5000;
const DEBUG = true;
const DESIGNING = false;
var isChatEngineStopped = !DESIGNING;
var isNotificationEngineStopped = !DESIGNING;
var recipientType = '';
var recipientId = -1;
var userTypes = {
    user: 'user',
    other: 'other',
    room: 'room'
};

function showHomePage(user) {
    loadContent('home.php', { userId: user.id });
    loadHeader('pages/header.php');
}

function showLoadingScreen() {
    var loadingScreen = document.createElement('div');
    var size = Math.min(200, $(document).width()) / 4 + 'px';
    $(loadingScreen).css({
        width: "100%",
        height: "100%",
        margin: "auto",
        backgroundImage: "url('img/loading-screen.gif')",
        backgroundRepeat: "no-repeat",
        backgroundPosition: "center center",
        backgroundSize: size + ' ' + size
    });
    $('#content').html(loadingScreen);
}

function configCredentials(user) {
    setLoggedIn(true);
    setUserId(user.id);
}

function loadEngines() {
    if (isNotificationEngineStopped) {
        isNotificationEngineStopped = false;
        startNotificationEngine();
    }
}

///
/// Navigation functions
///

var historyPages = [];

$(document).on('click', '#back-button', function() {
    if (historyPages.length > 0) {
        var previous = historyPages.pop();
        previous.data.fromHistory = true;
        loadContent(previous.page, previous.data);
    }
});

$(document).on('click', '#home', function() {
    stopChatEngine();
    loadContent('home.php', { userId: getUserId() });
});

///
/// Sign Up
///

$(document).on('click', '#show-signup-page', function(e) {
    e.preventDefault();
    loadContent('signup.php');
});

$(document).on('click', '#cancel-signup', function() {
    loadContent('login.php');
});

$(document).on('click', '#signup', function(e) {
    e.preventDefault();
    var email = $('input[name=email]').val();
    var password = $('input[name=password]').val();
    var firstName = $('input[name=firstName]').val();
    var lastName = $('input[name=lastName]').val();
    var age = $('input[name=age]').val();
    loadAsync({
        url: 'api/signup.php',
        type: 'post',
        data: {
            email: email,
            password: password,
            firstName: firstName,
            lastName: lastName,
            age: age
        },
        success: function(result) {
            if (result.status == 'success') {
                onSignUpSuccess(result.message);
            }
            else {
                onSignUpError();
            }
        },
        error: function() {
            onSignUpError();
        }
    });
});

function onSignUpSuccess(user) {
    configCredentials(user);
    showHomePage(user);
    loadEngines();
}

function onSignUpError() {
	
}
	
///
/// Log In functions
///

// late binding of click listener to submit button
$(document).on('click', '#login', function(e) {
    e.preventDefault();
    var email = $('#email').val();
    var password = $('#password').val();
    loadAsync({
        url: 'api/login.php',
        type: 'post',
        data: { email: email, password: password },
        beforeSend: function() {
            showLoadingScreen();
        },
        success: function(result) {
            if (result.status == 'success') {
                onLogInSuccess(result.message);
            }
            else {
                onLogInError(result.message);
            }
        },
        error: function(a, b, c) {
            
        }
    });
});

function onLogInSuccess(user) {
    if (user.active == '0') {
        configCredentials(user);
        showHomePage(user);
        loadEngines();
    }
    else {
        console.log('your account is online somewhere else');
        alert('your account is online somewhere else');
    }
}

function onLogInError(message) {
	loadContent('login.php', { message: message });
}
	
///
/// Log Out functions
///

$(document).on('click', '#logout', function(e) {
    abortAllRequests();
    stopChatEngine();
    stopNotificationEngine();
    setLoggedIn(false);
    setUserId(null);
    loadContent('login.php');
    loadHeader('pages/header-blank.php');
    loadFooter('pages/footer-blank.php');
    historyPages = [];
});

///
/// Search functions
///

$(document).on('click', '#search-button', function() {
    var search = $('#input-search').val();
    loadContent('pages/search-result.php', { userId: getUserId(), search: search });
    loadFooter('pages/footer-blank.php');
    stopChatEngine();
});

$(document).on('focus', '#input-search', function() {
    $('#back-button').parent().hide(400);
    $(this).parent().addClass('col-xs-8').removeClass('col-xs-6');
});

$(document).on('blur', '#input-search', function() {
    $('#back-button').parent().show();
    $(this).parent().addClass('col-xs-6').removeClass('col-xs-8');
});

///
/// Chat functions
///

function deliverMessage(message, type) {
    loadAsync({
        url: 'api/message-create.php',
        type: 'post',
        data: {
            senderId: getUserId(),
            recipientId: recipientId,
            recipientType: recipientType,
            type: type || 'text',
            body: message
        },
        success: function(result) {
            if (result.status == 'success') {
                
            }
            else {
                
            }
        }
    });
}

function popNewDialog(source, message) {
    var content = document.getElementById('content');
    var messageRow = document.createElement('div');
    var messageWrapper = document.createElement('div');
    var messageBody = document.createElement('div');
    var dateSent = document.createElement('div');
    messageRow.className += 'row message-row';
    messageWrapper.className += 'col-xs-10 ';
    dateSent.className += 'extra-detail';
    messageBody.className += source;

    var shouldScroll = (content.scrollTop + content.offsetHeight >= content.scrollHeight);

    switch(source) {
    case userTypes.user:
        messageWrapper.className += 'pull-right text-right';
        break;
    case userTypes.other:
        messageWrapper.className += 'pull-left text-left';
        break;
    }
    
    var formattedDate = moment(message.dateSent).fromNow();
    var timeStamp = moment(message.dateSent).format('YYYY-MM-DD hh:mm:ss');
    switch(message.type) {
    case 'text':
        $(messageBody).append(document.createTextNode(message.body));
        break;
    case 'link':
        var link = document.createElement('a');
        $(link).attr('href', message.body);
        $(link).text('Download attacment');
        $(link).addClass('download-link');
        $(messageBody).append(link);
        break;
    }
    $(dateSent).append(document.createTextNode(formattedDate));
    $(messageWrapper).append(messageBody);
    $(messageWrapper).append(dateSent);
    $(messageRow).append(messageWrapper);
    $(messageRow).attr('data-timestamp', timeStamp);
    $('#message-container').append(messageRow);

    if (shouldScroll) {
        content.scrollTop = content.scrollHeight;
    }
}

$(document).on('click', '#send-message', function() {
    var message = $('#input-message').val();
    deliverMessage(message);
    popNewDialog(userTypes.user, {
        body: message,
        type: 'text',
        dateSent: new Date().toISOString()
    });

    if ($('#chat-greetings').length > 0) {
        $('#chat-greetings').remove();
    }

    $('#input-message').val('');
    $('#input-message').focus();
});

$(document).on('click', '.chat', function() {
    recipientId = parseInt($(this).attr('data-recipient-id'));
    recipientType = $(this).attr('data-recipient-type');
    loadContent('chat.php', { recipientId: recipientId, recipientType: recipientType });
    loadFooter('pages/footer-chat.php');
    if (isChatEngineStopped) {
        isChatEngineStopped = false;
        startChatEngine();
    }
});

function startChatEngine() {
    
    if (isChatEngineStopped) {
        return;
    }
    
    loadAsync({
        url: 'api/message-broadcaster.php',
        type: 'post',
        data: {
            userId: getUserId(),
            recipientId: getRecipientId(),
            recipientType: recipientType
        },
        beforeSend: function() {
        },
        success: function(result) {
            if (result.status == 'success') {
                onMessagesReceived(result.message);
            }
            else {
                setTimeout(startChatEngine, MESSAGE_RECEIVING_INTERVAL);
            }
        }
    });
    
    function onMessagesReceived(messages) {
        busy = true;
        var messagesId = [];
        for(var i = 0;i < messages.length; ++i) {
            var msg = messages[i];
            // TODO: fix me!
            if (getRecipientId() == msg.recipientId) {
                messagesId.push(msg.id);
                popNewDialog(userTypes.other, msg);
            }
        }
        loadAsync({
            url: 'api/message-updater.php',
            data: {
                messagesId: messagesId.join(','),
                userId: getUserId(),
                recipientId: getRecipientId(),
                recipientType: recipientType
            },
            type: 'post',
            success: function(result) {
                setTimeout(startChatEngine, MESSAGE_RECEIVING_INTERVAL);
                switch(result.status) {
                    case 'success':
                        break;
                    case 'error':
                        console.log('error updating message: %s', result.message);
                        break;
                }
            }
        });
    }
}

function stopChatEngine() {
    isChatEngineStopped = true;
}

///
/// Notification functions
///

$(document).on('click', '#notification-button', function() {
    stopChatEngine();
    loadContent('pages/user-notifications.php', { userId: getUserId() });
});

function startNotificationEngine() {
    
    if (isNotificationEngineStopped) {
        return;
    }
    
    loadAsync({
        url: 'api/notification-broadcaster.php',
        data: { recipientId: getUserId(), recipientType: 'user' },
        type: 'post',
        success: function(result) {
            setTimeout(startNotificationEngine, NOTIFICATION_RECEIVING_INTERVAL);
            updateUserStatus();
            if (result.status == 'success') {
                onNotificationReceived(result.message);
            }
            else {
                onEmptyNotifications();
            }
        },
        error: function() {
            setTimeout(startNotificationEngine, NOTIFICATION_RECEIVING_INTERVAL);
        }
    });
	
}

function onNotificationReceived(notifications) {
    $('#notification-bubble').show();
    $('#notification-bubble').text(notifications.length);
}

function onEmptyNotifications() {
    $('#notification-bubble').hide();
    $('#notification-bubble').text('');
}

function stopNotificationEngine() {
    isNotificationEngineStopped = true;
}

function updateUserStatus() {
    var position = $('#data-position').val();
    if (position == '') {
        position = '12.4123124,121.234234';
    }
    position = position.split(',');
    loadAsync({
        url: 'api/update-user-status.php',
        data: {
            userId: getUserId(),
            latitude: position[0],
            longitude: position[1]
        },
        type: 'post',
        success: function(result) {
            // since it's just an update of user's status
            // we will ignore the result and continue on with our life
            if (result.status == 'success') {
                // console.log('updating user status success');
            }
            else {
                // console.log('updating user status failed: %s', result.message);
            }
        }
    });
}

///
/// Contact functions
///

$(document).on('click', '.add-contacts', function() {
    var rootDiv = $(this).parent().parent();
    loadAsync({
        url: 'api/add-contacts.php',
        data: {
            contactOwner: getUserId(), 
            contactId: $(rootDiv).attr('data-contact-id'),
            type: $(rootDiv).attr('data-contact-type')
        },
        type: 'post',
        success: function(result) {
            if (result.status == 'success') {
                console.log('new friends added');
            }
            else {
                
            }
        }
    });
});

///
/// View profile functions
///

$(document).on('click', '.view-profile', function() {
    loadContent('pages/view-profile.php', {
        userId: $(this).attr('data-user-id')
    });
});

///
/// Edit profile functions
///

var removeContactList = [];

$(document).on('click', '#edit-profile', function() {
    loadContent('pages/home-profile-edit.php', {
        userId: getUserId()
    });
});

$(document).on('click', '#cancel-edit-profile', function() {
    $('#home').click();
});

$(document).on('click', '#save-edit-profile', function() {
    var firstName = $('input[name=edit-first-name]').val();
    var lastName = $('input[name=edit-last-name]').val();
    var city = $('input[name=edit-city]').val();
    loadAsync({
        url: 'api/update-user-info.php',
        type: 'post',
        data: {
            userId: getUserId(),
            firstName: firstName,
            lastName: lastName,
            city: city,
            removeContactList: removeContactList.join(',')
        },
        success: function(result) {
            if (result.status == 'success') {
                $('#home').click();
            }
            else {
                
            }
        },
        error: function() {
            
        }
    });
});

$(document).on('click', '.remove-contact', function() {
    var contactId = $(this).attr('data-user-id');
    removeContactList.push(contactId);
    $(this).parent().remove();
});

///
/// Room functions
///
var roomMembers = [];

$(document).on('click', '#add-members a', function(event) {
    var $target = $(event.currentTarget),
       value = $target.attr('data-contact-id'),
       $input = $target.find('input'),
       idx;

    if ((idx = roomMembers.indexOf(value)) > -1) {
        roomMembers.splice(idx, 1);
        setTimeout(function() { $input.prop('checked', false) }, 0);
    }
    else {
        roomMembers.push(value);
        setTimeout(function() { $input.prop('checked', true) }, 0);
    }

    $(event.target).blur();
      
    console.log(roomMembers);
    return false;
});

$(document).on('click', '#create-room', function() {
    var name = $('input[name=room-name]').val();
    var password = $('input[name=room-password]').val();
    var accessibility = $('input[name=room-accessibility]:checked').val();
    var description = $('textarea[name=room-description]').val();
    roomMembers.push(getUserId());
    loadAsync({
        url: 'api/create-room.php',
        type: 'post',
        data: {
            name: name,
            password: password,
            accessibility: accessibility,
            description: description,
            roomMembers: roomMembers.join(','),
            ownerId: getUserId()
        },
        success: function(result) {
            if (result.status == 'success') {
                onSuccess();
            }
            else {
                onError(result.message);
            }
        },
        error: function(a, b, c) {
            onError(c);
        }
    });
    
    function onSuccess() {
        reloadMenu('rooms');
    }
    
    function onError(message) {
        
    }
});

function reloadMenu(menuItem) {
    loadAsync({
        url: 'pages/home-' + menuItem + '.php',
        type: 'post',
        data: { userId: getUserId() },
        success: function(result) {
            $('#menu-' + menuItem).html(result);
        },
        error: function() {
            
        }
    });
}

///
/// File download
///

$(document).on('click', '.download-link', function(e) {
    e.preventDefault();
    var link = $(this).attr('href');
    var filename = link.split('/').pop().split('#')[0].split('?')[0];
    console.log('Downloading: ' + filename);
    
    window.requestFileSystem(LocalFileSystem.PERSISTENT, 5 * 1024 * 1024, function(fs) {
        fs.root.getFile(filename, {
            create: true,
            exclusive: false
        }, onSuccessCreateFile, onErrorCreateFile);
        
        function onSuccessCreateFile(fileEntry) {
            var fileTransfer = new FileTransfer();
            fileTransfer.download(link, fileEntry.toURL(),
                function(entry) {
                    cordova.InAppBrowser.open(entry.toURL(), '_blank', 'location=no');
                    console.log("download complete: " + entry.toURL());
                },
                function(error) {
                    console.log("download error source " + error.source);
                    console.log("download error target " + error.target);
                    console.log("download error code" + error.code);
                },
                false, {}
            );
        }
        
        function onErrorCreateFile(message) {
            console.log(message);
        }
    });
});

///
/// File upload
///

$(document).on('click', '.upload-file', function() {
    
    var platform = device.platform.toLowerCase();
    switch (platform) {
        'android':
        showAndroidFileBrowser();
        break;
        'ios':
        showIosFileBrowser();
        break;
    }
    
    function showAndroidFileBrowser() {
        fileChooser.open(function(fileUri) {
            onFileSelect(fileUri);
        }, function(message) {
            console.log('error: ' + message);
        });
    }
    
    function showIosFileBrowser() {
        FilePicker.pickFile(function(path) {
            var fileName = path.substr(path.lastIndexOf('/') + 1);
            var fileExtension = path.split('.').pop();
            uploadFile(fileName, path, fileExtension);
        }, function(message) {
            alert('Error opening file: ' + message);
        });
    }
    
    function uploadFile(fileName, filePath, fileType) {
        var serverUrl = DOMAIN + 'api/upload-file.php';
        var options = new FileUploadOptions();
        
        options.fileKey = "file";
        options.fileName = fileName;
        options.mimeType = fileType;
        options.chunkedMode = false;
        
        console.log('Uploading file: { name: ' + fileName + ', type: ' + fileType + ' } from: ' + filePath);
        
        var fileTransfer = new FileTransfer();
        fileTransfer.upload(filePath, serverUrl, 
        function(result) {
            if (result.responseCode == 200) {
                console.log('response: ' + result.response);
                var response = JSON.parse(result.response);
                if (response.status == 'success') {
                    onUploadSuccess(response);
                }
                else {
                    onUploadError('Failed to upload: ' + response.message);
                }
            }
            else {
                onUploadError('Failed to upload');
            }
        }
        , function(error) {
            onUploadError('An error has occurred: Code = ' + error.code);
            console.log("upload error source " + error.source);
            console.log("upload error target " + error.target);
        }, options);
        
        function onUploadSuccess(response) {
            var links = response.message;
            for(var i = 0;i < links.length; ++i) {
                var message = links[i];
                deliverMessage(message, 'link');
                popNewDialog(userTypes.user, {
                    body: message,
                    type: 'link',
                    dateSent: new Date().toISOString()
                });
            }
        }
        
        function onUploadError(message) {
            alert(message);
        }
    }
    
    function onFileSelect(fileUri) {
        window.resolveLocalFileSystemURL(fileUri, function(fileEntry) {
            fileEntry.file(function(file) {
               uploadFile(fileEntry.name, fileEntry.toURL(), file.type); 
            });
        });
    }
});

///
/// Map functions
///

var otherLocation;
var mapContainer, map, markers = [];
var directionsDisplay;

function initMap(width, height) {
    
    var userPos = $('#data-position').val();
    
    if (userPos == '') {
        console.log('no available location');
        return;
    }
    
    userPos = userPos.split(',');
    
    mapContainer = mapContainer || document.createElement('div');
    $('#content').html(mapContainer);
    
    var positionA = new google.maps.LatLng(userPos[0], userPos[1]);
    var positionB = new google.maps.LatLng(otherLocation[0], otherLocation[1]);
    var bounds = new google.maps.LatLngBounds();
    bounds.extend(positionA);
    bounds.extend(positionB);
    
    map = map || new google.maps.Map(mapContainer, {
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    map.setZoom(10);
    map.setCenter(bounds.getCenter());
    map.fitBounds(bounds);
    
    $(mapContainer).css({
        width: $('#content').width() + 'px',
        height: $('#content').height() + 'px'
    });
    
    var directionsService = new google.maps.DirectionsService();
    directionsDisplay = directionsDisplay || new google.maps.DirectionsRenderer();
    directionsDisplay.setMap(null);
    directionsDisplay.setMap(map);
    directionsDisplay.setOptions( { suppressMarkers: true } );
    calculateAndDisplayRoute(directionsService, directionsDisplay, positionA, positionB);
    
    clearOverlays();
    markers = [
        new google.maps.Marker({
            map: map,
            position: positionA,
            label: 'You',
            icon: genMarker('red')
        }),
        new google.maps.Marker({
            map: map,
            position: positionB,
            label: 'Other',
            icon: genMarker('green')
        })
    ];
    
    
    function genMarker(markerColor) {
        return {
            path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z',
            fillColor: markerColor,
            fillOpacity: 1,
            strokeColor: '#000',
            strokeWeight: 1,
            scale: 1,
            labelOrigin: new google.maps.Point(0, 0)
        };
    }
}

$(document).on('click', '.view-map', function(e) {
    var pos = $(this).attr('data-position');
    if (pos == '') {
        console.log("other's location cannot determine");
        return;
    }
    otherLocation = pos.split(',');
    showMap();
});

function showMap() {
    if (typeof window.google != 'undefined') {
        initMap();
    }
    else {
        $.getScript('https://maps.googleapis.com/maps/api/js?callback=initMap');
    }
}

function clearOverlays() {
    for (var i = 0; i < markers.length; i++ ) {
        markers[i].setMap(null);
    }
    markers = [];
}

function calculateAndDisplayRoute(directionsService, directionsDisplay, origin, destination) {
    directionsService.route({
        origin: origin,
        destination: destination,
        travelMode: 'DRIVING'
    },
    function(response, status) {
        if (status === 'OK') {
            directionsDisplay.setDirections(response);
        } else {
            window.alert('Directions request failed due to ' + status);
        }
    });
}

window.initMap = initMap;

///
/// Utility functions
///

var requestIdGenerator = -1;
var ajaxRequests = [];

function loadAsync(request) {
    // if (request.url !== 'undefined' && !request.url.startsWith('api')) {
        // // TODO: push request.url to history queue
        // console.log('requesting page: %s', request.url);
    // }
    request.url = DOMAIN + request.url;
    request.queueId = ++requestIdGenerator;
    request.complete = function() {
        ajaxRequests.splice(this.queueId, 1);
    };
    ajaxRequests.push($.ajax(request));
}

function abortAllRequests() {
    for(var i = 0;i < ajaxRequests.length; ++i) {
        ajaxRequests[i].abort();
    }
    ajaxRequests = [];
}

///
/// Startup functions
///

function loadContent(address, data, onSuccess, onError) {
    loadAsync({
        url: address,
        data: data,
        type: 'post',
        beforeSend: function() {
            showLoadingScreen();
        },
        success: function(result) {
            $('#content').html(result);
            if (data && !data.fromHistory) {
                historyPages.push({
                    page: address,
                    data: data
                });
            }
            if (onSuccess) onSuccess(result);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("ajaxOptions: " + ajaxOptions + 
                        ", thrownError: " + thrownError);
            console.log(xhr);
            if (onError) onError();
        }
    });
}

function loadHeader(address) {
    loadAsync({
        url: address,
        type: 'post',
        success: function(result) {
            $('#header').html(result);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("ajaxOptions: " + ajaxOptions + 
                        ", thrownError: " + thrownError);
            console.log(xhr);
        }
    });
}

function loadFooter(address) {
    loadAsync({
        url: address,
        type: 'post',
        success: function(result) {
            $('#footer').html(result);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("error: " + thrownError);
        }
    });
}

function isLoggedIn() {
    return localStorage.getItem('loggedIn') == "true";
}

function setLoggedIn(loggedIn) {
    localStorage.setItem('loggedIn', loggedIn);
}

function getUserId() {
    return parseInt(localStorage.getItem('userId'));
}

function setUserId(userId) {
    localStorage.setItem('userId', userId);
}

function getRoomId() {
    return localStorage.getItem('roomId');
}

function getRecipientId() {
    switch(recipientType) {
        case 'user':
            return getUserId();
        case 'room':
            return recipientId;
        default:
            return -1;
    }
}

function setRoomId(roomId) {
    localStorage.setItem('roomId', roomId);
}

function loadInitialContent() {
    if (isLoggedIn()) {
        showHomePage({ id: getUserId() });
        loadEngines();
    }
    else {
        loadContent('login.php');
        loadHeader('pages/header-blank.php');
    }
}

loadInitialContent();

})();