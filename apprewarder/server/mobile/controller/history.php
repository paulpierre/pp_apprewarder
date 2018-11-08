<?php
$APP_NAME = constant('APP_NAME');
$USER_CURRENCY = (!isset($_SESSION['userCredits']))?0:$_SESSION['userCredits'];
$APP_BASE_URL = constant('API_HOST');
$IMG_RES = constant('IMG_RES');
$CSS_RES = constant('CSS_RES');
$JS_RES = constant('JS_RES');


if(!$userInstance->user_is_logged_in())
{
    die();
}

$historyData = $userInstance->get_user_history($_SESSION['userID']);
include(VIEW_PATH . 'historyView.php');
die();


?>