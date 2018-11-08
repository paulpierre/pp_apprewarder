<?php
class User extends Database {
    const TABLE_NAME = 'user_account';
    const OFFER_TABLE_NAME = 'user_offers';
    const REWARD_USER_TABLE = 'user_rewards';
    const REFERRAL_TABLE_NAME = 'user_referral';
    const CB_TABLE_NAME = 'user_offer_cb';
    const LOGIN_TABLE_NAME = 'user_login';
    const SYS_ISP_TABLE_NAME = 'sys_isp';
    const SELECTOR_ALL =  '*';
    public $userID;
    public $userLocale;
    public $userEmail;
    public $userDevicePlatform;
    public $userDeviceID;
    public $userDeviceIMEI;
    public $userDeviceModel;
    public $userDeviceVersion;
    public $userAccountCreated;
    public $userAccountModified;
    public $userLastLogin;
    public $userDeviceMAC;
    public $userIP; //the IP they last logged in with
    public $userRegisterIP; //the IP address they registered with
    public $userAccountStatus;
    public $userCredits;
    public $userFacebookID;
    public $userSessionID;
    public $userReferralCount;
    public $userFriendCode;
    public $userCreditsFree;
    public $userCreditsReferral;
    public $userCreditsSum;
    public $userPayout;
    public $userClientVersion;
    public $userName;
    public $userGender;
    public $userFacebookVerified;
    public $userFacebookToken;

    public $didRegister;
    public $didConfirmEmail;
    public $didUnlockAllApps;
    public $isFlagged;
    public $isFlaggedForSuspiciousActivity;
    public $isBanned;
    public $isBannedAndSuspended;
    public $isBannedPermanently;

    public function user_unset_referral()
    {
        $_SESSION['referralID'] = null;
        //$_SESSION['referralFriendCode'] = null;
        $_SESSION['referralSource'] = null;
        unset($_SESSION['referralID']);
        //unset($_SESSION['referralFriendCode']);
        unset($_SESSION['referralSource']);

    }

    public function set_user_credits_free($user_credits_free)
    {
        $this->userCreditsFree = $user_credits_free;
        $_SESSION['userCreditsFree'] = $user_credits_free;
    }

    public function set_user_credits_referral($user_credits_referral)
    {
        $this->userCreditsReferral = $user_credits_referral;
        $_SESSION['userCreditsReferral'] = $user_credits_referral;
    }

    public function set_user_client_version($user_client_version)
    {
        $this->userClientVersion = $user_client_version;
        $_SESSION['userClientVersion'] = $user_client_version;
    }

    public function set_user_credits_sum($user_credits_sum)
    {
        $this->userCreditsSum = $user_credits_sum;
        $_SESSION['userCreditsSum'] = $user_credits_sum;
    }

    public function set_user_name($user_name)
    {
        $this->userName = $user_name;
        $_SESSION['userName'] = $user_name;
    }
    public function set_user_gender($user_gender)
    {
        $this->userGender = $user_gender;
        $_SESSION['userGender'] = $user_gender;
    }

    public function set_user_facebook_id($user_facebook_id)
    {
        $this->userFacebookID = $user_facebook_id;
        $_SESSION['userFacebookID'] = $user_facebook_id;
    }

    public function set_user_facebook_token($user_facebook_token)
    {
        $this->userFacebookToken = $user_facebook_token;
        $_SESSION['userFacebookToken'] = $user_facebook_token;
    }
    public function set_user_facebook_verified($user_facebook_verified)
    {
        $this->userFacebookVerified = $user_facebook_verified;
        $_SESSION['userFacebookVerified'] = $user_facebook_verified;
    }

    public function set_user_payout($user_payout)
    {
        $this->userPayout = $user_payout;
        $_SESSION['userPayout'] = $user_payout;
    }

    public function set_user_referral_count($referral_count)
    {
        $this->userReferralCount = $referral_count;
        $_SESSION['userReferralCount'] = $referral_count;
    }

    public function set_user_friend_code($user_friend_code)
    {

        $this->userFriendCode = $user_friend_code;
        $_SESSION['userFriendCode'] = $user_friend_code;
    }


    public function set_user_device_id($device_id)
    {
        $this->userDeviceID = $device_id;
        $_SESSION['deviceID'] = $device_id;
    }

    public function set_user_device_imei($device_imei)
    {
        $this->userDeviceIMEI = $device_imei;
        $_SESSION['deviceIMEI'] = $device_imei;
    }

    public function set_user_device_model($device_model)
    {
        $this->userDeviceModel = $device_model;
        $_SESSION['deviceModel'] = $device_model;
    }

    public function set_user_device_version($device_version)
    {
        $this->userDeviceVersion = $device_version;
        $_SESSION['deviceVersion'] = $device_version;
    }

    public function set_user_platform($user_device_platform)
    {
        $this->userDevicePlatform = $user_device_platform;
        $_SESSION['devicePlatform'] = $user_device_platform;

    }

    public function set_user_id($user_id)
    {
        $this->userID = $user_id;
        $_SESSION['userID'] = $user_id;
    }

    public function set_user_locale($user_locale)
    {
        $this->userLocale = $user_locale;
        $_SESSION['userLocale'] = $user_locale;
    }

    public function set_user_mac_address($user_mac_address)
    {
        $this->userDeviceMAC = $user_mac_address;
        $_SESSION['userDeviceMAC'] = $user_mac_address;
    }

    public function set_user_credits($user_credits)
    {
        $this->userCredits = $user_credits;
        $_SESSION['userCredits'] = $user_credits;
    }

    public function set_user_account_status()
    {
        $registerField = 0;
        $emailField = 0;
        $appsLockedField = 0;
        $banField = 0;

        if($this->didUnlockAllApps) $appsLockedField = ACCOUNT_APPS_UNLOCKED; else $appsLockedField = ACCOUNT_APPS_LOCKED;

        if($this->didRegister) $registerField = ACCOUNT_REGISTERED; else $registerField = ACCOUNT_UNREGISTERED;
        if($this->didConfirmEmail) $emailField = ACCOUNT_EMAIL_CONFIRMED; else $emailField = ACCOUNT_EMAIL_UNCONFIRMED;

        if($this->isBanned)
        {
            if($this->isBannedAndSuspended) $banField = ACCOUNT_BANNED_SUSPENDED;
            elseif ($this->isBannedPermanently) $banField = ACCOUNT_BANNED_PERMANENT_BAN;
        }

        if($this->isFlagged)
        {
            if($this->isFlaggedForSuspiciousActivity) $banField = ACCOUNT_WARN_SUSPICIOUS_ACTIVITY; else $banField = ACCOUNT_WARN_GENERAL;
        }

        $accountStatus = $registerField . $emailField . $appsLockedField . $banField;

        $this->userAccountStatus = $accountStatus;
        $_SESSION['userAccountStatus'] = $accountStatus;
        $this->store_user_account_status($_SESSION['userID']);
        $this->process_user_account_status();
    }


    public function set_user_account_created($user_account_created)
    {
        $this->userAccountCreated = $user_account_created;
        $_SESSION['userAccountCreated'] = $user_account_created;
    }

    public function set_user_account_modified($user_account_modified)
    {
        $this->userAccountModified = $user_account_modified;
        $_SESSION['userAccountModified'] = $user_account_modified;
    }

    public function set_user_ip_address($user_ip_address)
    {
        $this->userIP = $user_ip_address;
    }

    public function set_user_email($user_email)
    {
        $this->userEmail = $user_email;
        $_SESSION['userEmail'] = $user_email;
    }



    function process_user_account_status() {
        //first we update and flush to local variables

        $this->userAccountStatus = $_SESSION['userAccountStatus'];
                //and then we process
        $accountArray = str_split($this->userAccountStatus);
        /*
         *  registration status
         */

        switch($accountArray[0])
        {
            case ACCOUNT_REGISTERED:
                $this->didRegister = true;
                break;

            case ACCOUNT_UNREGISTERED:
            default:
                $this->didRegister = false;
                break;
        }

        /*
         * email status
         */

        switch($accountArray[1])
        {
            case ACCOUNT_EMAIL_CONFIRMED:
                $this->didConfirmEmail = true;
                break;

            default:
            case ACCOUNT_EMAIL_UNCONFIRMED:
                $this->didConfirmEmail = false;
                break;
        }

        /*
         *  app unlock status
         */

        switch($accountArray[2])
        {
            case ACCOUNT_APPS_UNLOCKED:
                $this->didUnlockAllApps = true;
                break;

            default:
            case ACCOUNT_APPS_LOCKED:
                $this->didUnlockAllApps = false;
                break;
        }

        if(intval($accountArray[3]) >= ACCOUNT_BANNED_SUSPENDED) {
            $this->isBanned = true;
        } else { $this->isBanned = false;}

        switch($accountArray[3])
        {
            case ACCOUNT_WARN_GENERAL:
                $this->isFlagged = true;
                break;

            case ACCOUNT_WARN_SUSPICIOUS_ACTIVITY:
                $this->isFlaggedForSuspiciousActivity = true;
                $this->isFlagged = true;
                break;

            case ACCOUNT_BANNED_SUSPENDED:
                $this->isBannedAndSuspended = true;
                break;

            case ACCOUNT_BANNED_PERMANENT_BAN:
                $this->isBannedPermanently = true;
                break;

            default:
                $this->isFlagged = false;
                $this->isFlaggedForSuspiciousActivity = false;
                $this->isBannedAndSuspended = false;
                $this->isBannedPermanently = false;
                break;
        }
    }


    public function store_user_account_status($user_id)
    {

        //$this->set_user_account_status();
        //$user_account_status = $this->get_user_account_status;
        //lets update
        $db_columns = array(
            'user_tmodified'=>time(),
            'user_account_status'=>$this->userAccountStatus,
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);

        //$this->set_user_account_status();

    }


    public function get_user_locale($ip_address)
    {
        /*
        if(isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) return $_SERVER["HTTP_ACCEPT_LANGUAGE"]; else return 'XX';
        //return 'US';
        */
        if(MODE == 'local') return 'US';
        $geoip = Net_GeoIP::getInstance(GEO_DATA_PATH);

        // $ipaddress = '58.187.75.152'; // Yahoo!
        $location = $geoip->lookupCountryCode($ip_address);
        return (isset($location))?$location:false;
    }

    public function get_expired_offers($time_frame) //TODO: add a default time here
    {
//
        $n =  intval(time() - $time_frame);
        $q = 'SELECT id, offer_name, offer_tcreate FROM user_offers WHERE offer_tcreate <= ' . $n .' AND offer_status=0';
        $result = $this->db_query($q);
        //return intval($result[0]['sum']);
        if(empty($result)) return false;
        return $result;
    }


    public function get_completed_offers($user_id,$offer_network_id=9) //9=hasoffers
    {
        if(!is_numeric($user_id)) return false;
        $db_columns = array('offer_id');
        //$db_conditions = array('user_id'=>$user_id,'offer_network_id'=>intval($offer_network_id),'offer_status'=>1);
        $q = 'SELECT offer_id FROM user_offers WHERE user_id="' . $user_id .'" AND offer_network_id=' . intval($offer_network_id) .' AND (offer_status=1 OR offer_status=5)';
        $result = $this->db_query($q);

        //$result = $this->db_retrieve(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result;
    }

    public function get_pending_offers($user_id) //9=hasoffers
    {
        if(!is_numeric($user_id)) return false;
        $db_columns = array(
            'offer_id',
            'offer_payout',
            'offer_referral_payout',
            'offer_cost',
            'offer_name',
            'offer_image_url',
            'offer_tcreate'
        );
        $db_conditions = array('user_id'=>$user_id,'offer_status'=>0);
        $result = $this->db_retrieve(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,null,false);

        $_SESSION['pendingOffersCount'] = count($result);
        if(!$result) return false;
        return $result;
    }

    public function user_exists($user_id)
    {
        if(!is_numeric($user_id)) return false;
        $db_columns = array('user_id');
        $db_conditions = array('user_id'=>$user_id);

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result[0]['user_id'];
    }

    public function get_user_id_by_device_id($device_id)
    {
        $db_columns = array('user_id');
        $db_conditions = array('user_device_id'=>$device_id);

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result[0]['user_id'];
    }

    public function get_user_id_by_user_friend_code($user_friend_code)
    {
        $db_columns = array('user_id');
        $db_conditions = array('user_friend_code'=>strtolower($user_friend_code));

        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return $result[0]['user_id'];
    }

    public function get_user_platform()
    {
        $detect_type = new DeviceDetectManager();
        //$detect_type->userAgent = $_SESSION['userAgent'];
        if((MODE == 'local') && $detect_type->isiOS()) $this->set_user_platform(PLATFORM_IPHONE);


        //if the session variable is set, return it
        if(isset($_SESSION['devicePlatform']) && intval($_SESSION['devicePlatform']) > 1 && isset($_SESSION['userAgent']))  {
            $this->set_user_platform(intval($_SESSION['devicePlatform']));
        }
        //if not reset everything
            else {

            if(isset($_SESSION['userAgent'])) $detect_type->setUserAgent($_SESSION['userAgent']);
            /*
            elseif(MODE == 'local') {
                $detect_type->setUserAgent($_SERVER['HTTP_USER_AGENT']);
                //UtilityManager::log('OK HERE:' . $detect_type->userAgent);
            }*/
            if($detect_type->isiPad()){
                $this->set_user_platform(PLATFORM_IPAD);
            } elseif($detect_type->isiPod()) {
                $this->set_user_platform(PLATFORM_IPOD);
            } elseif ($detect_type->isiPhone()){
                $this->set_user_platform(PLATFORM_IPHONE);
            } elseif($detect_type->isAndroidOS()) {
                $this->set_user_platform(PLATFORM_ANDROID);
            } elseif($detect_type->isiOS()) {
                $this->set_user_platform(PLATFORM_IOS);
            }else {
                return false;
            }
        }
        //$this->set_user_platform(intval($_SESSION['devicePlatform']));
        return $this->userDevicePlatform;
    }

    public function isiOS() {
        if($this->get_user_platform() == PLATFORM_IPAD || $this->get_user_platform() == PLATFORM_IPHONE || $this->get_user_platform() == PLATFORM_IPOD || $this->get_user_platform() == PLATFORM_IOS) return true; else return false;
    }

    public function isAndroid() {
        if($this->get_user_platform() == PLATFORM_ANDROID) return true; else return false;
    }


    public function user_add_credits($user_id,$credits,$referral_credits=0,$free_credits=false)
    {
        //TODO: mysqli::escape_string( lets escape strings!!
        if(!is_numeric($credits) || !is_numeric($referral_credits)) return false; //if we're not given an integer value lets bomb out

        $q='UPDATE user_account SET user_credits=user_credits+' . $credits .', user_credits_sum=user_credits_sum+' . $credits. (($free_credits)?(', user_credits_free=user_credits_free + '.$credits):'') .' , user_tmodified="' . time() .'" WHERE user_id="' . $user_id.'"';
        //print $q;
        $result = $this->db_query($q);


        $db_columns = array(
            'user_id',
            'user_credits',
            'user_credits_sum'
        );

        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        //print '<pre>' . print_r($result,true) .'</pre>';

        $user_credits_sum = intval($result[0]['user_credits_sum']);
        $user_credits = intval($result[0]['user_credits']);

        if($referral_credits > 0 && $user_credits_sum >= REFERRAL_PAYOUT_MIN_BONUS && !$free_credits) {
            //lets update any relevant referral credits for really only two cases
            // 1. User referral credits to anyone who referred me (i.e. referral revshare)
            // 2. Credit anyone who referred me for the one-time referral bonus
            $referralData = $this->user_credit_referral($user_id,$referral_credits);
            if($referralData)
            {
                $creditResults = Array(
                    'referral_user_id'=>$referralData['referral_user_id'],
                    'referral_credits'=>$referralData['referral_credits'],
                    'user_credits'=>$user_credits_sum
                );
            } else {
                $creditResults = Array(
                    'user_credits'=>$user_credits //$user_credits_sum
                );
            }

        } else {
            $creditResults = Array(
                'user_credits'=>$user_credits //$user_credits_sum
            );
        }

        return $creditResults;
        /*

        //lets grab user's balances
        $db_columns = array(
            'user_id',
            'user_credits',
            'user_credits_sum'
        );*/

        //detect whether what is being passed is a UDID or an internal userID record
        //$db_conditions = (is_numeric($user_id))?array('user_id'=>$user_id):array('user_device_id'=>$user_id); //allows us to use both user_id and user_device_id
        //$result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        /*
        if($result) {

            $user_credits = (isset($result[0]['user_credits']))?intval($result[0]['user_credits']):0;
            $user_credits_sum = (isset($result[0]['user_credits_sum']))?intval($result[0]['user_credits_sum']):0;

            //in case we used the UDID to reference user ID, lets make sure we use the internal ID
            if (isset($result[0]['user_id'])) $user_id = intval($result[0]['user_id']);
            //lets update the user record with the new value of credits
            $credits_new = $user_credits + intval($credits);
            $credits_sum_new = $user_credits_sum + intval($credits_new);


            if($referral_credits > 0 && $credits_sum_new >= REFERRAL_PAYOUT_MIN_BONUS) {
                //lets update any relevant referral credits for really only two cases
                // 1. User referral credits to anyone who referred me (i.e. referral revshare)
                // 2. Credit anyone who referred me for the one-time referral bonus
                $referralData = $this->user_credit_referral($user_id,$credits_new,$referral_credits);
                if($referralData)
                {
                    $creditResults = Array(
                        'referral_user_id'=>$referralData['referral_user_id'],
                        'referral_credits'=>$referralData['referral_credits'],
                        'user_credits'=>$credits_new
                    );
                }
            } else {
                $creditResults = Array(
                    'user_credits'=>$credits_new
                );
            }



            //lets update
            $db_columns = array(
                'user_tmodified'=>time(),
                'user_credits'=>$credits_new,
                'user_credits_sum'=>$credits_sum_new
            );
            $db_conditions = array('user_id'=>$user_id);
            $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);




            return $creditResults;
        } else { return false;} //failure in finding the user
        */
    }

    public function get_user_referrals($user_id)
    {

    }

    public function get_user_credits($user_id)
    {
        $db_columns = array('user_credits');
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) return false;
        return intval($result[0]['user_credits']);
    }

    public function user_deduct_credits($user_id,$credits)
    {
        if(!is_numeric($credits)) return false; //if we're not given an integer value lets bomb out
        $db_columns = array(
            'user_id',
            'user_credits',
            'user_payout'
        );

        //detect whether what is being passed is a UDID or an internal userID record
        $db_conditions = (is_numeric($user_id))?array('user_id'=>$user_id):array('user_device_id'=>$user_id); //allows us to use both user_id and user_device_id
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if($result) {
            //if the offer has already been clicked by the user, lets just update the tmodified and count
            //$user_id = $result[0]['id'];
            $user_credits = (isset($result[0]['user_credits']))?intval($result[0]['user_credits']):0;
            $user_payout = (isset($result[0]['user_payout']))?intval($result[0]['user_payout']):0;

            //in case we used the UDID to reference user ID, lets make sure we use the internal ID
            if (isset($result[0]['user_id'])) $user_id = intval($result[0]['user_id']);

            //lets update the user record with the new value of credits
            $credits_new = ($user_credits - intval($credits) <= 0)?0:$user_credits - intval($credits); //update of credit balance
            $payout_new = ($user_payout + intval($credits)); //lets update total credits paid to user
            $db_columns = array(
                'user_tmodified'=>time(),
                'user_credits'=>$credits_new,
                'user_payout' =>$payout_new
            );
            $db_conditions = array('user_id'=>$user_id);
            $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);

            return $credits_new;
        } else { return false;} //failure in finding the user
    }

    public function get_user_spend_history_sum($user_id)
    {
        $q = 'SELECT sum(reward_user_cost) as sum FROM user_rewards WHERE reward_user_status=1 AND user_id =' . $user_id;
        $result = $this->db_query($q);
        return intval($result[0]['sum']);
    }

    public function get_user_claims_sum($user_id)
    {
        $q = 'SELECT sum(reward_user_cost) as sum FROM user_rewards WHERE reward_user_status=0 AND user_id =' . $user_id;
        $result = $this->db_query($q);
        return $result[0]['sum'];
    }


    public function get_sys_isp_all_list()
    {
        $q = 'SELECT * from sys_isp';
        $result = $this->db_query($q);
        return $result;
    }
    public function get_sys_isp_unresolved_list()
    {
        $q = 'SELECT * from sys_isp WHERE isp_status=0';
        $result = $this->db_query($q);
        return $result;
    }

    public function get_sys_isp_denied_list()
    {
        $q = 'SELECT * from sys_isp WHERE isp_status=5';
        $result = $this->db_query($q);
        return $result;
    }

    public function get_sys_isp_accepted_list()
    {
        $q = 'SELECT * from sys_isp WHERE isp_status=1';
        $result = $this->db_query($q);
        return $result;
    }

    public function update_sys_ip($o)
    {
        $ispName = $o['ispName'];
        $ispID = $o['ispID'];
        $ispCountry = isset($o['ispCountry'])?$o['ispCountry']:'';
        $ispStatus =$o['ispStatus'];
        if(!isset($ispName) || !isset($ispID) || !isset($ispStatus)) return false;
        $db_columns = array(
            'isp_tmodified'=>time(),
            'isp_name'=>$ispName,
            'isp_country'=>$ispCountry,
            'isp_status'=>$ispStatus
        );
        $db_conditions = array('id'=>$ispID);
        $result = $this->db_update(self::SYS_ISP_TABLE_NAME,$db_columns,$db_conditions,false);
        if(!empty($result)) return true; else return false;

    }

    public function get_user_credits_history_sum($user_id)
    {
        $offerInstance = new OfferManager();
        $networkIDs = array(
            'aarki',
            'adscend',
            'adaction',
            'ksix'
        );
        $userCreditsSum = 0;
        foreach($networkIDs as $networkID)
        {
            $q = 'SELECT sum(offer_network_payout) as sum FROM user_offer_cb WHERE offer_network=\'' . $networkID . '\' AND user_id =' . $user_id;
            $result = $this->db_query($q);
            $offerPayout = intval($result[0]['sum']);
            $payoutConversion = $offerInstance->offer_payout_conversion($offerPayout,$networkID);
            //lets only get the user payout and not the referral payout
            if(intval($payoutConversion['userPayout']) > 0) $userCreditsSum = $userCreditsSum + $payoutConversion['userPayout'] ;
        }
        return $userCreditsSum;
    }

    public function user_did_refer($user_id)
    {
            $db_columns = array('user_id');
            $db_conditions = array(
                'referral_referred_user_id'=>$user_id
            );
            $result = $this->db_retrieve(self::REFERRAL_TABLE_NAME,$db_columns,$db_conditions,null,false);
            if(empty($result))
            {
                return false;
            } else return true;
    }

    public function user_did_unlock_offers($user_id)
    {
        global $lockedOffers;
        if(count($lockedOffers) == 0) return true;
        $items = '';
        $i=0;
        foreach($lockedOffers as $lockedOffer)
        {
         $i++;
         $items .= (count($lockedOffers) -1 > $i)?'offer_id=\''.$lockedOffer.'\' OR ':'offer_id=\''.$lockedOffer.'\'';
        }
        $q = 'SELECT offer_id, offer_status FROM user_offers WHERE ('.$items.') AND offer_status=1 AND user_id =' . $user_id;
        //print $q . '</pre>';
        $result = $this->db_query($q);
        //print_r($resrult);die();
        if(count($result) < count($lockedOffers)) return false; //this means they didnt unlock all the offers
        return true;


    }

    public function user_referral_exists($user_id,$referrer_id)
    {
       //referrer_id = the person getting credit for referring $user_id
       //$user_id = the person getting referred (new user) by referrer_id
        $db_columns = array('user_id');
        $db_conditions = array(
            'user_id'=>$referrer_id,
            'referral_referred_user_id'=>$user_id
        );
        $result = $this->db_retrieve(self::REFERRAL_TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result))
        {
            return false;
        } else return true;

    }

    public function user_refresh_data()
    {
        if(isset($_SESSION['userID']))
        {
            $db_columns = array(
                'user_credits',
                'user_referral_count',
                'user_credits_sum',
                'user_credits_free',
                'user_payout',
                'user_account_status',
                'user_credits_referral',
                'user_email',
                'user_gender',
                'user_facebook_id',
                'user_facebook_verified',
                'user_locale'

            );
            $db_conditions = array(
                'user_id'=>$_SESSION['userID']
            );
            $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
            if(empty($result))
            {
                return false;
            } else
            {


                $this->set_user_referral_count($result[0]['user_referral_count']);
                $this->set_user_credits(intval($result[0]['user_credits']));
                $this->set_user_credits_sum($result[0]['user_credits_sum']);
                $this->set_user_credits_free($result[0]['user_credits_free']);
                $this->set_user_payout($result[0]['user_payout']);
                $this->set_user_credits_referral($result[0]['user_credits_referral']);
                $this->set_user_email($result[0]['user_email']);
                $this->set_user_gender($result[0]['user_gender']);
                $this->set_user_facebook_id($result[0]['user_facebook_id']);
                $this->set_user_facebook_verified($result[0]['user_facebook_verified']);
                $this->set_user_locale($result[0]['user_locale']);


                $this->userAccountStatus  = $result[0]['user_account_status'];
                $_SESSION['userAccountStatus'] = $result[0]['user_account_status'];
                $this->process_user_account_status();
            };

        }

    }

    public function user_credit_referral($user_id,$referral_credits=0)
    {
        /* check to see if the $user_id is eligible to payout bonus referral credit to their
         * referrer and where $credits is their current credit balance
         * referral_status =>  0=referral made but not qualified 1=referral qualified and
         * referring user was credited
         * NEW: alsos to check to see if my referrer is eligble for payout if there is a referral payout!
         *  */

        /* ================================
         * REFERRAL DOWNLOAD/REVSHARE CHECK
         * ================================
         */

        //lets make sure there are referral credits eligible to begin with
        if(is_numeric($referral_credits) && $referral_credits > 0)
        {
            //lets find the user who referred me and verify they are eligible for payment
            $db_columns = array('id','referral_status','referral_referred_user_id','user_id');

            //lets see if this user was referred by another user
            $db_conditions = array(
                'referral_referred_user_id'=>$user_id,
                //'referral_referred_user_id !='=>$user_id
            );
            $result = $this->db_retrieve(self::REFERRAL_TABLE_NAME,$db_columns,$db_conditions,null,false);
            if($result)
            {
                //so it looks like we have our user, there should definitely be no more than one
                $referrer_id = $result[0]['user_id'];
                //lets credit them the referral amount for this download!
                $this->user_add_credits($referrer_id,$referral_credits);

                //lets update the referral credits sum for the referrer
                //lets retrieve the *referring user's* referral count in the user_account table
                //and also update the sum of the referral credits

                $this->db_query('UPDATE user_account SET user_credits_referral = user_credits_referral+' . $referral_credits .', user_tmodified=' . time() .' WHERE user_id="' . $referrer_id.'"');
                /*

                $db_columns = array(
                    'user_credits_referral'
                );
                $db_conditions = array(
                    'user_id'=>$referrer_id,
                );
                //lets increase the referring user's referral count
                $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
                $referralCredits = intval($result[0]['user_credits_referral']) + $referral_credits;
                $db_columns = array(
                    'user_tmodified'=>time(),
                    'user_credits_referral'=>$referralCredits
                );

                $db_conditions = array('user_id'=>$referrer_id);
                $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);*/
                //if($result)
               // {
                    return Array(
                        'referral_user_id'=>$referrer_id, //who got paid for the referral
                        'referral_credits'=>$referral_credits
                    );
                //} else return false;

            }

        }
        return false;

        //the rest is deprecated

        /* =============================
         * SIGN UP BONUS REFERRAL CHECK
         * =============================
         */


        //no need to make the referral check if the user doesn't have enough credits
        if(intval($credits) < REFERRAL_MINIMUM_BALANCE || REFERRAL_MINIMUM_BALANCE <= 0) return false;
        $db_columns = array('id','referral_status','referral_referred_user_id','user_id');

        //lets see if this user was referred by another user already
        $db_conditions = array(
            'referral_referred_user_id'=>$user_id, //check if i am in there
            'referral_status'=>0
            //'referral_referred_user_id !='=>$user_id
        );
        $result = $this->db_retrieve(self::REFERRAL_TABLE_NAME,$db_columns,$db_conditions,null,false);

        //if so, since this user meets the min balance, lets pay the referring user (user_id) out
        if($result)
        {

                //lets credit the referrer their bonus
                $referrer_id = $result[0]['user_id']; //the person being credited
                $record_id = $result[0]['id'];

                $this->user_add_credits($referrer_id,PAYOUT_REFERRAL_REFERRER);

                //lets mark the referral as credited by making the status = 1
                $db_columns = array(
                    'referral_tmodified'=>time(),
                    'referral_status'=>1

                );
                $db_conditions = array('id'=>$record_id);
                $result = $this->db_update(self::REFERRAL_TABLE_NAME,$db_columns,$db_conditions,false);

                /*
                 *  INCREMENT REFERRERS USER REFERRAL COUNT
                 */


                //lets retrieve the *referring user's* referral count in the user_account table
                //and also update the sum of the referral credits
                $db_columns = array(
                    'user_id',
                    'user_referral_count',
                    'user_credits_referral'
                );
                $db_conditions = array(
                    'user_id'=>$referrer_id,
                );
                //lets increase the referring user's referral count
                $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
                $referralCount = intval($result[0]['user_referral_count']);
                $referralCredits = intval($result[0]['user_credits_referral']) + PAYOUT_REFERRAL_REFERRER;

                $referralCount = intval($referralCount) + 1; //lets increment that count and update it on their record
                $db_columns = array(
                    'user_referral_count'=>$referralCount,
                    'user_tmodified'=>time(),
                    'user_credits_referral'=>$referralCredits
                );

                $db_conditions = array('user_id'=>$referrer_id);
                $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);

                return true;
        } else
        {
            //print 'no results';
            return false;
        }

    }

    public function user_create_referral($user_id,$referrer_id,$referralSource = REFERRAL_SOURCE_UNKNOWN)
    {
        //if(!isset($_SESSION['referralSource'])){ $referralSource = REFERRAL_SOURCE_UNKNOWN; }
        //else {$referralSource = $_SESSION['referralSource'];}
        $utilityInstance = new UtilityManager();
        //lets make sure we're not creating duplicate referrals
        //$referrer_id = $this->get_user_id_by_user_friend_code($referrer_friend_code);
        //if(empty($referrer_friend_code)) { $utilityInstance->log('Referral error: attempted to attribute registration to friend code ' . $referrer_friend_code . ' from user: '. $user_id . ' but it does NOT exist'); }
        if(!$this->user_referral_exists($user_id,$referrer_id)) {
            $utilityInstance->log('referral does not exist, creating record');
            //if the user has not been referred already, lets create the record
            $db_columns = array(
                'user_id'=>$referrer_id,
                'referral_referred_user_id'=>$user_id,
                'referral_tcreate'=>time(),
                'referral_tmodified'=>time(),
                'referral_status'=>1,
                'referral_source'=>$referralSource
            );
            $result = $this->db_create(self::REFERRAL_TABLE_NAME,$db_columns);//_create(self::OFFER_TABLE_NAME,db_columns);
            $this->user_unset_referral();

            //lets increase the referral count of the user doing the referring $referrer_id
            $this->db_query('UPDATE user_account SET user_referral_count = user_referral_count+1 WHERE user_id="' . $referrer_id.'"');

            //lets credit 50 pts
            if(REFERRAL_SIGNUP_BONUS > 0) {
                $this->user_add_credits($user_id,REFERRAL_SIGNUP_BONUS,0,true);
                //user_add_credits($user_id,$credits,$referral_credits=0,$free_credits=false)
            }



            return true;

        } else {
            $utilityInstance->log('referral exists');

            return false;} //failure in finding the user OR the referral exists already
    }

    //this function is called by the callback verification
    public function user_credit_offer($offerData) //$user_id,$offer_payout,$network_name,$network_cb_id=0,$offer_id = 0
    {

        $offerID = $offerData['offer_id'];
        $userID = intval($offerData['user_id']);
        /*
        $db_columns = array('offer_status','offer_id');
        $db_conditions = array('offer_status'=>1,'offer_id'=>$offerID,'user_id'=>$userID);
        $result = $this->db_retrieve(self::OFFER_TABLE_NAME,$db_columns,$db_conditions);
        */

        $q = "SELECT offer_status,offer_id FROM user_offers WHERE (offer_status=1 OR offer_status=5) AND user_id=" . $userID . " AND offer_id='" . $offerID ."'";
        $result = $this->db_query($q);

        if(!empty($result))
        {
            return false;
            //if we're here, it means someone is attempting a complete post back.. could mean hacking.
            //right now we just check the offer table for the flag to be true. we could go further..that said
            //TODO: flag transaction ID for hasoffers and possibly work some NONCE magic with postbacks
        }


        $result = $this->user_add_credits($offerData['user_id'],$offerData['offer_payout'],$offerData['referral_payout']);
        $credits_new = $result['user_credits'];
        //if the credits were successfully added to the user's account then

        if($credits_new)
        {

            /**
             *   'referral_user_id'=>$referralData['referral_user_id'],
            'referral_credits'=>$referralData['referral_credits'],
            'user_credits'=>$credits_new
             */
            $this->set_user_credits($credits_new['user_credits']); //lets update the users credit balance in the session
            //lets update the callback offer records which is separate from offers table
            $db_columns = array(
                'user_id'=>$userID,
                'offer_network'=>$offerData['network_name'],
                'network_cb_id' =>$offerData['network_cb_id'],
                'offer_tcreate'=>time(),
                'offer_payout'=>$offerData['offer_payout'],
                'offer_referral_payout'=>$credits_new['referral_credits'],
                'offer_referral_user_id'=>$credits_new['referral_user_id'],
                'offer_network_payout'=>$offerData['network_payout'],
                'offer_id'=>$offerID
            );
            //$db_conditions = array('user_id'=>$user_id);
            $result = $this->db_create(self::CB_TABLE_NAME,$db_columns);//_create(self::OFFER_TABLE_NAME,$db_columns);

            //if there is an offerID provided, lets mark it in the offer DB

            $db_columns = array(
                'offer_tmodified'=>time(),
                'offer_status'=>1
            );
            $db_conditions = array('offer_id'=>$offerID,'user_id'=>$userID);
            $result = $this->db_update(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,false);

            if(!empty($result)) { return true; } else {return false;}
        } else { return false;}
    }

    public function get_user_history($user_id)
    {
        /**
         *  Lets grab user download activity as well as referral activity and status
         */
        /*
        $db_columns = array('reward_id','reward_user_cost','reward_user_payout','reward_user_status','reward_user_tcreate','reward_user_tmodified as tmodified');
        $db_conditions = array(
            'user_id'=>$user_id,
            'reward_user_payout'
            //'reward_status'=>1
        );
        */

        $q = "SELECT user_rewards.id, sys_rewards.reward_name, sys_rewards.reward_payout as reward_payout,sys_rewards.reward_cost as reward_cost,sys_rewards.reward_source_id as reward_source_id, user_rewards.reward_user_status,user_rewards.reward_user_tmodified as tmodified FROM sys_rewards LEFT JOIN user_rewards ON sys_rewards.reward_id = user_rewards.reward_id WHERE user_id =" . $user_id;
        $rewardHistory = $this->db_query($q);
        $_SESSION['rewardsCount'] = count($rewardHistory);


        $db_columns = array('user_id','offer_id','offer_payout','offer_cost','offer_name','offer_image_url','offer_status','offer_tmodified as tmodified');
        $db_conditions = array(
            'user_id'=>$user_id
        );

        $downloadHistory = $this->db_retrieve(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,null,false);
        $_SESSION['offersCount'] = count($downloadHistory);


        $db_columns = array('user_id','referral_referred_user_id','referral_source','referral_tcreate','referral_tmodified as tmodified','referral_status');
        $db_conditions = array(
            'user_id'=>$user_id,
        );
        $referralHistory = $this->db_retrieve(self::REFERRAL_TABLE_NAME,$db_columns,$db_conditions,null,false);
        $_SESSION['referralCount'] = count($referralHistory);

        $userHistory = array_merge((array)$downloadHistory,(array) $referralHistory,(array) $rewardHistory);


        return $userHistory;

    }



    public function user_update_offer($offerData)//$offer_id,$offer_payout,$offer_cost,$offer_network,$offer_network_id=0,$offer_name,$offer_image_url)
    {

        if(!isset($_SESSION['userID'])) return false;

        //lets see if the user has clicked on this app already
        $db_columns = array('id','offer_click_count');
        $db_conditions = array('offer_id'=>$offerData['offer_id'],'user_id'=>$_SESSION['userID']);
        $result = $this->db_retrieve(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(!empty($result)) {
        //if the offer has already been clicked by the user, lets just update the tmodified and count
            $record_id = $result[0]['id'];
            $click_count = intval($result[0]['offer_click_count']);
            $click_count++;
            $db_columns = array(
                'offer_tmodified'=>time(),
                'offer_click_count'=>$click_count,
                'user_ip'=>$offerData['user_ip']
            );
            $db_conditions = array('id'=>$record_id);
            $result = $this->db_update(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,false);
            return $click_count;
        }

        //if the record of downloading the offer does not exist, lets download!
        $db_columns = array(
            'offer_id'=>$offerData['offer_id'],
            'offer_payout'=>$offerData['offer_payout'],
            'user_id'=>$_SESSION['userID'],
            'offer_status'=>0,
            'offer_click_count'=>1,
            'offer_tcreate'=>time(),
            'offer_tmodified'=>time(),
            'offer_referral_payout'=>$offerData['offer_referral_payout'],
            'offer_cost'=>$offerData['offer_cost'],
            'offer_network_id'=>$offerData['offer_network_id'],
            'offer_network'=>$offerData['offer_network'],
            'offer_network_payout'=>$offerData['offer_network_payout'],
            'offer_name'=>$offerData['offer_name'],
            'offer_image_url'=>$offerData['offer_image_url'],
            'offer_url'=>$offerData['offer_url'],
            'user_ip'=>$offerData['user_ip']
        );

        $result = $this->db_create(self::OFFER_TABLE_NAME,$db_columns);
        if(isset($result)) {return true;}
        else { return false;}

    }

    public function register_user()
    {
        global $lockedOffers;
        $this->set_user_locale($this->get_user_locale($this->userIP));
        $user_credits = USER_DEFAULT_CREDITS;

        //$user_platform = intval(constant($this::get_user_platform()));//constant($this->get_user_platform());

        switch($this->get_user_platform())
        {
            case PLATFORM_IPAD:case PLATFORM_IPHONE:case PLATFORM_IPOD:case PLATFORM_IOS:
                if($lockedOffers > 0) {
                    $this->userAccountStatus = ACCOUNT_REGISTERED  . ACCOUNT_EMAIL_UNCONFIRMED . ACCOUNT_APPS_LOCKED . ACCOUNT_OK;
                } else $this->userAccountStatus = ACCOUNT_REGISTERED  . ACCOUNT_EMAIL_UNCONFIRMED . ACCOUNT_APPS_UNLOCKED . ACCOUNT_OK;
            break;

            case PLATFORM_ANDROID:
            default:
                $this->userAccountStatus = ACCOUNT_REGISTERED  . ACCOUNT_EMAIL_UNCONFIRMED . ACCOUNT_APPS_UNLOCKED . ACCOUNT_OK;
            break;
        }



        $db_columns = array(
            'user_device_id'=>$this->userDeviceID,
            'user_device_imei'=>$this->userDeviceIMEI,
            'user_device_model'=>$this->userDeviceModel,
            'user_device_version'=>$this->userDeviceVersion,
            'user_platform'=>0,//$user_platform,
            'user_tcreate'=>time(),
            'user_tmodified'=>time(),
            'user_client_version'=>$this->userClientVersion,
            'user_ip_register'=>$this->userIP,
            'user_device_mac'=>$this->userDeviceMAC,
            'user_account_status'=>$this->userAccountStatus, //1=enabled 2=suspended 3=banned
            //the above is done for app verification purposes, user must install an app first to download other apps
            'user_locale_register'=>$this->userLocale,
            'user_credits'=>$user_credits,
            'user_referral_count'=>0,
    );

        $result = $this->db_create(self::TABLE_NAME,$db_columns);

        return $result;
    }

    public function register_friend_code($user_id)
    {
        $utilityInstance = new UtilityManager();
        $userFriendCode = $utilityInstance->get_friend_code($user_id);
        $this->set_user_friend_code($userFriendCode);
        $db_columns = array(
            'user_friend_code'=>$userFriendCode,
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }

    public function update_user_email($user_id,$user_email)
    {
        $this->set_user_email($user_email);
        $db_columns = array(
            'user_email'=>$user_email,
            'user_tmodified'=>time()
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $result;
    }

    public function user_did_like_facebook_page($user_id)
    {
        $db_columns = array(
            'user_facebook_like'
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(intval($result[0]['user_facebook_like']) == 1) return true;
        else return false;


    }

    public function update_user_facebook_like($user_id)
    {
        $db_columns = array(
            'user_tmodified'=>time(),
            'user_facebook_like'=>1
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
    }

    public function update_user_facebook($user_id,$user_email,$user_name,$user_gender,$user_facebook_id,$user_locale,$user_facebook_token,$user_facebook_verified)
    {

        $this->set_user_email($user_email);
        $this->set_user_name($user_name);
        $this->set_user_gender($user_gender);
        $this->set_user_facebook_id($user_facebook_id);
        $this->set_user_locale($user_locale);
        $this->set_user_facebook_token($user_facebook_token);
        $this->set_user_facebook_verified($user_facebook_verified);

        $db_columns = array(
            'user_email'=>$user_email,
            'user_name'=>$user_name,
            'user_gender'=>$user_gender,
            'user_locale'=>$user_locale,
            'user_facebook_token'=>$user_facebook_token,
            'user_facebook_verified'=>$user_facebook_verified,
            'user_facebook_id'=>$user_facebook_id,
            'user_tmodified'=>time()
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);

        $this->userAccountStatus = ACCOUNT_REGISTERED  . ACCOUNT_EMAIL_CONFIRMED . ACCOUNT_APPS_LOCKED . ACCOUNT_OK;


        //$this->set_user_account_status();
        $this->store_user_account_status($user_id);

        return $result;
    }


    public function set_user_hash($user_id,$user_email)
    {
        $userHash = md5($user_id . $user_email);
        $db_columns = array(
            'user_hash'=>$userHash,
            'user_tmodified'=>time()
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return $userHash;
    }

    public function confirm_user_hash($user_hash)
    {
        $db_columns = array(
            'user_id','user_email','user_account_status'
        );

        $db_conditions = array('user_hash'=>$user_hash);
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);

        if(empty($result)) return false;

        $user_id = $result[0]['user_id'];
        $accountStatus = str_split($result[0]['user_account_status']);
        $accountStatus[1] = ACCOUNT_EMAIL_CONFIRMED; //access email field

        $db_columns = array(
            'user_tmodified'=>time(),
            'user_account_status'=>implode($accountStatus),
            'user_hash'=>''
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        return true;
    }

    public function user_did_register_email($user_email,$user_id = null)
    {
        $db_columns = array('user_email');
        if(isset($user_id)) {
            $db_conditions = array('user_id'=>$user_id,'user_email'=>$user_email);
        } else {
            $db_conditions = array('user_email'=>$user_email);
        }
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(!empty($result)) return true; else return false;
    }

    public function update_user_credits($user_id,$user_credits)
    {
        if($this->isBannedPermanently) return false; //dont credit a user if they're banned
        //$this->set_user_email($user_email);
        $db_columns = array(
            'user_credits'=>$user_credits,
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        if(empty($result)) return false;
        else return $result;

    }


    public function raw_dump_user_rewards()
    {
        $q="SELECT user_rewards.id,user_rewards.reward_source_id as reward_source_id,user_rewards.reward_id,sys_rewards.reward_img as reward_img,sys_rewards.reward_name,user_rewards.user_id,user_account.user_email,user_account.user_friend_code,user_rewards.reward_user_cost,user_rewards.reward_user_payout,user_rewards.reward_user_status,user_rewards.reward_user_tcreate,user_rewards.reward_user_tmodified FROM user_rewards LEFT JOIN user_account ON user_rewards.user_id = user_account.user_id LEFT JOIN sys_rewards ON user_rewards.reward_id = sys_rewards.reward_id";
        $result = $this->db_query($q);
        return $result;
    }

    public function raw_dump_users()
    {
        $db_columns = array('user_id','user_friend_code','user_name','user_credits','user_referral_count','user_device_id','user_device_model','user_device_version','user_tcreate','user_tlogin','user_ip','user_locale','user_email');

        //detect whether what is being passed is a UDID or an internal userID record
        //$db_conditions = (is_numeric($user_id))?array('user_id'=>$user_id):array('user_device_id'=>$user_id); //allows us to use both user_id and user_device_id
        $db_conditions = '';
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        return $result;
    }


    public function raw_dump_user_offers()
    {
        $db_columns = array('id','offer_id','user_id','offer_payout','offer_cost','offer_network_id','offer_network','offer_name','offer_image_url','offer_status','offer_click_count','offer_tcreate','offer_tmodified');

        //detect whether what is being passed is a UDID or an internal userID record
        //$db_conditions = (is_numeric($user_id))?array('user_id'=>$user_id):array('user_device_id'=>$user_id); //allows us to use both user_id and user_device_id
        $db_conditions = '';
        $result = $this->db_retrieve(self::OFFER_TABLE_NAME,$db_columns,$db_conditions,null,false);
        return $result;
    }


    public function raw_dump_user_callbacks()
    {
        $db_columns = array('id','network_cb_id','user_id','offer_network','offer_payout','offer_id','offer_tcreate');

        //detect whether what is being passed is a UDID or an internal userID record
        //$db_conditions = (is_numeric($user_id))?array('user_id'=>$user_id):array('user_device_id'=>$user_id); //allows us to use both user_id and user_device_id
        //$db_conditions = '';
        $result = $this->db_retrieve(self::CB_TABLE_NAME,$db_columns,$db_conditions,null,false);
        return $result;
    }

    public function user_is_logged_in()
    {
        $utilityInstance = new UtilityManager();
        $utilityInstance->log('user_is_logged_in(): deviceID:' . $_SESSION['deviceID'] . ' userID: ' . $_SESSION['userID'] . ' devicePlatform: ' . $_SESSION['devicePlatform'] . PHP_EOL);

        if(
            !isset($_SESSION['deviceID']) ||
            !isset($_SESSION['userID']) ||
            !isset($_SESSION['devicePlatform'])
            )
        {

            return false;
        }
        else {

            return true;
        }
    }

    public function user_has_facebook()
    {

        if(empty($_SESSION['userFacebookID']) || empty($_SESSION['userName']))
        {
            return false;
        }
        else {
            return true;
        }
    }

    public function user_is_admin()
    {
        global $adminAccounts;
        if(isset($_SESSION['userEmail']) && (in_array($_SESSION['userEmail'],$adminAccounts) )) return true; else return false;

    }




    public function load_user($user_id,$device_id)
    {
        global $lockedOffers;
        $utilityInstance = new UtilityManager();

        /**
         *  Retrieve user data
         */
        $db_columns = array(
              'user_id',
              'user_device_id',
              'user_device_imei',
              'user_device_model',
              'user_device_version',
              'user_platform',
              'user_tcreate',
              'user_tmodified',
              'user_account_status',
              'user_locale',
              'user_credits',
              'user_referral_count',
              'user_friend_code',
              'user_email',
              'user_credits_free',
              'user_credits_referral',
              'user_credits_sum',
              'user_payout',
              'user_name',
              'user_gender',
              'user_facebook_token',
              'user_facebook_verified',
              'user_facebook_id'
        );

        //if(is_numeric($user_id)) $column = 'user_id'; else $column = 'user_device_id';
        $db_conditions = array(
            'user_id'=>$user_id,
            'user_device_id'=>$device_id
        );
        $result = $this->db_retrieve(self::TABLE_NAME,$db_columns,$db_conditions,null,false);
        if(empty($result)) {
            $utilityInstance->log('[User.php] fatal error loading user_id:' . $user_id);
            return false;
        } //if we get no results, lets quit

        $this->set_user_id($result[0]['user_id']);
        $this->set_user_device_id($result[0]['user_device_id']);
        $this->set_user_device_imei($result[0]['user_device_imei']);
        $this->set_user_device_model($result[0]['user_device_model']);
        $this->set_user_device_version($result[0]['user_device_version']);
        $_SESSION['userAccountStatus'] = $result[0]['user_account_status'];
        $this->userAccountStatus = $_SESSION['userAccountStatus'];
        $this->set_user_locale($result[0]['user_locale']);
        $this->set_user_platform($result[0]['user_platform']);
        $this->set_user_referral_count($result[0]['user_referral_count']);
        $this->set_user_credits($result[0]['user_credits']);
        $this->set_user_account_created($result[0]['user_tcreate']);
        $this->set_user_account_modified($result[0]['user_tmodified']);
        $this->set_user_friend_code($result[0]['user_friend_code']);
        $this->set_user_email($result[0]['user_email']);
        $this->set_user_credits_free($result[0]['user_credits_free']);
        $this->set_user_credits_referral($result[0]['user_credits_referral']);
        $this->set_user_credits_sum($result[0]['user_credits_sum']);
        $this->set_user_payout($result[0]['user_payout']);

        $this->set_user_name($result[0]['user_name']);
        $this->set_user_gender($result[0]['user_gender']);
        $this->set_user_facebook_token($result[0]['user_facebook_token']);
        $this->set_user_facebook_verified($result[0]['user_facebook_verified']);
        $this->set_user_facebook_id($result[0]['user_facebook_id']);


        /**
         *  Update user account with passive information
         */

        //set the user IP address
	if(MODE=='prod'){
        	$this->set_user_ip_address($_SERVER['HTTP_X_FORWARDED_FOR']);
	}else{
        	$this->set_user_ip_address((MODE == 'local')?LOCAL_IP:$_SERVER['REMOTE_ADDR']);
	
	}
        $this->set_user_locale($this->get_user_locale($this->userIP));
        $this->process_user_account_status();

        /*
         *  IOS LOCKED APPS CHECK
         *  this is for users on iOS.. if they didn't load the gate offer lets check and unlock their account if they did.
         */
        switch($this->get_user_platform())
        {
            case PLATFORM_IPAD:
            case PLATFORM_IPHONE:
            case PLATFORM_IPOD:
            case PLATFORM_IOS:
                if(count($lockedOffers) > 0) {
                    if($this->user_did_unlock_offers($user_id)) {
                        $this->didUnlockAllApps = true;
                        $this->set_user_account_status();
                    }
                }
            break;
        }
        //if($this->userAccountStatus == 0 && $this->user_did_unlock_offers($user_id)) $this->update_user_account_status($user_id,1);



        //$rewardHistory = $this->db_query($q);
        $db_columns = array(
            'user_tlogin'=>date('Y-m-d'),
            'user_ip'=>$this->userIP,
            'user_locale'=>$this->userLocale,
            'user_platform'=>$this->get_user_platform(),
            'user_client_version'=>$this->userClientVersion
        );
        $db_conditions = array('user_id'=>$user_id);
        $result = $this->db_update(self::TABLE_NAME,$db_columns,$db_conditions,false);
        //$this->set_user_id($user_id);

        $utilityInstance->log('[User.php] User successfully logged in. Session data: [userID]: ' . $_SESSION['userID'] . ' [deviceID]: ' . $_SESSION['deviceID'] . ' [devicePlatform]:' . $_SESSION['devicePlatform']);

        //lets update the user login table
        $db_columns = array(
            'user_id'=>$user_id,
            'tlogin'=>date('Y-m-d')
        );
        $result = $this->db_create(self::LOGIN_TABLE_NAME,$db_columns);//_create(self::OFFER_TABLE_NAME,$db_columns);
        return true;
    }


}
//add user accounts


//check user accounts
