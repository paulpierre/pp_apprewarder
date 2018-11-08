<?php

/*
 *  Validate user's email
 *  http://m.apprewarder.com/c/userid/md5(userid+email)
 */


if(isset($controllerFunction) && $controllerFunction !== '')
{

    if($userInstance->confirm_user_hash($controllerFunction))
    {
        $MESSAGE_HEADER = '<h1 class="success">Huzzah, Success!</h1>';
        $MESSAGE_BODY = <<<EOT
        *BURP* Congrats lad/lass. Your email address has been successfully confirmed.
        <br>You are now able to turn those shiny coins into awesome rewards like Amazon Gift Cards.
        <br><br> Thank you for using AppRewarder, you rock!'
        <br>
        <br><span style="color:#999">-AppRewarder Team</span>
        <br><br>
        <a href="http://www.apprewarder.com" class="btn">OK, Let's get goin'!</a>
EOT;
    } else {

        $MESSAGE_HEADER = '<h1 class="error">Error 404</h1>';
        $MESSAGE_BODY = <<<EOT
        Looks to me you may have entered an incorrect or expired email confirmation url. Please try again.
EOT;

    }

}
else {
    $MESSAGE_HEADER = '<h1 class="error">Error 404</h1>';
    $MESSAGE_BODY = <<<EOT
       Looks like what you're looking for isn't there, homeslice.
EOT;
}

include(VIEW_PATH . 'messageView.php');
exit();




?>