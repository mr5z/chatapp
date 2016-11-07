<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-8 col-md-8">
    <input class="form-control" id="input-message" type="text" placeholder="Type message here" />
</div>
<div class="col-xs-3 col-md-3">
    <a href="#" class="btn btn-success form-control" id="send-message">
        <span class="glyphicon glyphicon-send"></span>
    </a>
</div>
<div class="col-xs-1 col-md-1">
    <a href="#" class="dropup" data-toggle="dropdown">
        <span class="glyphicon glyphicon-option-vertical"></span>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
            <li><a class="upload-file" href="#">Upload file</a></li>
        </ul>
    </a>
</div>