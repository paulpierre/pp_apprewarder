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
define('MODE',(isset($_SERVER['MODE']))?$_SERVER['MODE']:'prod'); //local,stage,prod
define('ROOT_PATH','../mobile/');
define('LIB_PATH',ROOT_PATH . 'lib/');
define('MODEL_PATH',ROOT_PATH . 'model/');


include_once(ROOT_PATH . 'config.php');

include_once(LIB_PATH . 'UtilityManager.php');
include_once(LIB_PATH . 'Database.php');
include_once(MODEL_PATH . 'User.php');
include_once(MODEL_PATH . 'Reward.php');
include_once(MODEL_PATH . 'Offer.php');
include_once(LIB_PATH . 'OfferManager.php');
include_once(LIB_PATH . 'DeviceDetectManager.php');
include_once(LIB_PATH . 'GeoIP.php');


//establish instances
$userInstance = new User();



	$insert_history="INSERT INTO clear_coin_history SELECT user_id, user_credits, now() FROM user_account WHERE datediff(now(),user_tlogin) > 30";

	//select round(user_credits * .10,0) from user_account where datediff(now(),user_tlogin) > 30;

        $result = $userInstance->db_query($insert_history);

	$update="UPDATE user_account SET user_credits=round(user_credits * .10,0) FROM user_account WHERE datediff(now(),user_tlogin) > 30";

        $result = $userInstance->db_query($insert_history);

?>
