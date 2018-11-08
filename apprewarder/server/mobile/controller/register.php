<?php
/**
 *  ==================================================
 *  Retrieve user device information and register them
 *  if they are already registered, 'log' them in
 *  ==================================================
 */
switch($controllerFunction)
{
    case 'redir':


        $userNonce = $_GET['n'];

        $file_contents = file_get_contents('php://input');

        //$utilityInstance = new UtilityManager();
        //$utilityInstance->log('this is a test!');
        $xml_raw_data = '<root>' . $utilityInstance->get_xml_value('dict', trim($file_contents)) . '</root>';

        //file_put_contents('raw.txt',$xml_raw_data);
        $xml_clean = preg_replace('/\s+/', '',$xml_raw_data);
        //$utilityInstance->log('OTA clean:' . $xml_clean);
        //file_put_contents('raw.txt', $xml_clean);

        $xml_object = simplexml_load_string($xml_clean);

        for($i = 0; $i < count($xml_object->string); ++$i)
        {
           if(strtolower($xml_object->key[$i]) == 'product') { $device_model = $xml_object->string[$i]; }
           if(strtolower($xml_object->key[$i]) == 'imei') { $device_imei = $xml_object->string[$i]; }
           if(strtolower($xml_object->key[$i]) == 'udid') { $device_id = $xml_object->string[$i]; }
           if(strtolower($xml_object->key[$i]) == 'version') { $device_version = $xml_object->string[$i]; }

        }
        $userNonce = $_GET['n'];
        $_SESSION['deviceIMEI'] = (isset($device_imei))?$device_imei:'';
        $_SESSION['deviceModel'] = (isset($device_model))?$device_model:'';
        $_SESSION['deviceID'] = (isset($device_id))?$device_id:'';
        $_SESSION['deviceVersion'] = (isset($device_version))?$device_version:'';

       $utilityInstance->log('OTA cert submitted. Data:'  .print_r($xml_object,true));
        //file_put_contents('data.txt', print_r($xml_object,true));
        //header("Location: https://10.0.1.3/register/" . rawurlencode($data),TRUE,301);
        $header_info = 'Location: ' .SERVER_PROTOCOL .API_HOST .'/register/?deviceID=' . $device_id . '&deviceModel=' . $device_model . '&deviceIMEI=' . $device_imei . '&deviceVersion=' . $device_version .'&n=' . $userNonce;
        header($header_info,true,301);

        // header('Location: http://10.0.1.3/register/'.rawurlencode($data));
        //http://apprewarder/register/deviceid1234/devicemodel/deviceimei1234/deviceversion1234
        die();
    break;

    case 'login':

        /**
         *  Logs user in based on deviceID and userID. The client sends the user here if it detects if the users is not
         *  logged in so we can setup the proper user vars
         */
        $user_agent = $_GET['ua'];
        $client_version = $_GET['clientVersion'];
        $userInstance->set_user_client_version($client_version);
        $device_id = $_GET['aidid'];//$controllerID;
        $user_id = $_GET['aiuid'];//$controllerData;
        $referrer_id = $_GET['referrerID'];
        $referrer_source = $_GET['referrerSource'];
        $utilityInstance->log('request to login with deviceID:' . $device_id. ' and userID:' . $user_id);
        //if(isset($device_id) && !isset($user_id)) { $user_id = $device_id; } //deviceID and userID are samsies
        if(!isset($device_id) && !isset($user_id) && !isset($user_agent)) { print 'no information provided :('; die(); }

        $_SESSION['userAgent'] = $user_agent;

        /*
        if(isset($referrer_id) && isset($referrer_source)) //this is for iOS only for custom URL schema
        {

            $_r = $referrer_id;
            $referrer_id = $userInstance->get_user_id_by_user_friend_code($_r);

            $userInstance->user_create_referral($user_id,$referrer_id,$referrer_source);
             $utilityInstance->log('User attempting to register referral code via iOS shorturl. referrer_id: ' . $referrer_id . ' referrer_source: ' . $referrer_source);

        }*/

        if($userInstance->load_user($user_id,$device_id))
        {
            //$utilityInstance->log('Loaded user. Session data: '.  print_r($_SESSION,true));

            /*
             *  This means the user is authenticated and processed
             */
            //once they login, send 'em to the main page
            //$_SESSION['currentOfferTypes'] = OFFERS_NON_APPS;//$_SESSION['currentOfferTypes'] = OFFERS_APPS_ALL;
            /*
            if(isset($_SESSION['currentOfferTypes']) && $_SESSION['currentOfferTypes'] == OFFERS_VIDEO)
            {
                $offerURL = '/offer/other/r/';
            } else {
                $offerURL = '/offer/apps/r/';
            }
            */
            $url = SERVER_PROTOCOL . ((MODE !== 'local')?API_HOST:LOCAL_IP);
            $utilityInstance->log('sending user to:' . $url);

            //$client_version


            include_once(CONTROLLER_PATH . 'main.php');
            //header('Location: ' . $url);
            exit();
        }
        else {

            print 'request to login but no matching user id and device id. please contact support@apprewarder.com :-x';exit();
        }

    break;

    case 'email':
        $user_id = intval($_SESSION['userID']);
        $user_email = $_POST['userEmail'];
        if(!$utilityInstance->is_email($user_email)) { print 'You must provide a valid email address.'; die(); }
        if(!isset($user_email) || !isset($user_id)) { print 'You must be logged in to set your email.';die(); }
        $userInstance->update_user_email($user_id,$user_email);
        print 'Settings saved, thank you!'; // ' . $user_email;
        die();
    break;

    case 'new':
        //registering a new user, lets generate a nonce
        $userNonce = UtilityManager::nonce_create(); //5 minutes nonce for registration . '<br>';
        include(VIEW_PATH . 'registerView.php');
        exit();
    break;

    break;

    default:
        /**
         *  registerView.php sends us here with the nonce
         */
        $userNonce = $_GET['n'];
        //print 'nonce: ' . $userNonce . ' request:<pre> ' . print_r($_REQUEST,true);
        //print  (UtilityManager::nonce_is_valid($userNonce))?'true':'false';
        //    exit();
        if(MODE !== 'local') {
            $device_type = new DeviceDetectManager();
            if(!UtilityManager::nonce_is_valid($userNonce) || !$device_type->isAppRewarderClient() || !isset($userNonce)) {
            //$utilityInstance = new UtilityManager();
                $utilityInstance->log('register.php - /403 ERROR DUMP - nonce: ' . $userNonce . ' nonceValid: ' . UtilityManager::nonce_is_valid($userNonce) . ' isAppRewarderClient: ' . $device_type->isAppRewarderClient());
               // print
                //exit();
                header('Location: ' . SERVER_PROTOCOL . API_HOST . '/error/403/');
                exit();
            }
        }

        $user_agent = $_GET['ua'];
        if(!isset($user_agent)) exit('useragent bro?');
        $_SESSION['userAgent'] = $user_agent;


        $client_version = (isset($_GET['clientVersion']))?$_GET['clientVersion']:'';
        $device_id = (isset($_GET['deviceID']))?$_GET['deviceID']:'';
        $device_model = (isset($_GET['deviceModel']))?$_GET['deviceModel']:'';
        $device_imei = (isset($_GET['deviceIMEI']))?$_GET['deviceIMEI']:'';
        $device_version = (isset($_GET['deviceVersion']))?$_GET['deviceVersion']:'';
        $device_mac = (isset($_GET['deviceMAC']))?$_GET['deviceMAC']:'';

        $referrer_id = (isset($_GET['referrer']))?$_GET['referrer']:'';
        $referrer_source = (isset($_GET['referrerSource']))?$_GET['referrerSource']:'';

        if(!isset($device_id))
        {

            header('Location: ' . SERVER_PROTOCOL . API_HOST . '/error/403/');
            //print 'error: this call was not made using the app or there is a severe server issue. please contact support@apprewarder.com';
            $utilityInstance->log('register.php - /403 ERROR  look like either udid,model,imei,or version was not defined so we will not register an account. fack!');

            exit();
        }

        //lets set all the object instance vars via local vars
        $userInstance->set_user_mac_address($device_mac);
        $userInstance->set_user_device_version($device_version);
        $userInstance->set_user_device_id($device_id);
        $userInstance->set_user_device_imei($device_imei);
        $userInstance->set_user_device_model($device_model);
        $userInstance->set_user_platform($userInstance->get_user_platform());
        $userInstance->set_user_client_version($client_version);


        if(MODE =='prod'){
                $userInstance->set_user_ip_address($_SERVER['HTTP_X_FORWARDED_FOR']);
        }else{
                $userInstance->set_user_ip_address((MODE == 'local')?LOCAL_IP:$_SERVER['REMOTE_ADDR']);

        };

        $utilityInstance->log('received request to register user with deviceID:' . $device_id . ' model:' . $device_model . ' imei:' . $device_imei . ' version:' . $device_version. ' client version:' . $client_version);
        //lets see if their login information exists, if not

        //lets grab the user ID via device id, if there is no match, lets create a new account
        $utilityInstance->log('checking to see if device ID ' . $userInstance->userDeviceID . ' maps to a userID first');

        $userID = $userInstance->get_user_id_by_device_id($device_id);
        $deviceID = $userInstance->userDeviceID;
        //$detectType = new DeviceDetectManager();
        //if($detectType->isiOS()) { $userPlatform = 1; } elseif($detectType->isAndroidOS()) { $userPlatform = 2;}else { $userPlatform = 0;}


        $utilityInstance->log('result: ' . $userID);
        if(!$userID)
        {
            $utilityInstance->log('device ID does NOT map. creating account for them.');
            $userInstance->register_user();
            $userID = $userInstance->get_user_id_by_device_id($device_id);
            $userInstance->register_friend_code($userID); //create their friend code

        }

        $_SESSION['userID'] = $userID;


        if(isset($referrer_id))
        {
            $referrer_user_id = $userInstance->get_user_id_by_user_friend_code($referrer_id);
            if($referrer_user_id)
            {
                //$referrer_id = $_SESSION['referralID'];
                $referrer_source = (isset($referrer_source))?$referrer_source:REFERRAL_SOURCE_UNKNOWN;
                $utilityInstance->log('[register.php] referral detected from: ' . $referrer_id);
                $utilityInstance->log('[register.php] generating referral record ' . $referrer_user_id . 'referred ' . $userID);
                if($userInstance->user_create_referral($userID,$referrer_user_id,$referrer_source))
                {
                    $utilityInstance->log('[register.php] referral generated. adding credits: ' . PAYOUT_REFERRAL_REFERRED . ' to '. $userID);

                    //if the referral did not exist and was successfully created, lets credit this user
                    //$userInstance->user_add_credits($userID,intval(PAYOUT_REFERRAL_REFERRED));
                }
                else {
                    $utilityInstance->log('[register.php] referral not generated :(');
                }
            }

        }


        //lets load the user object and session var with the user's information
        if(!$userInstance->load_user($userID,$deviceID))
        {
            print 'there was an error loading user:' . $userID . ' with device id:' . $deviceID;
            $utilityInstance->log('could not properly load user account ' . $userID);
            die();
        }

        $url = SERVER_PROTOCOL .API_HOST;// . '/u=' . $userID;
        $utilityInstance->log('[register.php] registered user ' . $_SESSION['userID'] .', forwarding to: ' . $url);
        header('Location: ' . $url);
        exit();


    break;
}


?>
