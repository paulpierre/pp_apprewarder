<?php

$referralFriendCode = (isset($controllerFunction))?$controllerFunction:false;
$referralSource = (isset($controllerID))?$controllerID:false;
/*
 *  the user should only be here if they made a referral OR the app is attempting to do
 *  a referral code check
 *  Referral URL construction: http://m.apprewarder.com/i/7Zi3k/1
 */
if($detect_type->isiOS())
{
    //if this is the ios device attempting to grab referral cookie data
    if($controllerFunction == "ios")
    {
        $referralFriendCode = (!empty($_COOKIE['arReferralID']))?$_COOKIE['arReferralID']:'';
        $referralSource = (!empty($_COOKIE['arReferralSource']))?$_COOKIE['arReferralSource']:'';

        if(!empty($referralFriendCode))
        {
            header('Location: ' . DEVICE_SCHEMA_URL . 'referral/' . $referralFriendCode . '?' . $referralSource);
            exit();
        } else {
            //if this method was called but nothing is in the cookies then something is
            //definitely wrong!
            header('Location: ' . DEVICE_SCHEMA_URL);
            exit();
        }
    }

    //if not lets set the cookies so we can make the referral on the device
    if($referralFriendCode)
    {
        setcookie("arReferralID",$referralFriendCode,time()+3600*48,"/");
        setcookie("arReferralSource",$referralSource,time()+3600*48,"/"); //expire cookie in 48 hrs

    }
    header('Location: ' . IOS_DOWNLOAD_URL);
    exit();




}
    else if($detect_type-> isAndroidOS())
{
    //if its a google play download, lets append the referral parameters

    if(isset($referralFriendCode) && isset($referralSource))
    {
        header('Location: ' . GOOGLEPLAY_DOWNLOAD_URL . "&referrer=" . $referralFriendCode . "&utm_source=". $referralSource);
    } else
    {
        header('Location: ' . GOOGLEPLAY_DOWNLOAD_URL);
        die();
    }


} else
{
    header('Location: http://' . API_HOST . '/error/404');
    die();
}




/*
  case 'i':

      if(strtolower($controllerFunction) == 'claim')// && $referralSource == REFERRAL_SOURCE_MANUAL )
      {
          //an ajax call

          //aiuid:aiuid,aifid:aifid,aifc:aifc,airs:airs
          //$userFriendCode = $_POST['userFriendCode'];

          $referralFriendCode = $_POST['aifc'];
          $referralSource = $_POST['airs'];
          $userID = $_POST['aiuid'];


          //no referral data
          if(!isset($referralFriendCode) || !isset($referralSource)){ print 'A friend code must be provided to claim a referral.'; die();}

          //user not logged in
          //$userID = $_SESSION['userID']; if(!isset($userID) || $_POST['aiuid']) { print 'You must be logged into claim your friend code bonus.'; die(); }

          if(!isset($userID)) { print 'You must be logged into claim your friend code bonus.'; die(); }

          //user attempting to put in their own friend code
          $referralID = $userInstance->get_user_id_by_user_friend_code($referralFriendCode);
          if(!$referralID) { print 'You must provide a valid friend code.'; die();}
          if($referralID == $userID) { print 'You cannot add your own friend code.'; die();}

          //neither friend code nor friend user ID exists
          //if(!isset($referralFriendCode) || !$referralID) { print 'You must provide a valid friend code to claim your friend code bonus.'; die(); }

          //if($userInstance->user_referral_exists($userID,$friendID)) { print 'You have already claimed a friend code bonus for ' . $userFriendCode; die();}



          //the referring user already invited this new user
          if($userInstance->user_did_refer($userID)) { print 'You have already claimed a friend code bonus already.'; die(); }

          //everything checks out okay so create the referral
          $userInstance->user_create_referral($userID,$referralID,$referralSource);

          //pay out the credits for signing up
          $userInstance->user_add_credits($userID,intval(PAYOUT_REFERRAL_REFERRED));
          print 'You have successfully claimed friend code:' . $userFriendCode . ' earning ' . PAYOUT_REFERRAL_REFERRED . ' credits!';
          die();
      }
      else
      {
          $referralFriendCode = $controllerID;
          $referralSource = intval($controllerFunction);
          $referralID = $userInstance->get_user_id_by_user_friend_code($controllerID);
          $_SESSION['referralFriendCode'] = $referralFriendCode;
          $_SESSION['referralID'] = $referralID;
          $_SESSION['referralSource'] = $referralSource;


          $utilityInstance->log('[index.php.backup] referral detected. referral ID:' . $referralID . ' referral friendCode:' . $referralFriendCode . ' referral source:' . $referralSource);
          include_once(CONTROLLER_PATH . 'offer.php');
      }

      break;
  **/


?>