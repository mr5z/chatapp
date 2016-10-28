(function() {

// var DOMAIN = '';
// var DOMAIN = 'http://lbchatapp.esy.es/';
// var DOMAIN = 'http://lbchatapp.hol.es/';
var DOMAIN = 'http://locationbasedapp.esy.es/';
var MESSAGE_RECEIVING_INTERVAL = 500;
var NOTIFICATION_RECEIVING_INTERVAL = 2000;
const DEBUG = true;
const DESIGNING = true;
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
    var size = $(document).width() / 4 + 'px';
    $(loadingScreen).css({
        width: "100%",
        height: "100%",
        margin: "auto",
        backgroundImage: "url('img/loading-screen.gif')",
        backgroundRepeat: "no-repeat",
        backgroundPosition: "center center",
        backgroundSize: size + ' ' + size
    });
    $('#content *').replaceWith(loadingScreen);
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

$(document).on('click', '#back-button', function() {
    // TODO: pop history queue and load it to content
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
        data: {
            email: email,
            password: password,
            firstName: firstName,
            lastName: lastName,
            age: age
        },
        type: 'post',
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

function deliverMessage(message) {
    loadAsync({
        url: 'api/message-create.php',
        data: {
            senderId: getUserId(),
            recipientId: recipientId,
            recipientType: recipientType,
            body: message
        },
        type: 'post',
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
    messageRow.className += "row message-row";
    messageWrapper.className += "col-xs-10 ";
    dateSent.className += "extra-detail";
    messageBody.className += source;

    var shouldScroll = (content.scrollTop + content.offsetHeight >= content.scrollHeight);

    switch(source) {
    case userTypes.user:
        messageWrapper.className += "pull-right text-right";
        break;
    case userTypes.other:
        messageWrapper.className += "pull-left text-left";
        break;
    }
    // var formattedDate = moment(message.dateSent).format('h:mm:ss A MM/DD/YY');
    var formattedDate = moment(message.dateSent).fromNow();
    var timeStamp = moment(message.dateSent).format('YYYY-MM-DD hh:mm:ss');
    $(messageBody).append(document.createTextNode(message.body));
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
    popNewDialog(userTypes.user, { body: message, dateSent: new Date().toISOString() });

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
    
    console.log('userId: %d, recipientId: %d, recipientType: %s',
            getUserId(),
            getRecipientId(),
            recipientType);
    
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
    loadAsync({
        url: 'api/update-user-status.php',
        data: { userId: getUserId() },
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
/// Room functions
///
var options = [];

$(document).on('click', '.dropdown-menu a', function(event) {
    var $target = $(event.currentTarget),
       val = $target.attr('data-value'),
       $inp = $target.find('input'),
       idx;

    if (( idx = options.indexOf( val )) > -1 ) {
        options.splice( idx, 1 );
        setTimeout(function() { $inp.prop( 'checked', false) }, 0);
    }
    else {
        options.push( val );
        setTimeout(function() { $inp.prop( 'checked', true) }, 0);
    }

    $(event.target).blur();
      
    console.log(options);
    return false;
});

$(document).on('click', '#create-room', function() {
    var name = $('input[name=room-name]').val();
    var password = $('input[name=room-password]').val();
    var accessibility = $('input[name=room-accessibility]').val();
    var description = $('input[name=room-description]').val();
    loadAsync({
        url: 'api/create-room.php',
        type: 'post',
        data: {
            name: name,
            password: password,
            accessibility: accessibility,
            description: description
        },
        success: function(result) {
            if (result.status == 'success') {
                insertRoomMembers();
            }
            else {
                
            }
        },
        error: function() {
            
        }
    });
});

function insertRoomMembers(membersId) {
    
}

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

function loadContent(address, data) {
    loadAsync({
        url: address,
        data: data,
        type: 'post',
        beforeSend: function() {
            showLoadingScreen();
        },
        success: function(result) {
            $('#content').html(result);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log("ajaxOptions: " + ajaxOptions + 
                        ", thrownError: " + thrownError);
            console.log(xhr);
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