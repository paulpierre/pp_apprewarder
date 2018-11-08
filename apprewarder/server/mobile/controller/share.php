<?php
/**
 *  Setup the app constants
 */

$PAYOUT_REFERRAL_REFERRER = constant('PAYOUT_REFERRAL_REFERRER');
$PAYOUT_REFERRAL_REFERRED = constant('PAYOUT_REFERRAL_REFERRED');


$FB_PAGE_URL = constant('FB_PAGE_URL');
//$FB_WALL_POST = constant('FB_WALL_POST');
$TWITTER_PAGE_URL = constant('TWITTER_PAGE_URL');
$USER_REFERRAL_URL_EMAIL = $USER_REFERRAL_URL_BASE . REFERRAL_SOURCE_EMAIL . '/'. $_SESSION['userFriendCode'];
$USER_REFERRAL_URL_LINK =  $USER_REFERRAL_URL_BASE . REFERRAL_SOURCE_LINK . '/'. $_SESSION['userFriendCode'];
$USER_REFERRAL_URL_FB =  $USER_REFERRAL_URL_BASE . REFERRAL_SOURCE_FB . '/'. $_SESSION['userFriendCode'];
$USER_REFERRAL_URL_TWITTER =  $USER_REFERRAL_URL_BASE . REFERRAL_SOURCE_TWITTER . '/'. $_SESSION['userFriendCode'];

$FB_FAN_PAGE = constant('FB_FAN_PAGE');

$REFERRAL_MESSAGE = constant('REFERRAL_MESSAGE') . $USER_REFERRAL_URL_EMAIL . ' Earn ' . $PAYOUT_REFERRAL_REFERRED . ' credits when you join!';
//$REFERRAL_FB_MESSAGE = constant('REFERRAL_FB_MESSAGE') . $USER_REFERRAL_URL;
$REFERRAL_TWITTER_MESSAGE = urlencode(constant('REFERRAL_TWITTER_MESSAGE') . $USER_REFERRAL_URL_TWITTER . ' #apprewarder @apprewarder #beermoney');
$FB_REFERRAL_WALL_POST_URL = 'https://www.facebook.com/dialog/feed?app_id=##########&link=' . $USER_REFERRAL_URL_FB .'&picture=http://i.imgur.com/1EALx.png&name=AppRewarder%20-%20Where%20you%20get%20paid%20for%20having%20fun&caption=200%20credits%20on%20me!&description=I%20just%20made%20money%20using%20AppRewarder.com%20just%20by%20downloading%20free%20apps.%20Join%20with%20my%20friend%20code%20' . $_SESSION['userFriendCode'] .'%20to%20get%20' . $PAYOUT_REFERRAL_REFERRED .'%20free%20credits: ' . $USER_REFERRAL_URL_FB .'&redirect_uri=http://www.apprewarder.com'; //constant('FB_WALL_POST') . $REFERRAL_FB_MESSAGE;
$TWITTER_REFERRAL_TWEET_URL = 'https://twitter.com/intent/tweet?text=' . $REFERRAL_TWITTER_MESSAGE;//constant('FB_WALL_POST') . $REFERRAL_FB_MESSAGE;
$MAIL_REFERRAL_URL = constant('MAIL_REFERRAL_URL') . $REFERRAL_MESSAGE;
$SMS_REFERRAL_URL = constant('SMS_REFERRAL_URL') . $REFERRAL_MESSAGE;

$GOOGLE_PLUS_URL = 'http://plus.google.com';

$currentTime = intval(time());

include_once(VIEW_PATH . 'shareView.php');


?>