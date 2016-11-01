<?php header("Access-Control-Allow-Origin: *"); ?>
<div class="col-xs-8 col-md-8">
    <input class="form-control" id="input-message" type="text" placeholder="Type message here" />
</div>
<div class="col-xs-4 col-md-4 btn-group">
    <button class="btn btn-success form-control" id="send-message">
        <span class="glyphicon glyphicon-send"></span>
    </button>
    <button class="btn dropup" data-toggle="dropdown">
        <span class="glyphicon glyphicon-option-vertical"></span>
        <ul class="dropdown-menu dropdown-menu-right">
            <li><a class="upload-file" href="#">Upload file</a></li>
        </ul>
    </button>
</div>