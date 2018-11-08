<?php
date_default_timezone_set('America/Los_Angeles');
define('MODE',(isset($_SERVER['MODE']))?$_SERVER['MODE']:'local'); //local,stage,prod
session_start();

$q = (isset($_GET['q']))?explode('/',$_GET['q']):'';
$controller_name =       strtolower((isset($q[0]))?$q[0]:'home');
$controller_function =   strtolower((isset($q[1]))?$q[1]:'');
$controller_id =         strtolower((isset($q[2]))?$q[2]:'');
$controller_data =       strtolower((isset($q[3]))?$q[3]:'');
$controller_data2 =      strtolower((isset($q[4]))?$q[4]:'');
$controller_data3 =      strtolower((isset($q[5]))?$q[5]:'');

//Include dependencies
require($_SERVER['DOCUMENT_ROOT'] .    '/config.php');
require($RESOURCE_PATH['LIB_PATH'] .        'Smarty.class.php');
require($APP_RESOURCE_PATH['LIB_PATH'] .'Database.php' );
require($RESOURCE_PATH['LIB_PATH'] . 'GeoIP.php');
require($RESOURCE_PATH['LIB_PATH'] . 'Analytics.php');
require($APP_RESOURCE_PATH['LIB_PATH'] .'class.phpmailer.php' );
require($APP_RESOURCE_PATH['LIB_PATH'] .'DeviceDetectManager.php' );
require($APP_RESOURCE_PATH['MODEL_PATH'] .'User.php' );

require($APP_RESOURCE_PATH['LIB_PATH'] .'OfferManager.php' );
require($APP_RESOURCE_PATH['LIB_PATH'] .'UtilityManager.php' );
require($RESOURCE_PATH['LIB_PATH'] .'phpQuery.php' );
require($APP_RESOURCE_PATH['MODEL_PATH'] .'Offer.php' );
require($APP_RESOURCE_PATH['MODEL_PATH'] .'Reward.php' );
require($RESOURCE_PATH['MODEL_PATH'] . 'SQL.php');





$userInstance = new User;

//Create necessary instances
$smarty = new Smarty;

$analyticsInstance = new Analytics();

//exit('<pre><h1>Adscend:</h1>'.PHP_EOL.print_r($analyticsInstance->adscendStats(),true));



//Assign resource paths and app data for view
$smarty->assign('RESOURCE_PATH',    $RESOURCE_PATH);
$smarty->assign('APP_META',         $APP_META);
$smarty->assign('API_HOST', API_HOST);
//add the file extension to the controller name


//$geoip = Net_GeoIP::getInstance($RESOURCE_PATH['DATA_PATH']. 'GeoIPOrg.dat');
//print $geoip->lookupOrg('216.164.31.93');
//print '<pre>' . print_r($_COOKIE ,true) . print_r($_POST,true) .'</pre>';
if(!isset($_COOKIE['admin']))
{
    if(isset($_POST['password']) && isset($_POST['username']) && md5($_POST['password']) == ADMIN_PASSWORD && $_POST['username'] == ADMIN_USERNAME){
        setcookie('admin',ADMIN_PASSWORD,time()+3600*24*30);
        $controller_name = 'home';
    } else {
        $controller_name = 'login';
    }
}

$controller_file = CONTROLLER_PATH . $controller_name . CONTROLLER_EXT;
if(isset($controller_name) && strlen($controller_name) > 1 && file_exists($controller_file)) {
    include_once($controller_file);
}
exit();
?>