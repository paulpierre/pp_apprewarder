<?php
define('APP_DOMAIN','apprewarder');
define('SUPPORT_EMAIL','support@apprewarder.com');

//Payout related stuff
define('PAYOUT_REFERRAL_REFERRER',1000); //the user that referrs and is joining AI gets paid out 500 automatically

define('PAYOUT_REFERRAL_REFERRER_FB',1000);
define('PAYOUT_REFERRAL_REFERRER_TWITTER',1000);

define('PAYOUT_REFERRAL_REFERRED',200); //the user that is a referral and is joining AI gets paid out 200 automatically
define('REFERRAL_MINIMUM_BALANCE',2000); //the minimum number of points a referral must accrue before the referrer gets the referral bonus
define('PAYOUT_MINIMUM_BALANCE',2000); //balance user must have before cashing out to get a gift card


switch (MODE)
{
    case 'local': default: //local environment
        define('API_HOST','m.' . APP_DOMAIN);
        //define('API_HOST',LOCAL_IP);
        define('API_PORT',80);
    break;

    case 'stage':  //staging environment
        define('API_HOST','mstage.' . APP_DOMAIN . '.com');
        define('API_PORT',80);
        break;

    case 'prod': //production environment
        define('API_HOST','m.'.APP_DOMAIN . '.com');
        define('API_PORT',80);
        break;
}
?>