<?php
session_start();


/**
 * ==================================
 * AppRewarder by paul@stackpunch.com
 * Started: 9/3/12
 * ==================================
 */

/*
 *  TODO:CRON JOBS
 *  -cron.php flag_expired_offers  (run every 30 minutes)
 */

/* ====================
 * SERVER CONFIGURATION
 * ====================
 */

include_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');


//TODO: clean this up, are all of these used?
if(!isset($_SESSION['offerData'])) $_SESSION['offerData'] = '';
if(!isset($_SESSION['offerTime'])) $_SESSION['offerTime'] = '';
if(!isset($_SESSION['offerLastURL'])) $_SESSION['offerLastURL'] = '';
if(!isset($_SESSION['offerCurrentPage'])) $_SESSION['offerCurrentPage'] = '';
if(!isset($_SESSION['offerRankIndex'])) $_SESSION['offerRankIndex'] = '';

/* ================================
 * MODEL CLASS AND LIBRARY INCLUDES
 * ================================
 */
include_once(LIB_PATH . 'UtilityManager.php');
include_once(LIB_PATH . 'Database.php');
include_once(MODEL_PATH . 'User.php');
include_once(MODEL_PATH . 'Reward.php');
include_once(MODEL_PATH . 'Offer.php');
include_once(LIB_PATH . 'OfferManager.php');
include_once(LIB_PATH . 'DeviceDetectManager.php');
include_once(LIB_PATH . 'GeoIP.php');
include_once(LIB_PATH . 'class.phpmailer.php');
include_once(LIB_PATH . 'facebook.php');

/* ===========================
 * CONSTRUCT ROUTING VARIABLES
 * ===========================
 */
$q = (isset($_GET['q']))?explode('/',$_GET['q']):'';
$controllerName = strtolower((isset($q[0]))?$q[0]:'');
$controllerFunction = strtolower((isset($q[1]))?$q[1]:'');
$controllerID = strtolower((isset($q[2]))?$q[2]:'');
$controllerData = strtolower((isset($q[3]))?$q[3]:'');
$controllerData2 = strtolower((isset($q[4]))?$q[4]:'');
$controllerData3 = strtolower((isset($q[5]))?$q[5]:'');

/* ==============================
 * INSTANTIATE INSTANCE VARIABLES
 * ==============================
 */
$utilityInstance = new UtilityManager();
$userInstance = new User();
$offerInstance = new OfferManager();
$detect_type = new DeviceDetectManager();


/* =============
 * ROUTING LOGIC
 * =============
 */
/*
$facebook = new Facebook(array('appId'  => FACEBOOK_APP_ID,'secret' => FACEBOOK_SECRET_KEY,'cookie' => true,));
print 'facebook' .print_r($facebook->api('/me'),true);
exit();*/

//exit('<pre>' . print_r($offerInstance->get_fyber_offers(),true).'</pre>');


/* ==============================
 * PUBLIC ACCESS (Callbacks,etc.)
 * ==============================
 */

if($controllerName == 'offer' && $controllerFunction == 'cb')
{
        $network_name = $controllerID;
        include_once(CONTROLLER_PATH . 'callback.php');
        die();
}

switch($controllerName)
{
    case 'debug':
        include_once(CONTROLLER_PATH . 'debug.php');
        die();
    break;

    case 'download':
        include_once(CONTROLLER_PATH . 'download.php');
        break;

    case 'cb':
        $network_name = $controllerFunction;
        include_once(CONTROLLER_PATH . 'callback.php');
        die();
    break;

    case 'i':
        include_once(CONTROLLER_PATH . 'referral.php');
        die();
    break;

    case 'moregames':
        include(VIEW_PATH. 'moreGamesView.php');
        exit();
    break;

    case 'appstore':
        include(VIEW_PATH . 'appStoreLanding/appStoreLanding.php');
        exit();
    break;

    case 'c':
        include_once(CONTROLLER_PATH . 'confirm.php');
        die();
        break;

    case 'local':
        if(MODE == 'local') {
            header('Location: http://' . API_HOST .'/register/login/?clientVersion=1.0&aidid=##########&aiuid=##########');
            exit();
        }
    break;
}

/*  =====================
 *  INTERCEPT FB CALLBACK
 *  =====================
 */

if($controllerName == 'fb') {
    /*
    $facebook = new Facebook(array('appId'  => FACEBOOK_APP_ID,'secret' => FACEBOOK_SECRET_KEY,'cookie' => true,));
    $user = $facebook->getUser();
    if ($user){// && !$userInstance->user_has_facebook()) {
        try {
            $user_profile = $facebook->api('/me');
            $userInstance->update_user_facebook($_SESSION['userID'],$user_profile['email'],$user_profile['name'],$user_profile['gender'],$user_profile['id'],$user_profile['locale'],$facebook->getAccessToken(),$user_profile['verified']);

        } catch (FacebookApiException $e) {
            $utilityInstance::log('fbError:'.$e);
            $user = null;
        }
    }
    $fbBtoken = $facebook->getAccessToken();
    print '<pre>';
    print 'token:' . $facebook->getAccessToken();
    print 'user:' . print_r($facebook->api('/me'),true);
    $userinfo = $facebook->getUser();
    $userinfo->
    exit();
    //if($detect_type->isChrome()) header('Location: intent://scan/#Intent;scheme=apprewarder;package=com.stackpunch.apprewarder;end');
    //else
    */
    header('Location: apprewarder://fb');
    exit();
    }


/* =========================
 * PUBLIC MOBILE ACCESS ONLY
 * =========================
 */

//USER NOT LOGGED IN
if(!$userInstance->user_is_logged_in())
{
    //USER NOT ON MOBILE
    if(!$detect_type->isMobile())
    {
        /*  ====================
         *  SHOW DESKTOP WEBSITE
         *  ====================
         */
        header('Location: http://' . WWW_HOST);
        exit();
    //USER ON MOBILE, BUT NOT WITH APPREWARDER CLIENT *AND* WERE IN STAGE/PROD
    } else if(!$detect_type->isAppRewarderClient()) {


        /*  ========================
         *  SHOW MOBILE LANDING PAGE
         *  ========================
         */
        $APK_FILE = 'apprewarder_stage.apk';
        switch($userInstance->get_user_platform()) {
            case PLATFORM_IPAD: $deviceName = "iPad";$downloadUrl=IOS_DOWNLOAD_URL; break;
            case PLATFORM_IPOD: $deviceName = "iPod";$downloadUrl=IOS_DOWNLOAD_URL; break;
            case PLATFORM_IPHONE: $deviceName = "iPhone";$downloadUrl=IOS_DOWNLOAD_URL; break;
            //case PLATFORM_IOS: $deviceName = "iOS device";$downloadUrl=IOS_DOWNLOAD_URL; break;
            case PLATFORM_ANDROID: $deviceName = "Android device"; $downloadUrl=(MODE=='prod')?GOOGLEPLAY_DOWNLOAD_URL:$APK_FILE;break;
            default: $deviceName = "mobile device";$downloadUrl=(MODE=='prod')?GOOGLEPLAY_DOWNLOAD_URL:$APK_FILE; break;
        }
        include_once(VIEW_PATH . 'landingPageView.php');
        exit();
    }
}


/* ===================
 * PRIVATE ACCESS ONLY
 * ===================
 */

UtilityManager::log('Private API - Request:' . $_GET['q']);

/*
$seconds_to_cache = SERVER_CACHE_DURATION;
$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
header("Expires: $ts");
header("Pragma: cache");
header("Cache-Control: max-age=$seconds_to_cache");

UtilityManager::log('Cache set: [ Expires: ' . $ts . '] [ Cache-control: max-age=:' . $seconds_to_cache . ' ]');
*/

//if the visible user data is not set, reload it
if(!isset($_SESSION['userCredits']) || !isset($_SESSION['userFriendCode']) || !isset($_SESSION['userAccountStatus']))
{
    $userInstance->user_refresh_data();
}

$userCredits = (isset($_SESSION['userCredits']))?$_SESSION['userCredits']:0;
$userFriendCode = (isset($_SESSION['userFriendCode']))?$_SESSION['userFriendCode']:'';
$userEmail =  (isset($_SESSION['userEmail']) && UtilityManager::is_email($_SESSION['userEmail']))?$_SESSION['userEmail']:'';



switch($controllerName)
{
    case 'home':
        include_once(CONTROLLER_PATH . 'home.php');
    break;

    case 'reward':
        include_once(CONTROLLER_PATH . 'reward.php');
    break;

    case 'share':
        include_once(CONTROLLER_PATH . 'share.php');
    break;

    case 'other':
        include_once(CONTROLLER_PATH . 'other.php');
    break;

    case 'help':
        include_once(CONTROLLER_PATH . 'help.php');
    break;

    case 'forum':
        include_once(FORUM_PATH . 'index.php');
    break;

    case 'register':
        include_once(CONTROLLER_PATH . 'register.php');
    break;

    case 'offers':
        include_once(CONTROLLER_PATH . 'offers.php');
    break;

    case 'history':
        include_once(CONTROLLER_PATH . 'history.php');
    break;

    case 'beta':
        include_once(VIEW_PATH . 'betaView.php');
    break;

    case 'faq':
        include_once(VIEW_PATH . 'faqView.php');
    break;
    case 'university':
        include_once(CONTROLLER_PATH . 'university.php');
        break;

    case 'offer':
        include_once(CONTROLLER_PATH . 'offer.php');
    break;

    case 'help':
        include_once(CONTROLLER_PATH . 'help.php');
        break;

    case 'promo':
        include_once(CONTROLLER_PATH . 'promo.php');
    break;

    case 'vault':
        include_once(CONTROLLER_PATH . 'vault.php');
    break;
/*
    case 'provision':
        //DEPRECATED

        include_once(CONTROLLER_PATH . 'provision.php');
    break;
*/
    case 'srv':
        include_once(CONTROLLER_PATH . 'srv.php');
    break;

    case 'error':
        include_once(CONTROLLER_PATH . 'error.php');
    break;

    case 'logout':     //DESTROY SESSION VARS, SHOW LOGIN PAGE
        //TODO: possible deprecate this, is this necessary?
        session_destroy();
        header('Location: ' . SERVER_PROTOCOL . API_HOST);
    break;

    /*  ========================
     *  DISPLAY CLIENT DASHBOARD
     *  ========================
     */

    case 'r': //DUMP CACHE, RELOAD DASHBOARD
        $userInstance->user_refresh_data();
        $offerInstance->purge_offers();
        unset($_SESSION['offerNetwork']);
    default:

        //Passively determine if a referral is being made
        if(!empty($_GET['arReferralID']) && !empty($_GET['arReferralSource'])) { $_SESSION['arReferralID'] = $_GET['arReferralID'];$_SESSION['arReferralSource'] = $_GET['arReferralSource']; }
        include_once(CONTROLLER_PATH . 'main.php');
    break;
}
exit();


?>