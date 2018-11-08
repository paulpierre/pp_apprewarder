<?php
$errorType = $controllerFunction;
$JS_QUIT = ($userInstance->get_user_platform() == PLATFORM_ANDROID)?'Android.exit();':'document.addEventListener("WebViewJavascriptBridgeReady", function(event) {window.bridge = event.bridge;window.bridge.init(function(message, responseCallback) { });window.bridge.send(JSON.stringify({"f":"exit"}), function responseCallback(responseData) {});}, false);';


switch(intval($errorType))
{
    case 404:
        $errorMsg = 'Sorry, you have reached this page in error. Please email <a href="mailto:'. SUPPORT_EMAIL . '">' . SUPPORT_EMAIL . '</a> if problems persist. Like, seriously.';
        $JS_INJECT = '<script>setTimeout(function(){' . $JS_QUIT . '},5000);</script>';
        break;

    case 403:
	if(MODE=='prod'){
        	$errorMsg = 'Your IP ' . $_SERVER['HTTP_X_FORWARDED_FOR'] . ' and device has been flagged. If you believe this has been in error please contact support at: ' . SUPPORT_EMAIL . ' and we can address any technical difficulties.';
	}else{
        	$errorMsg = 'Your IP ' . $_SERVER['REMOTE_ADDR'] . ' and device has been flagged. If you believe this has been in error please contact support at: ' . SUPPORT_EMAIL . ' and we can address any technical difficulties.';
	}

        //add some fancy code to track the user and flag # of abuses or something\
        $JS_INJECT = 'setTimeout(function(){' . $JS_QUIT . '},5000);';
        break;

    default:
        $errorType = 404;
        $errorMsg = 'Sorry, you have reached this page in error';
        break;
}

$MESSAGE_HEADER = '<h1 class="error">' . $errorType .'</h1>';
$MESSAGE_BODY = $errorMsg;
include(VIEW_PATH . 'messageView.php');
