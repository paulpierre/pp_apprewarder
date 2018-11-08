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
$userFriendCode = $_SESSION['userFriendCode'];




/**
 * OFFERTYPES:
 * -----------
 * 0=all
 * 1=install (all apps)
 * 2=install free only
 * 3=install paid only
 * 4=lead-gen AND video only
 * 5=lead-gen only
 * 6=video only
 */


//if the request is flagged with an 'r', lets refresh and pull from the APIs again
$_SESSION['currentOfferTypes'] = OFFERS_APPS;
$offerInstance = new OfferManager();
/*
if($controllerID == 'r') {
    $offerInstance = new OfferManager();
    $offerInstance->load_offers(OFFERS_APPS,true);
    $utilityInstance->log('offers refreshed');
}

$offerInstance->load_offers(OFFERS_APPS);
*/
include(VIEW_PATH . 'homeView.php');
/**
 *
$referralFriendCode = $controllerID;
$referralSource = intval($controllerFunction);
$referralID = $userInstance->get_user_id_by_user_friend_code($controllerID);     */
/*
if(isset($referralID) && isset($_SESSION['userID']) && intval($referralID) == intval($_SESSION['userID']))
{
    print '<li style="list-style:none;background-color:#ff4932;color:#FFF;font-size:18px;padding:0 3px 3px;">You cannot add your own friend code :(</b></li>';
    $userInstance->user_unset_referral();
    //unset($referralID);
}

//TODO: need to figure out how to display referral code, use alerts

else if(isset($referralID)){ print '<span class="alert yellow">You were invited by:<b>' . $referralFriendCode .'</b></span>'; }
//include(VIEW_PATH . 'wrapperView.php');

*/
?>