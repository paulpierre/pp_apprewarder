<?
/*
 *  Lets redirect the user if they are on a mobile device to the mobile site
 */

$q = (isset($_GET['q']))?explode('/',$_GET['q']):'';
$controllerName = strtolower((isset($q[0]))?$q[0]:'');
$controllerFunction = strtolower((isset($q[1]))?$q[1]:'');
$controllerID = strtolower((isset($q[2]))?$q[2]:'');

if($controllerName == 'offer' && $controllerFunction == 'cb'  && $controllerID == 'w3i')
{
    //http://www.appinviter.com/q=offer/cb/w3i/?UDID=00ccc29098ad0c280b57b7d9c263c374c585a850&userid=11273&offerid=Jewel+Mania&payoutamount=4
    header('Location: http://m.apprewarder.com/cb/w3i/?' .str_replace('q=offer/cb/w3i/&',"",$_SERVER['QUERY_STRING']));
    die();
}

if(($controllerName == 'offer' && $controllerFunction == 'cb'  && $controllerID == 'flurry') || ($controllerName == 'cb'  && $controllerFunction == 'flurry'))
{
    //http://www.appinviter.com/q=offer/cb/w3i/?UDID=00ccc29098ad0c280b57b7d9c263c374c585a850&userid=11273&offerid=Jewel+Mania&payoutamount=4
    header('Location: http://m.apprewarder.com/cb/flurry/?' .str_replace('q=offer/cb/flurry/&',"",$_SERVER['QUERY_STRING']));
    die();
}

if(($controllerName == 'offer' && $controllerFunction == 'cb'  && $controllerID == 'sponsorpay') || ($controllerName == 'cb'  && $controllerFunction == 'sponsorpay'))
{
    //http://www.appinviter.com/q=offer/cb/w3i/?UDID=00ccc29098ad0c280b57b7d9c263c374c585a850&userid=11273&offerid=Jewel+Mania&payoutamount=4
    header('Location: http://m.apprewarder.com/cb/sponsorpay/?' .str_replace('q=offer/cb/sponsorpay/&',"",$_SERVER['QUERY_STRING']));
    die();
}

if(($controllerName == 'offer' && $controllerFunction == 'cb'  && $controllerID == 'aarki') || ($controllerName == 'cb'  && $controllerFunction == 'aarki'))
{
    //http://www.appinviter.com/q=offer/cb/w3i/?UDID=00ccc29098ad0c280b57b7d9c263c374c585a850&userid=11273&offerid=Jewel+Mania&payoutamount=4
    header('Location: http://m.apprewarder.com/cb/aarki/?' .str_replace('q=offer/cb/aarki/&',"",$_SERVER['QUERY_STRING']));
    die();
}


define('MODE',(isset($_SERVER['MODE']))?$_SERVER['MODE']:'local'); //local,stage,prod
define('LOCAL_IP','127.0.0.1');


include_once('config.php');
include_once('lib/DeviceDetectManager.php');
$detect_type = new DeviceDetectManager();

//var_dump($detect_type->isMobile());
//exit();
if($detect_type->isMobile())
{
    header('Location: http://' . API_HOST);
    exit();
}
include_once('header.php');

switch($controllerName)
{

    case 'faq':
        include_once('faq.php');
        break;

    case 'privacy':
        include_once('privacy.php');
        break;
    case 'terms':
        include_once('terms.php');

        break;
    default:
        include_once('main.php');
    break;
}
include_once('footer.php');
exit();
?>




















