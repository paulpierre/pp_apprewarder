<?php
//API constants

define('LOCAL_IP','127.0.0.1');
//date_default_timezone_set('America/Los_Angeles');
//switching over to UTC
date_default_timezone_set('UTC');
define('MODE',(isset($_SERVER['MODE']))?$_SERVER['MODE']:'local'); //local,stage,prod


define('CLIENT_VERSIONS_SUPPORTED',serialize(array(
    '1.1'
)));

$CLIENT_VERSIONS_SUPPORTED = unserialize(CLIENT_VERSIONS_SUPPORTED);

define('APP_DOMAIN','apprewarder');
define('API_PATH',$_SERVER['DOCUMENT_ROOT']);
//define('WWW_PATH',$_SERVER['DOCUMENT_ROOT'].'/server/www/');
define('IMG_RES', '/view/img');
define('CSS_RES', '/view/css');
define('JS_RES',  '/view/js');
define('MODEL_PATH', API_PATH . '/model/');
define('CLASS_PATH', API_PATH . '/class/');
define('VIEW_PATH', API_PATH . '/view/');
define('BANNER_PATH', VIEW_PATH . 'banners/');
define('LIB_PATH', API_PATH . '/lib/');
define('CONTROLLER_PATH',API_PATH . '/controller/');
define('GEO_DATA_PATH',API_PATH . '/data/GeoIP.dat');
define('TMP_PATH', API_PATH . '/tmp/');
define('FORUM_PATH','../forum/');

define('PLATFORM_IOS',1);
define('PLATFORM_ANDROID',2);
define('PLATFORM_IPHONE',11);
define('PLATFORM_IPAD',12);
define('PLATFORM_IPOD',13);

define('AR_EXCHANGE_RATE',600); //how much coins for $1 USD

define('PROMO_BANNERS',serialize(array(
    0=>array(
        'img'=>IMG_RES . '/banners/banner_06.png',
        'url'=>'##########',
        'os'=>PLATFORM_ANDROID
    ),
    1=>array(
        'img'=>IMG_RES . '/banners/banner_05.png',
        'url'=>'##########',
        'os'=>PLATFORM_IOS
    )
)));

//ANDROID STAFF PICKS OFFERS
define('PROMO_OFFERS',serialize(array(
    0=>array(
        'name'=>'Tiny Toad',
        'url'=>'##########',
        'icon'=>'##########',
        'os'=>PLATFORM_ANDROID
    ),
    1=>array(
        'name'=>'Dookie Bowser',
        'url'=>'##########',
        'icon'=>'##########',
        'os'=>PLATFORM_ANDROID
    ),
    2=>array(
        'name'=>'Do You Know Singapore?',
        'url'=>'##########',
        'icon'=>'##########',
        'os'=>PLATFORM_ANDROID
    ),
    3=>array(
        'name'=>'Do You Know the 90\'s',
        'url'=>'##########',
        'icon'=>'##########',
        'os'=>PLATFORM_ANDROID
    ),
    4=>array(
        'name'=>'Dragon Tactics',
        'url'=>'##########',
        'icon'=>'##########',
        'os'=>PLATFORM_IOS
    )
)));


//db config
switch (MODE)
{
    case 'local': default: //local environment
    define('API_HOST','m.' . APP_DOMAIN);
   // define('API_HOST',LOCAL_IP);
    define('API_PORT',80);
    define('WWW_HOST',APP_DOMAIN);
    define('WWW_PORT',80);
    define('DATABASE_HOST','127.0.0.1');
    define('DATABASE_PORT',3306);
    define('DATABASE_NAME','appinviter');
    define('DATABASE_USERNAME','appinviterwrite');
    define('DATABASE_PASSWORD','##########');
    define('LOG_PATH',TMP_PATH); //TODO: change this
    define('SERVER_PROTOCOL','http://');
    define('FORUM_HOST','forum.apprewarder');

    break;

    case 'stage':  //staging environment
        define('API_HOST','mstage.' . APP_DOMAIN . '.com');
        define('API_PORT',80);
        define('WWW_HOST','stage.' . APP_DOMAIN . '.com');
        define('WWW_PORT',80);
        define('DATABASE_HOST','stage.##########.us-east-1.rds.amazonaws.com');
        define('DATABASE_PORT',3306);
        define('DATABASE_NAME',APP_DOMAIN);
        define('DATABASE_USERNAME',APP_DOMAIN.'write');
        define('DATABASE_PASSWORD','##########');
        define('LOG_PATH',TMP_PATH);
        define('SERVER_PROTOCOL','https://');
        define('FORUM_HOST','forum.apprewarder.com');
        break;

    case 'prod': //production environment
        define('API_HOST','m.' . APP_DOMAIN . '.com');
        define('API_PORT',80);
        define('WWW_HOST','www.' .APP_DOMAIN . '.com');
        define('WWW_PORT',80);
        define('DATABASE_HOST', 'prod-apprewarder.##########.us-east-1.rds.amazonaws.com');
        define('DATABASE_PORT',3306);
        define('DATABASE_NAME',APP_DOMAIN);
        define('DATABASE_USERNAME',APP_DOMAIN . 'write');
        define('DATABASE_PASSWORD','##########');
        define('LOG_PATH','');
        define('SERVER_PROTOCOL','https://');
        define('FORUM_HOST','forum.apprewarder.com');
        break;
}

define('USER_REFERRAL_URL_BASE', 'http://apprw.de/');

define('DEVICE_SCHEMA_URL',"apprewarder://");

define('IOS_DOWNLOAD_URL',SERVER_PROTOCOL .API_HOST . '/download');
define('GOOGLEPLAY_DOWNLOAD_URL','##########');

//encryption keys
define('AR_KEY','##########');
define('AR_IV',##########');

define('APPREWARDER_API_PROVIDER_ID',0);
define('APPREWARDER_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('KSIX_API_KEY','##########');
define('KSIX_API_NETWORK_ID','bmg');
define('KSIX_API_BASE_URL','http://api.hasoffers.com/v3/Affiliate_Offer.json');
define('KSIX_API_PROVIDER_ID',10);
define('KSIX_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('HASOFFERS_API_KEY','##########');
define('HASOFFERS_API_NETWORK_ID','##########');
define('HASOFFERS_API_BASE_URL','http://api.hasoffers.com/v3/Affiliate_Offer.json');
define('HASOFFERS_API_PROVIDER_ID',9);
define('HASOFFERS_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('AARKI_API_PLACEMENT_ID','##########');
define('AARKI_API_CLIENT_SECURITY_KEY','##########');
define('AARKI_API_OFFER_URL','http://ar.aarki.net/offers');
define('AARKI_API_POSTBACK_SECURITY_KEY','##########');
define('AARKI_API_TOKEN','##########');
define('AARKI_API_PROVIDER_ID',2);
define('AARKI_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('AARKI_API_PLACEMENT_ID_IOS','##########');
define('AARKI_API_CLIENT_SECURITY_KEY_IOS','##########');

define('AARKI_API_PLACEMENT_ID_ANDROID','##########');
define('AARKI_API_CLIENT_SECURITY_KEY_ANDROID','##########');

define('ADSCEND_API_APP_KEY','##########');
define('ADSCEND_API_PUBLISHER_ID','##########');
define('ADSCEND_API_OFFER_URL','http://adscendmedia.com/api-get.php');
define('ADSCEND_API_PROVIDER_ID',7);
define('ADSCEND_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('ADACTION_API_KEY','##########');
define('ADACTION_API_NETWORK_ID','adactioninteractive');
define('ADACTION_API_BASE_URL','http://api.hasoffers.com/v3/Affiliate_Offer.json');
define('ADACTION_API_PROVIDER_ID',11);
define('ADACTION_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('FYBER_API_PROVIDER_ID',12);
define('FYBER_API_BASE_URL','http://api.sponsorpay.com/feed/v1/offers.json');
define('FYBER_API_APP_ID_ANDROID','##########');
define('FYBER_API_APP_ID_IOS','##########');
define('FYBER_API_HASH_KEY_ANDROID','##########');
define('FYBER_API_HASH_KEY_IOS','##########');
define('FYBER_API_CB_TOKEN_ANDROID','##########');
define('FYBER_API_CB_TOKEN_IOS','##########');
define('FYBER_ALLOW_OFFER_REFERRAL_PAYOUT',true);


define('OFFER_UNKNOWN',0);
define('OFFER_IOS',1);
define('OFFER_ANDROID',2);


/*
 *  OFFER API CALL PARAMETERS
 */

define('OFFER_PAGE_MAX',15); //maximum number of offers to show when the page loads
/*
 * this will determine the ordering and list of ad networks
 * we will call when an offer list is requested and will prioritize
 * by OS, etc. it will also automatically rank by payout in realtime
 */

//global $OFFER_NETWORK_RANK;
$offerNetworkRank = Array(
    0=>FYBER_API_PROVIDER_ID,
    1=>AARKI_API_PROVIDER_ID,
    2=> APPREWARDER_API_PROVIDER_ID //this includes KSIX/W4

);


//App constants
define('PROVISION_FILENAME','##########.mobileconfig');
define('APP_NAME','AppRewarder');
define('OFFER_TTL',3600); //1hr
define('REWARD_TTL',3600); //1hr
define('MANUAL_OFFERS_TTL',86400);//900); //15 minutes
define('NETWORK_TIMEOUT',15);

define('USER_DEFAULT_CREDITS',0); //starting credits the users get
define('REFERRAL_SIGNUP_BONUS',50); //sign up bonus credits
define('REFERRAL_PAYOUT_MIN_BONUS',300); //referred user must earn this amount before user can qualify for referral bonuses

//Social media constants
define('FB_PAGE_URL','##########');//
define('FB_FAN_PAGE','fb://profile/##########');
//define('FB_WALL_POST','fb://publish/profile/me?text=');

define('GOOGLEPLUS_PAGE_URL', 'http://plus.google.com/');
define('YOUTUBE_PAGE_URL', 'http:///www.youtube.com');

define('FACEBOOK_FAN_PAGE_ID',##########);

define('SUPPORT_EMAIL','##########');

//Payout related stuff
define('PAYOUT_REFERRAL_REFERRER',1000); //the user that referrs and is joining AI gets paid out 500 automatically

define('PAYOUT_REFERRAL_REFERRER_FB',1000);
define('PAYOUT_REFERRAL_REFERRER_TWITTER',1000);

define('PAYOUT_REFERRAL_REFERRED',200); //the user that is a referral and is joining AI gets paid out 200 automatically
define('REFERRAL_MINIMUM_BALANCE',2000); //the minimum number of points a referral must accrue before the referrer gets the referral bonus
define('PAYOUT_MINIMUM_BALANCE',2000); //balance user must have before cashing out to get a gift card
define('PAYOUT_FACEBOOK_LIKE',20);
/**
 *  !!! IMPORTANT !!!
 *  PAYOUT_REFERRAL_OFFER_RATIO means for every download a referred user gets, the
 *  referrer gets a ratio of that. This actually globally sets prices for _everything_,
 *  meaning all users will see the payout values at the difference of the ratio. In other words if
 *  we say all referrers get 0.25 of all referral offer downloads that means if payouts in our
 *  system were at 100, all users will be paid out 75 for that download and the referral credits
 *  would show 25. If PAYOUT_REFERRAL_OFFER_RATIO == 0.60 then a system payout normally at 100
 *  would be shown as 40 for users and 60 for the referral credits.
 *
 *  Obviously this is a bad idea, PAYOUT_REFERRAL_OFFER_RATIO should never be more than 0.5. I'll beat
 *  your ass if you make it lower, I've programmed it to send me an SMS and with your GIT credentials
 *
 */

//define('PAYOUT_REFERRAL_OFFER_RATIO',0.3); //Get paid 50%!
define('PAYOUT_RATIO_TO_REFERRAL',.25);
define('PAYOUT_RATIO_TO_DOWNLOADER',.5);


define('TWITTER_PAGE_URL','http://www.twitter.com/AppRewarder');
define('FACEBOOK_PAGE_URL','fb://profile/' . FACEBOOK_FAN_PAGE_ID);
define('REFERRAL_MESSAGE','Hey buddy, checkout AppRewarder, a free web app for your phone that pays you cash for simply downloading and trying out apps for 30 seconds. No commitment, download an app, run it for 30 seconds and get paid. Get ' . REFERRAL_SIGNUP_BONUS .' when you sign up with my referral code. Visit AppRewarder now on your phone\'s browser to get started: ');
define('MAIL_REFERRAL_URL','mailto:yourfriend@example.com?subject=AppRewarder%20is%20the%20bee\'s%20knees%2C%20try%20apps%20for%20free%20and%20get%20paid%20!&body=' . REFERRAL_MESSAGE);
define('REFERRAL_FB_MESSAGE', 'Just made money using ' . APP_NAME . '.com just by downloading free apps. Join with my referral code to get ' . REFERRAL_SIGNUP_BONUS .' free coins: ');
define('REFERRAL_TWITTER_MESSAGE', 'Made some $$$ just trying out apps w/ @' . APP_NAME . '  Get ' . REFERRAL_SIGNUP_BONUS . ' FREE coins & #freemoney using: ');
define('TWITTER_REFERRAL_TWEET_URL','https://twitter.com/intent/tweet?text=');
define('FB_REFERRAL_WALL_POST_URL','http://www.facebook.com/sharer/sharer.php?u=');
define('GOOGLE_PLUS_URL','https://plus.google.com/share?url=');
define('SMS_REFERRAL_URL','sms:1234567?body=' . REFERRAL_MESSAGE);

//referral source identifier
define('REFERRAL_SOURCE_UNKNOWN',0);
define('REFERRAL_SOURCE_EMAIL',1);
define('REFERRAL_SOURCE_FB',2);
define('REFERRAL_SOURCE_TWITTER',3);
define('REFERRAL_SOURCE_LINK',4);
define('REFERRAL_SOURCE_MANUAL',5);
define('REFERRAL_SOURCE_SMS',6);
define('REFERRAL_SOURCE_GOOGLEPLUS',7);
define('REFERRAL_SOURCE_QR',8);

define('REWARD_SOURCE_APPREWARDER',0);
define('REWARD_SOURCE_AMAZON',1);
define('REWARD_SOURCE_ITUNES',2);
define('REWARD_SOURCE_PAYPAL',3);
define('REWARD_SOURCE_BITCOIN',4);
define('REWARD_SOURCE_PAID_IOS',5);
define('REWARD_SOURCE_IAP_IOS',6);
define('REWARD_SOURCE_PAID_ANDROID',7);
define('REWARD_SOURCE_IAP_ANDROID',8);

//reward sources
$rewardSources = array(
    REWARD_SOURCE_APPREWARDER=>array(
        'name'=>'APPREWARDER',
        'image'=>'icon-ipad-mini.png',
        'css' => ''
    ),
    REWARD_SOURCE_AMAZON=>array(
        'name'=>'AMAZON',
        'image'=>'icon-amazon.png',
        'css'=>'ar-amazon-card'
    ),
    REWARD_SOURCE_ITUNES=>array(
        'name'=>'ITUNES',
        'image'=>'icon-itunes.png',
        'css'=>'ar-itunes-card'
    ),
    REWARD_SOURCE_PAYPAL=>array(
        'name'=>'PAYPAL',
        'image'=>'icon-paypal.png',
        'css'=>'ar-paypal-card'
    ),
    REWARD_SOURCE_BITCOIN=>array(
        'name'=>'BITCOIN',
        'image'=>'icon-bitcoin.png',
        'css'=>'ar-bitcoin-card'
    ),
    REWARD_SOURCE_PAID_IOS=>array(
        'name'=>'IOS_APP',
        'image'=>'',
        'css'=>'ar-appstore-icon'
    ),
    REWARD_SOURCE_IAP_IOS=>array(
        'name'=>'IOS_IAP',
        'image'=>'',
        'css'=>'ar-reward-iap'
    ),
    REWARD_SOURCE_PAID_ANDROID=>array(
        'name'=>'ANDROID_APP',
        'image'=>'',
        'css'=>'ar-appstore-icon'
    ),

    REWARD_SOURCE_IAP_ANDROID=>array(
        'name'=>'ANDROID_IAP',
        'image'=>'',
        'css'=>'ar-reward-iap'
    )
);


define('REWARD_PAGE_MAX',8);
define('HISTORY_PAGE_MAX',5);

//Payout multiplier
define('PAYOUT_CONVERSION_FLURRY',0.30); //1
define('PAYOUT_CONVERSION_FLURRY_MIN',250);
define('PAYOUT_CONVERSION_FLURRY_MAX',250);
define('PAYOUT_CONVERSION_FYBER',AR_EXCHANGE_RATE*0.75); //4
define('PAYOUT_CONVERSION_FYBER_MIN',1);
define('PAYOUT_CONVERSION_FYBER_MAX',10000);
define('PAYOUT_CONVERSION_EVERBADGE',100*0.30); //4
define('PAYOUT_CONVERSION_EVERBADGE_MIN',1);
define('PAYOUT_CONVERSION_EVERBADGE_MAX',10000);
define('PAYOUT_CONVERSION_ADSCEND',1000*0.40); //4
define('PAYOUT_CONVERSION_ADSCEND_MIN',1);
define('PAYOUT_CONVERSION_ADSCEND_MAX',10000);
define('PAYOUT_CONVERSION_AARKI',1000*0.40);
define('PAYOUT_CONVERSION_AARKI_MIN',1);
define('PAYOUT_CONVERSION_AARKI_MAX',1000);
define('PAYOUT_CONVERSION_W3I',100*0.330); //5
define('PAYOUT_CONVERSION_W3I_MIN',1);
define('PAYOUT_CONVERSION_W3I_MAX',10000);
define('PAYOUT_CONVERSION_SUPERSONICADS',10*0.30); //4
define('PAYOUT_CONVERSION_SUPERSONICADS_MIN',1);
define('PAYOUT_CONVERSION_SUPERSONICADS_MAX',10000);
define('PAYOUT_CONVERSION_HASOFFERS',AR_EXCHANGE_RATE*0.75); //4
define('PAYOUT_CONVERSION_HASOFFERS_MIN',1);
define('PAYOUT_CONVERSION_HASOFFERS_MAX',10000);
define('PAYOUT_CONVERSION_ADACTION',AR_EXCHANGE_RATE*0.75); //4
define('PAYOUT_CONVERSION_ADACTION_MIN',1);
define('PAYOUT_CONVERSION_ADACTION_MAX',10000); //$0.75
define('PAYOUT_CONVERSION_KSIX',10*0.30); //4
define('PAYOUT_CONVERSION_KSIX_MIN',1);
define('PAYOUT_CONVERSION_KSIX_MAX',10000);


//banned offers
define('BANNED_OFFERS','juno,junowallet,appcasher,appredeem,freeappslots,freemyapps,apptrailers,points2shop,app trailers,tapcash,featurepoints,feature points');

//Server constants

//for the user ID friend code generator
define('FRIENDCODE_CHARS', '0123456789abcdefghijklmnopqrstuvwxyz');

//Offertype configurations.. to help me read these easier rather than referencing #'s
/* 0=all
* 1=install (all apps)
* 2=install free only
* 3=install paid only
* 4=lead-gen AND video only
* 5=lead-gnonceen only
* 6=video only
 */
define('OFFERS_ALL',0);
define('OFFERS_APPS',1);
define('OFFERS_VIDEO',2);

// DEBUG stuff

define('DEBUG_ADMIN_PASSWORD','##########');

// NONCE stuff
define('NONCE_KEY','##########');
define('NONCE_TTL',300); //5 minutes

define('OFFER_POSTBACK_EXPIRY_DAYS',3);
define('OFFER_POSTBACK_EXPIRY',intval((24 * 60 * 60 * OFFER_POSTBACK_EXPIRY_DAYS))); //24 hrs

$lockedOffers = array(
  //'##########' //memory plus offer ID hash md5('memory+');
);

define('SUPPORT_EMAIL_PASSWORD','##########');
define('SUPPORT_EMAIL_NAME','AppRewarder Support');
define('EMAIL_CONFIRMATION_SUBJECT','AppRewarder Email Confirmation');

/*
 *  ACCOUNT STATUS STATES
 */

// <is registered><email confirmed><app lock confirm><ban status>

define('ACCOUNT_REGISTERED',1);
define('ACCOUNT_UNREGISTERED',0);
define('ACCOUNT_EMAIL_UNCONFIRMED',0);
define('ACCOUNT_EMAIL_CONFIRMED',1);
define('ACCOUNT_APPS_LOCKED',0);
define('ACCOUNT_APPS_UNLOCKED',1);
define('ACCOUNT_OK',0);
define('ACCOUNT_WARN_GENERAL',1);
define('ACCOUNT_WARN_SUSPICIOUS_ACTIVITY',2);
define('ACCOUNT_BANNED_SUSPENDED',3);
define('ACCOUNT_BANNED_PERMANENT_BAN',9);

define('FACEBOOK_APP_ID','##########');
define('FACEBOOK_SECRET_KEY','##########');

$adminAccounts = Array('##########','##########','##########');

define('SERVER_CACHE_DURATION',1800);


define('OFFER_FILTER_TARGET_TITLE',1);
define('OFFER_FILTER_TARGET_DESCRIPTION',2);
define('OFFER_FILTER_ACTION_HIDE',1);
define('OFFER_FILTER_CONDITION_NONE',0);
define('OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT',1);
define('OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT',2);
define('OFFER_FILTER_CONDITION_HAS_ICON',3);
define('OFFER_FILTER_CONDITION_HAS_NO_ICON',4);

define('COUNTRIES',serialize(array(
    'INT'=>'International',
    'AF' => 'Afghanistan',
    'AX' => 'Aland Islands',
    'AL' => 'Albania',
    'DZ' => 'Algeria',
    'AS' => 'American Samoa',
    'AD' => 'Andorra',
    'AO' => 'Angola',
    'AI' => 'Anguilla',
    'AQ' => 'Antarctica',
    'AG' => 'Antigua And Barbuda',
    'AR' => 'Argentina',
    'AM' => 'Armenia',
    'AW' => 'Aruba',
    'AU' => 'Australia',
    'AT' => 'Austria',
    'AZ' => 'Azerbaijan',
    'BS' => 'Bahamas',
    'BH' => 'Bahrain',
    'BD' => 'Bangladesh',
    'BB' => 'Barbados',
    'BY' => 'Belarus',
    'BE' => 'Belgium',
    'BZ' => 'Belize',
    'BJ' => 'Benin',
    'BM' => 'Bermuda',
    'BT' => 'Bhutan',
    'BO' => 'Bolivia',
    'BA' => 'Bosnia And Herzegovina',
    'BW' => 'Botswana',
    'BV' => 'Bouvet Island',
    'BR' => 'Brazil',
    'IO' => 'British Indian Ocean Territory',
    'BN' => 'Brunei Darussalam',
    'BG' => 'Bulgaria',
    'BF' => 'Burkina Faso',
    'BI' => 'Burundi',
    'KH' => 'Cambodia',
    'CM' => 'Cameroon',
    'CA' => 'Canada',
    'CV' => 'Cape Verde',
    'KY' => 'Cayman Islands',
    'CF' => 'Central African Republic',
    'TD' => 'Chad',
    'CL' => 'Chile',
    'CN' => 'China',
    'CX' => 'Christmas Island',
    'CC' => 'Cocos (Keeling) Islands',
    'CO' => 'Colombia',
    'KM' => 'Comoros',
    'CG' => 'Congo',
    'CD' => 'Congo, Democratic Republic',
    'CK' => 'Cook Islands',
    'CR' => 'Costa Rica',
    'CI' => 'Cote D\'Ivoire',
    'HR' => 'Croatia',
    'CU' => 'Cuba',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DK' => 'Denmark',
    'DJ' => 'Djibouti',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'EC' => 'Ecuador',
    'EG' => 'Egypt',
    'SV' => 'El Salvador',
    'GQ' => 'Equatorial Guinea',
    'ER' => 'Eritrea',
    'EE' => 'Estonia',
    'ET' => 'Ethiopia',
    'FK' => 'Falkland Islands (Malvinas)',
    'FO' => 'Faroe Islands',
    'FJ' => 'Fiji',
    'FI' => 'Finland',
    'FR' => 'France',
    'GF' => 'French Guiana',
    'PF' => 'French Polynesia',
    'TF' => 'French Southern Territories',
    'GA' => 'Gabon',
    'GM' => 'Gambia',
    'GE' => 'Georgia',
    'DE' => 'Germany',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GR' => 'Greece',
    'GL' => 'Greenland',
    'GD' => 'Grenada',
    'GP' => 'Guadeloupe',
    'GU' => 'Guam',
    'GT' => 'Guatemala',
    'GG' => 'Guernsey',
    'GN' => 'Guinea',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HT' => 'Haiti',
    'HM' => 'Heard Island & Mcdonald Islands',
    'VA' => 'Holy See (Vatican City State)',
    'HN' => 'Honduras',
    'HK' => 'Hong Kong',
    'HU' => 'Hungary',
    'IS' => 'Iceland',
    'IN' => 'India',
    'ID' => 'Indonesia',
    'IR' => 'Iran, Islamic Republic Of',
    'IQ' => 'Iraq',
    'IE' => 'Ireland',
    'IM' => 'Isle Of Man',
    'IL' => 'Israel',
    'IT' => 'Italy',
    'JM' => 'Jamaica',
    'JP' => 'Japan',
    'JE' => 'Jersey',
    'JO' => 'Jordan',
    'KZ' => 'Kazakhstan',
    'KE' => 'Kenya',
    'KI' => 'Kiribati',
    'KR' => 'Korea',
    'KW' => 'Kuwait',
    'KG' => 'Kyrgyzstan',
    'LA' => 'Lao People\'s Democratic Republic',
    'LV' => 'Latvia',
    'LB' => 'Lebanon',
    'LS' => 'Lesotho',
    'LR' => 'Liberia',
    'LY' => 'Libyan Arab Jamahiriya',
    'LI' => 'Liechtenstein',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'MO' => 'Macao',
    'MK' => 'Macedonia',
    'MG' => 'Madagascar',
    'MW' => 'Malawi',
    'MY' => 'Malaysia',
    'MV' => 'Maldives',
    'ML' => 'Mali',
    'MT' => 'Malta',
    'MH' => 'Marshall Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MU' => 'Mauritius',
    'YT' => 'Mayotte',
    'MX' => 'Mexico',
    'FM' => 'Micronesia, Federated States Of',
    'MD' => 'Moldova',
    'MC' => 'Monaco',
    'MN' => 'Mongolia',
    'ME' => 'Montenegro',
    'MS' => 'Montserrat',
    'MA' => 'Morocco',
    'MZ' => 'Mozambique',
    'MM' => 'Myanmar',
    'NA' => 'Namibia',
    'NR' => 'Nauru',
    'NP' => 'Nepal',
    'NL' => 'Netherlands',
    'AN' => 'Netherlands Antilles',
    'NC' => 'New Caledonia',
    'NZ' => 'New Zealand',
    'NI' => 'Nicaragua',
    'NE' => 'Niger',
    'NG' => 'Nigeria',
    'NU' => 'Niue',
    'NF' => 'Norfolk Island',
    'MP' => 'Northern Mariana Islands',
    'NO' => 'Norway',
    'OM' => 'Oman',
    'PK' => 'Pakistan',
    'PW' => 'Palau',
    'PS' => 'Palestinian Territory, Occupied',
    'PA' => 'Panama',
    'PG' => 'Papua New Guinea',
    'PY' => 'Paraguay',
    'PE' => 'Peru',
    'PH' => 'Philippines',
    'PN' => 'Pitcairn',
    'PL' => 'Poland',
    'PT' => 'Portugal',
    'PR' => 'Puerto Rico',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RU' => 'Russian Federation',
    'RW' => 'Rwanda',
    'BL' => 'Saint Barthelemy',
    'SH' => 'Saint Helena',
    'KN' => 'Saint Kitts And Nevis',
    'LC' => 'Saint Lucia',
    'MF' => 'Saint Martin',
    'PM' => 'Saint Pierre And Miquelon',
    'VC' => 'Saint Vincent And Grenadines',
    'WS' => 'Samoa',
    'SM' => 'San Marino',
    'ST' => 'Sao Tome And Principe',
    'SA' => 'Saudi Arabia',
    'SN' => 'Senegal',
    'RS' => 'Serbia',
    'SC' => 'Seychelles',
    'SL' => 'Sierra Leone',
    'SG' => 'Singapore',
    'SK' => 'Slovakia',
    'SI' => 'Slovenia',
    'SB' => 'Solomon Islands',
    'SO' => 'Somalia',
    'ZA' => 'South Africa',
    'GS' => 'South Georgia And Sandwich Isl.',
    'ES' => 'Spain',
    'LK' => 'Sri Lanka',
    'SD' => 'Sudan',
    'SR' => 'Suriname',
    'SJ' => 'Svalbard And Jan Mayen',
    'SZ' => 'Swaziland',
    'SE' => 'Sweden',
    'CH' => 'Switzerland',
    'SY' => 'Syrian Arab Republic',
    'TW' => 'Taiwan',
    'TJ' => 'Tajikistan',
    'TZ' => 'Tanzania',
    'TH' => 'Thailand',
    'TL' => 'Timor-Leste',
    'TG' => 'Togo',
    'TK' => 'Tokelau',
    'TO' => 'Tonga',
    'TT' => 'Trinidad And Tobago',
    'TN' => 'Tunisia',
    'TR' => 'Turkey',
    'TM' => 'Turkmenistan',
    'TC' => 'Turks And Caicos Islands',
    'TV' => 'Tuvalu',
    'UG' => 'Uganda',
    'UA' => 'Ukraine',
    'AE' => 'United Arab Emirates',
    'GB' => 'United Kingdom',
    'US' => 'United States',
    'UM' => 'United States Outlying Islands',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VU' => 'Vanuatu',
    'VE' => 'Venezuela',
    'VN' => 'Viet Nam',
    'VG' => 'Virgin Islands, British',
    'VI' => 'Virgin Islands, U.S.',
    'WF' => 'Wallis And Futuna',
    'EH' => 'Western Sahara',
    'YE' => 'Yemen',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe',
)));
$countries = unserialize(COUNTRIES);

?>
