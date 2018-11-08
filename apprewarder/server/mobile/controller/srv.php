<?php

//print('youre here');
switch($controllerFunction)
{
    /*
     *  requesting application list for android
     */

    case 'pkg':
        $package = $_POST['pkg'];
        if(isset($package))
        {

            UtilityManager::log('[srv.php] SRV/PKG Decrypted payload:' . UtilityManager::decrypt($package));
            /*
             * function to store this
             */
        }
        else {
            UtilityManager::log('[srv.php] PKG called but nothing show!');
            //output some error here, someone is trying to hack us or there
            //is something seriously wrong with the client
        }
    break;

    case 'like':

        $userInstance = new User();
        $fbToken = $_SESSION['userFacebookToken'];
        $fbID = $_SESSION['userFacebookID'];
        $userID = $_SESSION['userID'];
        $url = 'https://graph.facebook.com/v2.0/me/likes/' . FACEBOOK_FAN_PAGE_ID .'?access_token=' . $fbToken;

        if(!$userInstance->user_has_facebook()) {
            $dialogData = array(
                'status'=>4,
                'title'=>'Facebook',
                'body'=>'Connect your Facebook account to App Rewarder to enable you to receive rewards!'
            );
            exit(json_encode($dialogData));
        } elseif($userInstance->user_did_like_facebook_page($userID))
        {
            $dialogData = array(
                'status'=>0,
                'title'=>'Oops..',
                'body'=>'It looks like you have already redeemed coins for liking our Facebook page already.'
            );
            exit(json_encode($dialogData));

        }


        $result = json_decode(file_get_contents( $url ),true);

        if(empty($result['data']) || intval($result['data'][0]['id']) !== FACEBOOK_FAN_PAGE_ID)
        {
            $dialogData = array(
                'status'=>3,
                'title'=>'Hey there!',
                'body'=>'Like our Facebook page to get ' . PAYOUT_FACEBOOK_LIKE . ' bonus coins!',
                'extra'=>print_r($result['data'][0],true)
            );

        } else {
            $userInstance->update_user_facebook_like($userID);
            $userInstance-> user_add_credits($userID,PAYOUT_FACEBOOK_LIKE,0,true);
            $dialogData = array(
                'status'=>1,
                'title'=>'Thank You :)',
                'body'=>'Awesome, thanks for liking our Facebook page, here is ' . PAYOUT_FACEBOOK_LIKE . ' coins! Hit OK to refresh.'
            );
        }




        exit(json_encode($dialogData));

        break;


    case 'fb':
        $userID = $_SESSION['userID'];
        $fbEmail = ($_POST['fbEmail'] == '(null)' || empty($_POST['fbEmail']))?'':$_POST['fbEmail'];
        $fbName = $_POST['fbName'];
        $fbUserID = $_POST['fbUserID'];
        $fbGender = $_POST['fbGender'];
        $fbLocale = $_POST['fbLocale'];
        $fbVerified = $_POST['fbVerified'];
        $fbToken = $_POST['fbToken'];
        UtilityManager::log('Facebook data:' . print_r($_POST,true));

        if(isset($userID) && isset($fbEmail) && isset($fbName) && isset($fbUserID) && isset($fbGender) && isset($fbLocale) && isset($fbToken) && isset($fbVerified))
        {
            $userInstance->update_user_facebook($userID,$fbEmail,$fbName,($fbGender == 'male')?1:2,$fbUserID,(strlen($fbLocale) > 2)?substr($fbLocale,3,2):$fbLocale,$fbToken,$fbVerified);
            exit("Congratulations You have successfully logged into Facebook!");
        } else {
            exit("0");
        }
    break;

    case 'update_email':
        $userID = $_SESSION['userID'];
        $userEmail = $_GET['userEmail'];
        if(!isset($userID)) { print 'You must be logged in to perform this function. If the problem persists, please contact ' . SUPPORT_EMAIL .'.'; die();}
        if(!isset($userEmail)) { print 'No email address specified, please contact support at ' . SUPPORT_EMAIL . '.'; die();}
        if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) { print 'There was an error performing this action. If the problem persists, please contact ' . SUPPORT_EMAIL;die();}
        $userInstance->update_user_email($userID,$userEmail);
        $userHash = $userInstance->set_user_hash($userID,$userEmail);

        include(VIEW_PATH . 'emailTemplateView.php');


        $utilityInstance->send_email(
            $userEmail,
            EMAIL_CONFIRMATION_SUBJECT,
            $BODY_TEXT
        );


        print 'We have sent a link to ' . $userEmail . ', from ' . SUPPORT_EMAIL . '. Please click the link to verify your email. Check your spam folder if you cannot find it.';
        die();



        die();

    break;

    default:
        die();
    break;
}

?>