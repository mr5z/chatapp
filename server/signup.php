<?php
header("Access-Control-Allow-Origin: *");
require_once('api/api.php');
?>
<form class="col-xs-12">
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="email" placeholder="Email" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="password" name="password" placeholder="Password" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="password" name="password" placeholder="Re-type password" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="firstName" placeholder="First name" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="lastName" placeholder="Last name" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="text" maxlength="2" name="age" placeholder="Age" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-12">
            <input class="form-control" type="text" name="city" placeholder="City e.g. Batangas" />
        </div>
    </div>
    <div class="row top-7">
        <div class="col-xs-6">
            <input id="cancel-signup" class="form-control btn btn-link" type="button" value="Cancel" />
        </div>
        <div class="col-xs-6">
            <input id="signup" class="form-control btn btn-primary" type="submit" value="Sign Up" />
        </div>
    </div>
</form>