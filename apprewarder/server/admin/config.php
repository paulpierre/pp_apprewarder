<?php

define('FLURRY_API_KEY_ANDROID','##########');
define('FLURRY_API_KEY_IOS','##########');
define('FLURRY_API_URL','http://api.flurry.com/appMetrics/METRIC_NAME?apiAccessCode=APIACCESSCODE&apiKey=APIKEY&startDate=STARTDATE&endDate=ENDDATE&country=COUNTRY&versionName=VERSIONNAME&groupBy=GROUPBY');
define('FLURRY_ACCESS_CODE','##########');

define('IMG_RES', '/view/img');
define('CSS_RES', '/view/css');
define('JS_RES',  '/view/js');

define('PLATFORM_IOS',1);
define('PLATFORM_ANDROID',2);
define('PLATFORM_IPHONE',11);
define('PLATFORM_IPAD',12);
define('PLATFORM_IPOD',13);

//Offertype configurations.. to help me read these easier rather than referencing #'s
/* 0=all
* 1=install (all apps)
* 2=install free only
* 3=install paid only
* 4=lead-gen AND video only
* 5=lead-gen only
* 6=video only
 */
define('OFFERS_ALL',0);
define('OFFERS_APPS',1);
define('OFFERS_VIDEO',2);

//encryption keys
define('AR_KEY','##########');
define('AR_IV','##########');

define('APPREWARDER_API_PROVIDER_ID',0);
define('APPREWARDER_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('KSIX_API_KEY','##########');
define('KSIX_API_NETWORK_ID','bmg');
define('KSIX_API_BASE_URL','http://api.hasoffers.com/v3/Affiliate_Offer.json');
define('KSIX_API_PROVIDER_ID',10);
define('KSIX_ALLOW_OFFER_REFERRAL_PAYOUT',true);

define('HASOFFERS_API_KEY','##########');
define('HASOFFERS_API_NETWORK_ID','deviant');
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

define('OFFER_UNKNOWN',0);
define('OFFER_IOS',1);
define('OFFER_ANDROID',2);

//App constants
define('APP_NAME','AppRewarder');
define('OFFER_TTL',3600); //1hr
define('REWARD_TTL',3600); //1hr
define('MANUAL_OFFERS_TTL',86400);//900); //15 minutes
define('NETWORK_TIMEOUT',15);

//Payout related stuff
define('PAYOUT_REFERRAL_REFERRER',1000); //the user that referrs and is joining AI gets paid out 500 automatically

define('PAYOUT_REFERRAL_REFERRER_FB',1000);
define('PAYOUT_REFERRAL_REFERRER_TWITTER',1000);

define('PAYOUT_REFERRAL_REFERRED',200); //the user that is a referral and is joining AI gets paid out 200 automatically
define('REFERRAL_MINIMUM_BALANCE',2000); //the minimum number of points a referral must accrue before the referrer gets the referral bonus
define('PAYOUT_MINIMUM_BALANCE',2000); //balance user must have before cashing out to get a gift card
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

define('AR_EXCHANGE_RATE',600); //how much coins for $1 USD


//reward sources
$rewardSources = array(
    0=>array(
        'name'=>'APPREWARDER',
        'image'=>'icon-ipad-mini.png',
        'css' => ''
    ),
    1=>array(
        'name'=>'AMAZON',
        'image'=>'icon-amazon.png',
        'css'=>'ar-amazon-card'
    ),
    2=>array(
        'name'=>'ITUNES',
        'image'=>'icon-itunes.png',
        'css'=>'ar-itunes-card'
    ),
    3=>array(
        'name'=>'PAYPAL',
        'image'=>'icon-paypal.png',
        'css'=>'ar-paypal-card'
    ),
    4=>array(
        'name'=>'BITCOIN',
        'image'=>'icon-bitcoin.png',
        'css'=>'ar-bitcoin-card'
    )
);

define('REWARD_PAGE_MAX',8);
define('HISTORY_PAGE_MAX',5);

//Payout multiplier
define('PAYOUT_CONVERSION_FLURRY',0.30); //1
define('PAYOUT_CONVERSION_FLURRY_MIN',250);
define('PAYOUT_CONVERSION_FLURRY_MAX',250);
define('PAYOUT_CONVERSION_SPONSORPAY',10*0.50); //4
define('PAYOUT_CONVERSION_SPONSORPAY_MIN',1);
define('PAYOUT_CONVERSION_SPONSORPAY_MAX',10000);
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


//define('PAYOUT_REFERRAL_OFFER_RATIO',0.3); //Get paid 50%!
define('PAYOUT_RATIO_TO_REFERRAL',.25);
define('PAYOUT_RATIO_TO_DOWNLOADER',.5);


//banned offers
define('BANNED_OFFERS','juno,junowallet,appcasher,appredeem,app trailers,freeappslots,freemyapps,apptrailers,points2shop,decanter,nme magazine');

//Server constants

//for the user ID friend code generator
define('FRIENDCODE_CHARS', '0123456789abcdefghijklmnopqrstuvwxyz');



// DEBUG stuff


define('OFFER_POSTBACK_EXPIRY_DAYS',3);
define('OFFER_POSTBACK_EXPIRY',intval((24 * 60 * 60 * OFFER_POSTBACK_EXPIRY_DAYS))); //24 hrs
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







define('AR_START_DATE','2014-06-11');


/*
 *  APP RESOURCE PATHS
 */

define('RESOURCE_PATH',serialize(array(
    'API_PATH' =>       $_SERVER['DOCUMENT_ROOT'],
    'IMG_PATH'=>        '/view/img/',
    'CSS_PATH'=>        '/view/css/',
    'JS_PATH'=>         '/view/js/',
    'MODEL_PATH'=>      $_SERVER['DOCUMENT_ROOT'] . '/model/',
    'CLASS_PATH'=>      $_SERVER['DOCUMENT_ROOT'] . '/class/',
    'VIEW_PATH'=>       $_SERVER['DOCUMENT_ROOT'] . '/view/',
    'LIB_PATH'=>        $_SERVER['DOCUMENT_ROOT'] . '/lib/',
    'DATA_PATH'=>       $_SERVER['DOCUMENT_ROOT'] . '/lib/data/',
    'CONTROLLER_PATH'=> $_SERVER['DOCUMENT_ROOT'] . '/controller/',
    'UPLOAD_PATH'=>     $_SERVER['DOCUMENT_ROOT'] . '/uploads/',
)));


define('QUERY_TTL',60*60*1); //store the cache for 1 hr






$RESOURCE_PATH = unserialize(RESOURCE_PATH);

define('APP_RESOURCE_PATH',serialize(array(
    'MODEL_PATH'=>'../mobile/model/',
    'LIB_PATH'=>'../mobile/lib/'
)));

$APP_RESOURCE_PATH = unserialize(APP_RESOURCE_PATH);

define('APP_FILE_EXTENSION',serialize(array(
    'VIEW_EXT' =>       '.view.tpl',
    'CONTROLLER_EXT' => '.controller.php'
)));

$APP_FILE_EXTENSION = unserialize(APP_FILE_EXTENSION);

// CONSTANTS FOR FILE EXTENSIONS
define('CONTROLLER_EXT',$APP_FILE_EXTENSION['CONTROLLER_EXT']);
define('VIEW_EXT',$APP_FILE_EXTENSION['VIEW_EXT']);

// CONSTANTS FOR FILE PATHS
define('CONTROLLER_PATH',$RESOURCE_PATH['CONTROLLER_PATH']);
define('VIEW_PATH',$RESOURCE_PATH['VIEW_PATH']);

// UNIVERSAL VIEW FILE NAMES
define('HEADER_VIEW', VIEW_PATH . 'header' . VIEW_EXT);
define('FOOTER_VIEW', VIEW_PATH . 'footer' . VIEW_EXT);


define('APP_META',serialize(array(

    'APP_NAME'=>        'AppRewarder Admin',
    'APP_DESCRIPTION'=> 'AppRewarder Admin',
    'APP_AUTHOR'=>      'paul@##########',

)));
$APP_META = unserialize(APP_META);

define('ADMIN_USERNAME','##########');
define('ADMIN_PASSWORD',md5('##########'));

define('APP_DOMAIN','apprewarder');

//db config
switch (MODE)
{
    case 'local': default: //local environment
        define('API_HOST','admin.apprewarder');
        define('DATABASE_HOST','127.0.0.1');
        define('DATABASE_PORT',3306);
        define('DATABASE_NAME','appinviter');
        define('DATABASE_USERNAME','appinviterwrite');
        define('DATABASE_PASSWORD','##########');
        define('LOG_PATH','./');

        break;

    case 'stage':  //staging environment
        define('API_HOST','stageadmin.apprewarder.com');
        define('DATABASE_HOST','stage.##########.us-east-1.rds.amazonaws.com');
        define('DATABASE_PORT',3306);
        define('DATABASE_NAME',APP_DOMAIN);
        define('DATABASE_USERNAME',APP_DOMAIN.'write');
        define('DATABASE_PASSWORD','##########');
        define('LOG_PATH','./');
        break;

    case 'prod': //production environment
        define('API_HOST','admin.apprewarder.com');
        define('DATABASE_HOST', 'prod-apprewarder.##########.us-east-1.rds.amazonaws.com');
        define('DATABASE_PORT',3306);
        define('DATABASE_NAME',APP_DOMAIN);
        define('DATABASE_USERNAME',APP_DOMAIN . 'write');
        define('DATABASE_PASSWORD','##########');
        define('LOG_PATH','./');

        break;
}

define('PROMO_BANNERS',serialize(array(
    0=>array(
        'img'=>IMG_RES . '/banners/banner_06.png',
        'url'=>'https://play.google.com/store/apps/details?id=##########',
        'os'=>PLATFORM_ANDROID
    ),
    1=>array(
        'img'=>IMG_RES . '/banners/banner_05.png',
        'url'=>'https://itunes.apple.com/US/app/##########',
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
        'name'=>'Gods & Dragons',
        'url'=>'##########',
        'icon'=>'##########',
        'os'=>PLATFORM_IOS
    )
)));


define('OFFER_FILTER_TARGET_TITLE',1);
define('OFFER_FILTER_TARGET_DESCRIPTION',2);
define('OFFER_FILTER_ACTION_HIDE',1);
define('OFFER_FILTER_CONDITION_NONE',0);
define('OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT',1);
define('OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT',2);
define('OFFER_FILTER_CONDITION_HAS_ICON',3);
define('OFFER_FILTER_CONDITION_HAS_NO_ICON',4);

define('COUNTRIES',serialize(array(
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
    'GB' => 'Great Britain',
    'UK' => 'United Kingdom',
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