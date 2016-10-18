<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-8 col-md-8">
	<input class="form-control" id="input-message" type="text" placeholder="Type message here" />
</div>
<div class="col-xs-3 col-md-3">
	<button class="form-control btn btn-success" id="send-message">
		<span class="glyphicon glyphicon-send"></span>
	</button>
</div>
<div class="col-xs-1 col-md-1 dropup">
	<a href="#" data-toggle="dropdown">
		<span class="glyphicon glyphicon-option-vertical"></span>
	</a>
	<ul class="dropdown-menu dropdown-menu-right">
		<li><a class="upload-file" href="#">Upload file</a></li>
	</ul>
</div>