<?php
/**
 * =============================
 * AppRewarder by paulpierre
 * Started: 9/3/12
 * =============================
 */
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('America/Los_Angeles');

//server mode

//define('MODE',(isset($_SERVER['MODE']))?$_SERVER['MODE']:'prod'); //local,stage,prod
define('MODE',getenv('MODE'));
define('ROOT_PATH','../mobile/');
define('DATA_PATH',ROOT_PATH . 'data/');

include_once(ROOT_PATH . 'config.php');

include_once(ROOT_PATH . 'lib/' . 'UtilityManager.php');
include_once(ROOT_PATH . 'lib/' . 'Database.php');
include_once(ROOT_PATH . 'model/' . 'User.php');
include_once(ROOT_PATH . 'model/' . 'Reward.php');
include_once(ROOT_PATH . 'model/' . 'Offer.php');
include_once(ROOT_PATH . 'lib/' . 'OfferManager.php');
include_once(ROOT_PATH . 'lib/' . 'DeviceDetectManager.php');
include_once(ROOT_PATH . 'lib/' . 'GeoIP.php');


//establish instances
$utilityInstance = new UtilityManager();
$userInstance = new User();
//$offerInstance = new OfferManager();

$cron_function = $argv[1];
$cron_param1 = isset($argv[2])?$argv[2]:'';
$cron_param2 = isset($argv[3])?$argv[3]:'';
$cron_param3 = isset($argv[4])?$argv[4]:'';
if(!isset($cron_function)) exit('no function passed!!');

switch($cron_function)
{
    case 'show_expired':
        $timeFrame = intval((24 * 60 * 60));
        $n = intval(time() - $timeFrame) ;
        echo 'TARGET TIME: ' . $n . ' - ' . date('Y-m-d',$n) . PHP_EOL;
        echo 'TIME: ' . intval(time()) . ' TIMEFRAME: ' . $timeFrame  . PHP_EOL;
        echo 'N:' . date('Y-m-d',1399939593) . PHP_EOL ;
        $offers = $userInstance->get_expired_offers($timeFrame);
        if($offers)
        {
            foreach($offers as $offer)
            {
                print  PHP_EOL .'ID: ' . $offer['id'] . ' TCREATE: ' . date('Y-m-d',$offer['offer_tcreate']) . ' NAME: ' . $offer['offer_name'];
            }
        }
        echo  PHP_EOL;
        //date('m/d/Y',
        exit();
    break;

    case 'flag_expired_offers':
        $n =  intval(time() - OFFER_POSTBACK_EXPIRY);
        $q = 'UPDATE user_offers SET offer_status = 5, offer_tmodified = "' . time(). '" WHERE offer_tcreate <= ' . $n .' AND offer_status=0';
        $result = $userInstance->db_query($q);
        //return intval($result[0]['sum']);
        if(empty($result)) return false;
        return $result;

    break;

    case 'flag_vpn_check_all':
        $geoISP = Net_GeoIP::getInstance(DATA_PATH. 'GeoIPOrg.dat');
        $geoCountry = Net_GeoIP::getInstance(DATA_PATH. 'GeoIP.dat');
        $interval_check = 1; //1 day
        $db = new User();
        $q = 'SELECT DISTINCT(user_ip),user_id,user_tlogin FROM user_account';
        $result = $db->db_query($q);
        if(empty($result)) exit($cron_function. ': VPN check. No results.');

        foreach($result as $item)
        {
            $user_ip  = $item['user_ip'];
            $user_id = $item['user_id'];
            $isp_org = $geoISP->lookupOrg($user_ip);
            $isp_country = $geoCountry->lookupCountryCode($user_ip);
            $values = array(
                'user_ip'=>$user_ip,
                'isp_tcreate'=>time(),
                'isp_tmodified'=>time(),
                'isp_name'=>(isset($isp_org))?$isp_org:'?',
                'isp_country'=>(isset($isp_country))?$isp_country:'?'
            );
            $result = $db->db_create('sys_isp',$values);
        }
        unset($db);
        exit($cron_function . ': update ' . count($result) . ' ip records in sys_isp');
        break;

    case 'flag_vpn_check':
        $geoISP = Net_GeoIP::getInstance(DATA_PATH. 'GeoIPOrg.dat');
        $geoCountry = Net_GeoIP::getInstance(DATA_PATH. 'GeoIP.dat');
        $interval_check = 1; //1 day
        $db = new User();
        $q = 'SELECT DISTINCT(user_ip),user_id,user_tlogin FROM user_account WHERE  user_tlogin BETWEEN DATE_SUB(NOW(), INTERVAL ' . $interval_check  .' DAY) AND NOW()';
        $result = $db->db_query($q);
        if(empty($result)) exit($cron_function. ': VPN check. No results.');

        foreach($result as $item)
        {
            $user_ip  = $item['user_ip'];
            $user_id = $item['user_id'];
            $isp_org = $geoISP->lookupOrg($user_ip);
            $isp_country = $geoCountry->lookupCountryCode($user_ip);
            $values = array(
                'user_ip'=>$user_ip,
                'isp_tcreate'=>time(),
                'isp_tmodified'=>time(),
                'isp_name'=>(isset($isp_org))?$isp_org:'?',
                'isp_country'=>(isset($isp_country))?$isp_country:'?'
            );
            $result = $db->db_create('sys_isp',$values);
        }
        unset($db);
        exit($cron_function . ': update ' . count($result) . ' ip records in sys_isp');
        break;

    case 'clear_expired_user_coins':

        $insert_history="INSERT INTO clear_coin_history SELECT user_id, user_credits, now() FROM user_account WHERE datediff(now(),user_tlogin) > 30";

	    //select round(user_credits * .10,0) from user_account where datediff(now(),user_tlogin) > 30;

        $result = $userInstance->db_query($insert_history);

	    $update="UPDATE user_account SET user_credits=round(user_credits * .10,0) FROM user_account WHERE datediff(now(),user_tlogin) > 30";

        $result = $userInstance->db_query($insert_history);
        break;


    default:
        exit();
    break;
}



?>