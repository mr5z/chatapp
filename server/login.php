<?php header("Access-Control-Allow-Origin: *"); ?>
<form class="col-xs-12">
    <?php
        require_once('api/api.php');
        
        $message = post('message');
        if (!empty($message)) {
    ?>
            <div class="alert alert-warning alert-dismissable fade in top-15">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">&times;</a>
                <p><?php echo $message; ?></p>
            </div>
    <?php
        }
    ?>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" id="email" type="text" placeholder="Email" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" id="password" type="password" placeholder="Password" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-8">
            <input class="form-control btn btn-link" id="show-signup-page" type="submit" value="Sign Up" />
        </div>
        <div class="col-xs-4">
            <input class="form-control btn-primary" id="login" type="submit" value="Log In" />
        </div>
    </div>
</form>