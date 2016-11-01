/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
var app = {
    // Application Constructor
    initialize: function() {
        this.bindEvents();
    },
    // Bind Event Listeners
    //
    // Bind any events that are required on startup. Common events are:
    // 'load', 'deviceready', 'offline', and 'online'.
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    // deviceready Event Handler
    //
    // The scope of 'this' is the event. In order to call the 'receivedEvent'
    // function, we must explicitly call 'app.receivedEvent(...);'
    onDeviceReady: function() {
        app.receivedEvent('deviceready');
		registerGeolocator();
    },
    // Update DOM on a Received Event
    receivedEvent: function(id) {
		
    }
};

function registerGeolocator() {
	var onSuccess = function(position) {
        $('#data-latitude').val(position.coords.latitude);
        $('#data-longitude').val(position.coords.longitude);
        // alert('Latitude: '          + position.coords.latitude          + '\n' +
              // 'Longitude: '         + position.coords.longitude         + '\n' +
              // 'Altitude: '          + position.coords.altitude          + '\n' +
              // 'Accuracy: '          + position.coords.accuracy          + '\n' +
              // 'Altitude Accuracy: ' + position.coords.altitudeAccuracy  + '\n' +
              // 'Heading: '           + position.coords.heading           + '\n' +
              // 'Speed: '             + position.coords.speed             + '\n' +
              // 'Timestamp: '         + position.timestamp                + '\n');
    };

    // onError Callback receives a PositionError object
    function onError(error) {
        // alert('code: '    + error.code    + '\n' +
              // 'message: ' + error.message + '\n');
    }
    var options = { maximumAge: 3000, timeout: 5000, enableHighAccuracy: true };
    navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
}

function registerFileTransfer() {
	function win(r) {
		console.log("Code = " + r.responseCode);
		console.log("Response = " + r.response);
		console.log("Sent = " + r.bytesSent);
	}

	function fail(error) {
		alert("An error has occurred: Code = " + error.code);
		console.log("upload error source " + error.source);
		console.log("upload error target " + error.target);
	}

	var uri = encodeURI("http://lbchatapp.hol.es");

	var options = new FileUploadOptions();
	options.fileKey="file";
	options.fileName=fileURL.substr(fileURL.lastIndexOf('/')+1);
	options.mimeType="text/plain";

	var headers={'headerParam':'headerValue'};

	options.headers = headers;

	var ft = new FileTransfer();
	ft.onprogress = function(progressEvent) {
		if (progressEvent.lengthComputable) {
			// loadingStatus.setPercentage(progressEvent.loaded / progressEvent.total);
		} else {
			// loadingStatus.increment();
		}
	};
	
	$(document).on('click', '#upload-file', function() {
		ft.upload(fileURL, uri, win, fail, options);
	});
	
}

app.initialize();