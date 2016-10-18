<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-2 text-center">
	<button id="back-button" class="btn btn-default">
		<span class="glyphicon glyphicon-triangle-left"></span>
	</button>
</div>
<div class="col-xs-6 btn-group text-center">
	<input class="form-control" id="input-search" type="search" placeholder="Search people/rooms..." />
	<button class="btn btn-default" id="search-button">
		<span class="glyphicon glyphicon-search"></span>
	</button>
</div>
<div class="col-xs-2 text-center">
	<button id="notification-button" class="btn btn-primary">
		<span id="notification-bubble" class="badge badge-bubble"></span>
		<span class="glyphicon glyphicon-bell"></span>
	</button>
</div>
<div class="col-xs-2 text-center">
	<button id="settings-button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
		<span class="glyphicon glyphicon-triangle-bottom"></span>
	</button>
	<ul class="dropdown-menu dropdown-menu-right">
		<li><a id="home" href="#home">Home</a></li>
		<li><a id="settings" href="#settings">Settings</a></li>
		<li><a id="help" href="#help">Help</a></li>
		<li><a id="logout" href="#logout">Log Out</a></li>
	</ul>
</div>