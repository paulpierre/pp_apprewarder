<?php
/**
 *  Setup the app constants
 */

$APP_NAME = constant('APP_NAME');
$USER_CURRENCY = (!isset($_SESSION['userCredits']))?0:$_SESSION['userCredits'];
$APP_BASE_URL = constant('API_HOST');
$IMG_RES = constant('IMG_RES');
$CSS_RES = constant('CSS_RES');
$JS_RES = constant('JS_RES');
$currentTime = intval(time());
global $CLIENT_VERSIONS_SUPPORTED;
$userInstanceMain = new User();
$userIsLoggedIn = $userInstance->user_is_logged_in();
unset($userInstanceMain);
$client_version = $_SESSION['userClientVersion'];
if($userIsLoggedIn && isset($client_version) && !in_array( $client_version,$CLIENT_VERSIONS_SUPPORTED)){

    $JS_QUIT = ($userInstance->get_user_platform() == PLATFORM_ANDROID)?'Android.exit();':'document.addEventListener("WebViewJavascriptBridgeReady", function(event) {window.bridge = event.bridge;window.bridge.init(function(message, responseCallback) { });window.bridge.send(JSON.stringify({"f":"exit"}), function responseCallback(responseData) {});}, false);';
    $UPDATE_URL = (($userInstance->get_user_platform() == PLATFORM_ANDROID)? GOOGLEPLAY_DOWNLOAD_URL:'http://' . API_HOST .'/download');
    $JS_UPDATE = 'setTimeout(function(){window.location="' . $UPDATE_URL .'";},15000);';

    $errorMsg = 'Yikes! It looks like you have an older version of AppRewarder '.$client_version.'. In order to continue, you must update your client by downloading the latest version. Don\'t worry, all your progress and coins will be saved!<br/><br/><a  href="' . $UPDATE_URL .'" class="btn" style="font-weight:bold;">Update to latest version<span class="icon-ok"></span></a>';
   $JS_INJECT = $JS_UPDATE .' setTimeout(function(){' . $JS_QUIT . '},30000);';


    $MESSAGE_HEADER = '<h1 class="error">' . $errorType .'</h1>';
    $MESSAGE_BODY = $errorMsg;
    include(VIEW_PATH . 'messageView.php');
    exit();
} else {
    include(VIEW_PATH . 'wrapperView.php');
}




?>