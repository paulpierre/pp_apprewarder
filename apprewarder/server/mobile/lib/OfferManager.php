<?php

class OfferManager extends User
{

    public $offerData = null;


   /* =====================
    * Adscend
    * ===================== */
    public $adscendAPIKey = ADSCEND_API_APP_KEY;
    public $adscendAPIBaseURL = ADSCEND_API_OFFER_URL;
    public $adscendProviderID = ADSCEND_API_PROVIDER_ID;
    public $adscendURL = null;


    /* =====================
     * HasOffers
     * ===================== */
    public $hasOffersAPIKey = HASOFFERS_API_KEY;
    public $hasOffersNetworkID = HASOFFERS_API_NETWORK_ID;
    public $hasOffersProviderID = HASOFFERS_API_PROVIDER_ID;
    public $hasOffersURL = null;

    /* =====================
     * AdAction
     * ===================== */
    public $adActionOffersAPIKey = ADACTION_API_KEY;
    public $adActionNetworkID = ADACTION_API_NETWORK_ID;
    public $adActionProviderID = ADACTION_API_PROVIDER_ID;
    public $adActionURL = null;

    /* =====================
     * Ksix
     * ===================== */
    public $ksixAPIKey = KSIX_API_KEY;
    public $ksixNetworkID = KSIX_API_NETWORK_ID;
    public $ksixProviderID = KSIX_API_PROVIDER_ID;
    public $ksixURL = null;

    /* =====================
     * SponsorPay / Fyber
     * =====================
     */
    public $fyberURL = null;
    public $fyberAppID = null;
    public $fyberHashKey = null;
    public $fyberBaseUrl = FYBER_API_BASE_URL;


    /* ====================
     * AARKI API CLASS VARS
     * ==================== */
    public $aarkiPlacementID;// = AARKI_API_PLACEMENT_ID;
    public $aarkiClientSecurityKey;// = AARKI_API_CLIENT_SECURITY_KEY;
    public $aarkiOfferBaseURL = AARKI_API_OFFER_URL;
    public $aarkiPostbackSecurityKey;// = AARKI_API_POSTBACK_SECURITY_KEY;
    public $aarkiProviderID = AARKI_API_PROVIDER_ID;
    public $device_type = null;
    public $aarkiURL = null;

    /*  ===========
     *  COMMON SHIT
     *  ===========
     */

    public $userID ;//null;
    public $deviceOS;
    public $deviceVersion;
    public $deviceMAC;
    public $deviceModel;
    public $defaultUUID = '7957e0a04e1225eb493160988c8a673103e1909e';
    public $userIP;


    /*
     *  When the class is instantiated, pull from
     */
    function __construct()
    {


        $this->detect_type = new DeviceDetectManager();
	if(MODE=='prod'){
        	$this->userIP = $_SERVER['HTTP_X_FORWARDED_FOR']; //derrick loadbalancer
	}else{
        	$this->userIP = (MODE == 'local')?'##########':$_SERVER['REMOTE_ADDR']; //lets pass it a dummy IP address
	}
        $this->userID = (isset($_SESSION['userID']))?$_SESSION['userID']:1234;
        $this->userLocale = (isset($_SESSION['userLocale']))?$_SESSION['userLocale']:'US'; //$this->userID = (isset($_SESSION['userLocale']))?$_SESSION['userLocale']:'en-us';
        $this->defaultUUID = (isset($_SESSION['deviceID']))?$_SESSION['deviceID']:'##########';
        $this->deviceOS = (isset($_SESSION['devicePlatform']))?$_SESSION['devicePlatform']:PLATFORM_IPHONE;
        $this->deviceVersion = (isset($_SESSION['deviceVersion']))?$_SESSION['deviceVersion']:'10a523';
        $this->deviceModel = (isset($_SESSION['deviceModel']))?$_SESSION['deviceModel']:'iPhone3,3';
        $this->deviceMAC = (isset($_SESSION['userDeviceMAC']))?$_SESSION['userDeviceMAC']:'00:0a:95:9d:68:16';
        //$this->load_offers(1,TRUE); //TODO: address this issue here!!

    }
    public function is_banned($offerName)
    {
        //foreach(explode(',',BANNED_OFFERS) as $bannedOffer) { if(strpos(strtolower($offerName),$bannedOffer)) return true; }
        $bannedOffers = explode(',',BANNED_OFFERS);
        if(in_array(strtolower($offerName),$bannedOffers)) return true; else return false;


    }

    public function is_sys_offer($offerID,$offerSet)
    {
        if(empty($offerSet)) return false;
        foreach($offerSet as $offerCheck) {
            if($offerCheck['offer_external_id'] == 0) return false;
            if($offerID == $offerCheck['offer_external_id']) return true;
        }
    }

    public function purge_offers($offerTypes = OFFERS_APPS)  //purges all offers by default
    {
        $currentTime = time(); //reset the offers and TTL from session var
        $_SESSION['offerTime'][$offerTypes] = $currentTime;
        $_SESSION['offerData'][$offerTypes] = Array();

        unset($_SESSION['offerNetwork']);
        unset($_SESSION['offerCurrentPage']);

    }

    public function load_offers($offerTypes = OFFERS_APPS,$isRefresh = false)
    {
        global $offerNetworkRank;
        if($this->cache_did_expire($offerTypes) == false && $isRefresh == false) return true;   //if the cache did not expire or its not a refresh, do nothing
        $utilityInstance = new UtilityManager();
        $utilityInstance->log('oops, looks this is a refresh or the cache did expire. calling networks!');
        $this->purge_offers();
        $_SESSION['offerData'][$offerTypes] = '';
        $_SESSION['offerTime'][$offerTypes] = '';
       //lets make sure we're dealing with only iOS right now
        //TODO: this is where we should separate iOS from Android offers

        $currentTime = time();
        $_SESSION['offerTime'][$offerTypes] = $currentTime;

        $this->user_refresh_data();
        $merged_offers = Array();

        foreach($offerNetworkRank as $offerNetwork)
        {
            switch($offerNetwork)
            {
                case ADSCEND_API_PROVIDER_ID:
                    $adscendOffers = $this->get_adscend_offers($offerTypes);
                    $merged_offers = array_merge((array)$merged_offers,(array)$adscendOffers);
                break;

                case APPREWARDER_API_PROVIDER_ID:
                    $appRewarderOffers = $this->get_local_offers($offerTypes);
                    $merged_offers = array_merge((array)$merged_offers,(array)$appRewarderOffers);
                 break;

                case AARKI_API_PROVIDER_ID:
                    $aarkiOffers = $this->get_aarki_offers($offerTypes);
                    $merged_offers = array_merge((array)$merged_offers,(array)$aarkiOffers);
                break;

                case FYBER_API_PROVIDER_ID:
                    $fyberOffers = $this->get_fyber_offers($offerTypes);
                    $merged_offers = array_merge((array)$merged_offers,(array)$fyberOffers);
                    break;

                default: break;
            }
        }

        $utilityInstance->aasort($merged_offers,'offerUserPayout');
        $_SESSION['offerData'][$offerTypes] = array_reverse($merged_offers); //display descending
    }

    public function manual_offers_did_expire()
    {
        $currentTime = intval(time()); //if the offer data is not loaded, or offer time, or the offer TTL expires, lets say yes
        $timeElapsed = (isset($_SESSION['manualOffersTime']))?($currentTime - intval($_SESSION['manualOffersTime'])):0;
        if(!isset($_SESSION['manualOffersData']) || !isset($_SESSION['manualOffersTime']) || (intval($timeElapsed) > intval(MANUAL_OFFERS_TTL))) return true;else return false;
    }

    public function cache_did_expire($offerTypes)
    {
        $currentTime = intval(time()); //if the offer data is not loaded, or offer time, or the offer TTL expires, lets say yes
        $timeElapsed = (isset($_SESSION['offerTime'][$offerTypes]))?($currentTime - intval($_SESSION['offerTime'][$offerTypes])):0;
        if(!isset($_SESSION['offerData']) || !isset($_SESSION['offerData'][$offerTypes]) || !isset($_SESSION['offerTime'][$offerTypes]) || (intval($timeElapsed) > intval(OFFER_TTL))) return true; else return false;
    }

    public function raw_dump_offers()
    {
        global $offerNetworkRank;
        $offerType = OFFERS_APPS;


        print '<H2>' . APP_NAME. ' Offers</H2><BR>';
        print 'userAgent: ' . $_SESSION['userAgent'] . '<br>';

        foreach($offerNetworkRank as $offerNetwork)
        {
            switch($offerNetwork)
            {
/*
                case ADSCEND_API_PROVIDER_ID:
                    print '<H1>ADSCEND</H1><PRE>';
                    print_r($this->get_adscend_offers($offerType,TRUE));
                    print '</PRE><p>' . $this->adscendURL . '</p></PRE>';
                 break;
*/

                case APPREWARDER_API_PROVIDER_ID:
                    print '<H1>APPREWARDER OFFERS</H1><PRE>';
                    print_r($this->get_local_offers($offerType,TRUE));
                    print '</PRE>';
                break;

                case AARKI_API_PROVIDER_ID:
                    print '<br><H1>AARKI</H1><br><PRE>';
                    //*  offerType - 0=all 1=install (all) only 2=install free only 3=install paid only 4=lead-gen only 5=video only
                    print_r($this->get_aarki_offers($offerType,TRUE));
                    print '</PRE><p>' . $this->aarkiURL . '</p>';
                break;

                case FYBER_API_PROVIDER_ID:
                    print '<br><H1>FYBER(SPONSORPAY)</H1><br><PRE>';
                    print_r($this->get_fyber_offers($offerType,TRUE));
                    print '</PRE><p>' . $this->fyberURL . '</p>';

                    break;

                default: break;
            }
        }
    }




/*
    public function get_adaction_url($offerID)
    {
        if(!is_numeric($offerID)) return false;
        $ch = curl_init();
        $params = array(
            'Method' => 'generateTrackingLink',
            'NetworkId' => ADACTION_API_NETWORK_ID,
            'api_key' => ADACTION_API_KEY,
            'filters' => array(
                'Offer.status' => 'active',
            ),
            'offer_id'=>$offerID
        );

        $adActionOffersURL = 'http://api.hasoffers.com/v3/Affiliate_Offer.json?' . http_build_query( $params );
        curl_setopt($ch, CURLOPT_URL,$adActionOffersURL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        $serializedResponse = json_decode($response,true);
        return $serializedResponse['response']['data']['click_url'];
    }
*/

    public function get_hasoffers_icons($networkIdentifier,$offerIDs)
    {
        if(!is_array($offerIDs) && !isset($networkIdentifier)) return false;

        switch($networkIdentifier)
        {
            case ADACTION_API_PROVIDER_ID:
                $networkID = ADACTION_API_NETWORK_ID;
                $apiKey = ADACTION_API_KEY;
                break;
            case KSIX_API_PROVIDER_ID:
                $networkID = KSIX_API_NETWORK_ID;
                $apiKey = KSIX_API_KEY;
                break;
            case HASOFFERS_API_PROVIDER_ID:
                $networkID = HASOFFERS_API_NETWORK_ID;
                $apiKey = HASOFFERS_API_KEY;
                break;
            default: return false; break;
        }

        $base = 'http://api.hasoffers.com/v3/Affiliate_Offer.json?';

        $params = array(
            'Format' => 'json'
        ,'Target' => 'Offer'
        ,'Method' => 'getThumbnail'
        ,'Service' => 'HasOffers'
        ,'Version' => 3,
            'filters' => array(
                'Offer.status' => 'active',
            )
        ,'NetworkId' => $networkID
        ,'api_key' =>$apiKey
        ,'ids' => $offerIDs
        );

        $url = $base . http_build_query( $params );
        $result = json_decode(file_get_contents( $url ),true);
        //if($networkIdentifier == KSIX_API_PROVIDER_ID) print 'ICONS:'.$url.'<br><pre>' . print_r($result,true) . '</pre>';
        foreach($result['response']['data'] as $item)
        {
            if(empty($item['Thumbnail'])) continue;
            $offerID = $item['offer_id'];
            $_o = $item['Thumbnail'];
            $offerThumbnail = $_o[key($_o)]['thumbnail'];

            //print '<pre>offerID:' . PHP_EOL . print_r($item,true) . PHP_EOL . '_o: ' . print_r($_o,true) . '</pre>' .PHP_EOL;
            $_SESSION['manualOffersThumbnails'][$networkIdentifier][$offerID] = $offerThumbnail;
            //print 'offerID:' . $offerID . ' thumb:' . $offerThumbnail . '</pre>' . PHP_EOL;

        }
    }


    public function get_hasoffers_categories($networkIdentifier,$offerIDs)
    {
        if(!is_array($offerIDs) && !isset($networkIdentifier)) return false;

        switch($networkIdentifier)
        {
            case ADACTION_API_PROVIDER_ID:
                $networkID = ADACTION_API_NETWORK_ID;
                $apiKey = ADACTION_API_KEY;
                break;
            case KSIX_API_PROVIDER_ID:
                $networkID = KSIX_API_NETWORK_ID;
                $apiKey = KSIX_API_KEY;
                break;
            case HASOFFERS_API_PROVIDER_ID:
                $networkID = HASOFFERS_API_NETWORK_ID;
                $apiKey = HASOFFERS_API_KEY;
                break;
            default: return false; break;
        }

        $base = 'http://api.hasoffers.com/v3/Affiliate_Offer.json?';

        $params = array(
            'Format' => 'json'
        ,'Target' => 'Offer'
        ,'Method' => 'getCategories'
        ,'Service' => 'HasOffers'
        ,'Version' => 3

        ,'NetworkId' => $networkID
        ,'api_key' =>$apiKey
        ,'ids' => $offerIDs
        );

        $url = $base . http_build_query( $params );
        $result = json_decode(file_get_contents( $url ),true);


        //if($networkIdentifier == KSIX_API_PROVIDER_ID) print 'ICONS:'.$url.'<br><pre>' . print_r($result,true) . '</pre>';
        foreach($result['response']['data'] as $item)
        {
            if(empty($item['Thumbnail'])) continue;
            $offerID = $item['offer_id'];
            $_o = $item['Thumbnail'];
            $offerThumbnail = $_o[key($_o)]['thumbnail'];

            //print '<pre>offerID:' . PHP_EOL . print_r($item,true) . PHP_EOL . '_o: ' . print_r($_o,true) . '</pre>' .PHP_EOL;
            $_SESSION['manualOffersCategories'][$networkIdentifier][$offerID] = $offerThumbnail;
            //print 'offerID:' . $offerID . ' thumb:' . $offerThumbnail . '</pre>' . PHP_EOL;

        }
    }


    public function get_hasoffers_url($networkIdentifier,$offerID)
    {
        if(!isset($offerID) || !isset($networkIdentifier)) return false;

        switch($networkIdentifier)
        {
            case ADACTION_API_PROVIDER_ID:
                $networkID = ADACTION_API_NETWORK_ID;
                $apiKey = ADACTION_API_KEY;
                break;
            case KSIX_API_PROVIDER_ID:
                $networkID = KSIX_API_NETWORK_ID;
                $apiKey = KSIX_API_KEY;
                break;
            case HASOFFERS_API_PROVIDER_ID:
                $networkID = HASOFFERS_API_NETWORK_ID;
                $apiKey = HASOFFERS_API_KEY;
                break;
            default: return false; break;
        }

        $ch = curl_init();
        $params = array(
            'Method' => 'generateTrackingLink',
            'NetworkId' => $networkID,
            'api_key' => $apiKey,
            'offer_id'=>$offerID
        );

        $url = 'http://api.hasoffers.com/v3/Affiliate_Offer.json?' . http_build_query( $params );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $serializedResponse = json_decode($response,true);
        return $serializedResponse['response']['data']['click_url'];
    }

    public function get_hasoffers_countries($networkIdentifier,$offerIDs)
    {
        if(!is_array($offerIDs) && !isset($networkIdentifier)) return false;

        switch($networkIdentifier)
        {

        case ADACTION_API_PROVIDER_ID:
            $networkID = ADACTION_API_NETWORK_ID;
            $apiKey = ADACTION_API_KEY;
            break;
        case KSIX_API_PROVIDER_ID:
            $networkID = KSIX_API_NETWORK_ID;
            $apiKey = KSIX_API_KEY;
            break;
        case HASOFFERS_API_PROVIDER_ID:
            $networkID = HASOFFERS_API_NETWORK_ID;
            $apiKey = HASOFFERS_API_KEY;
            break;
        default: return false; break;

        }


        $ch = curl_init();
        $params = array(
            'Format' => 'json'
            ,'Target' => 'Offer',
            'Method' => 'getTargetCountries'
        ,'NetworkId' =>$networkID
        ,'api_key' => $apiKey,
            'ids'=>$offerIDs
            ,'Service' => 'HasOffers'
            ,'Version' => 3,
            'filters' => array(
                'Offer.status' => 'active')
        );


        $url = 'http://api.hasoffers.com/v3/Affiliate_Offer.json?' . http_build_query( $params );
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response,true);
        //if($networkIdentifier == KSIX_API_PROVIDER_ID) print 'COUNTRIES:<pre>' . print_r($response,true) . '</pre>';

        foreach($response['response']['data'] as $offer)
        {
            if(empty($offer['countries'])) continue;
            $offerID = $offer['offer_id'];
            $_SESSION['manualOffersCountries'][$networkIdentifier][$offerID] = implode(',',array_keys($offer['countries']));
        }
        //exit('<pre>countries: ' . print_r($countries,true) .'</pre>');
        //return $serializedResponse;
    }




    public function purge_manual_offers_cache()
    {   //used by debug.php
        $currentTime = time();
        $_SESSION['manualOffersTime'] = $currentTime;
        $_SESSION['manualOffersData'] = null;
    }


    public function is_completed_offer($offerID,$offerSet)
    {
        if(empty($offerSet)) return false;
        foreach($offerSet as $offerCheck) {
            if($offerCheck['offer_id'] == 0) return false;
            if($offerID == $offerCheck['offer_id']) return true;
        }
    }


    public function get_local_offers($offerTypes = OFFERS_APPS, $returnRawData = false) //apprewarder 1st party offers e.g. hasoffers
    {
        $userID = $this->userID;
        $offerInstance = new Offer();
        $offerList = $offerInstance->get_offer_list();
        if(empty($offerList)) return false;
        $userCompletedOffers = $this->get_completed_offers(intval($userID));

        foreach($offerList as $o)
        {
            $data = $o;
            if($this->is_completed_offer($data['offer_external_id'],$userCompletedOffers)) continue;
            if($this->is_banned($data['offer_name'])) continue;
            if(!empty($this->userLocale) && (!empty($data['offer_country']))) {             //country white list check


                $isAllowed = FALSE;
                $countryList = explode(',',$data['offer_country']);
                    foreach($countryList as $countryCode)
                    {
                        if(strtoupper($countryCode) == strtoupper($this->userLocale)) $isAllowed = TRUE;
                    }
                if($isAllowed == FALSE) {continue;} //if none of the countries match, skip offer

            }

            $userPlatform = $this->get_user_platform();

            if(intval($data['offer_platform']) !== 0)
            {
                if((intval($data['offer_platform']) == PLATFORM_IOS ||
                    intval($data['offer_platform']) == PLATFORM_IPHONE ||
                    intval($data['offer_platform']) == PLATFORM_IPAD ||
                    intval($data['offer_platform']) == PLATFORM_IPOD
                ) && $userPlatform == PLATFORM_ANDROID) { continue;}//if its an ios app and were android skip
                if((intval($data['offer_platform']) == PLATFORM_ANDROID) && ($userPlatform == PLATFORM_IOS || $userPlatform == PLATFORM_IPHONE || $userPlatform == PLATFORM_IPAD || $userPlatform == PLATFORM_IPOD)) { continue;}//if its an android app, and were either iphone, ipad, or ios universal, skip
                if(intval($data['offer_platform']) == PLATFORM_IPHONE && $userPlatform == PLATFORM_IPAD) { continue; } //if the app is explicitly iphone and were ipad, skip
                if(intval($data['offer_platform']) == PLATFORM_IPAD && ($userPlatform == PLATFORM_IPHONE || $userPlatform == PLATFORM_IPOD)) { continue; } //if the app is explicitly ipad and were iphone, skip
            }

            $payoutConversion = $this->offer_payout_conversion($data['offer_network_payout'],intval($data['offer_source_id']));
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerURL'] = $data['offer_click_url'] . '&aff_sub2=' . $offerData['offerUserPayout']. '&aff_sub=' . $userID . '&device_id=' . $this->defaultUUID . '&mobile_ip=' . $this->userIP; //include userID for postback
            $offerData['offerType'] = $data['offer_type'];
            if(!isset($offerData['offerType'])) continue;
            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip
            $offerData['offerName'] =$data['offer_name'];
            $offerData['offerImage'] = $data['offer_image_url'];
            $offerData['offerID'] = $data['offer_external_id'];
            $offerData['offerUserCost'] = $data['offer_external_cost'];
            $offerData['offerNetworkPayout'] = $data['offer_network_payout'];
            $offerData['offerSrc'] = 'apprewarder';
            $offerData['offerNetworkID'] = $data['offer_source_id'];
            $offerData['offerDescription'] = (!empty($data['offer_description']))?$data['offer_description']:'Download and install. Play for 30 seconds.';
            $offerSet[] = $offerData;
        }

        if ($returnRawData) { return $offerList;}
        else { if(isset($offerSet)) return $offerSet; else return false;}
    }

    public function get_adaction_offers($offerTypes = OFFERS_APPS, $isRefresh = false, $returnRawData = false)
    {

        $ch = curl_init();
        $params = array(
            'Method' => 'findAll'
        ,'NetworkId' => ADACTION_API_NETWORK_ID
        ,'api_key' => ADACTION_API_KEY
        );
        $this->adActionOffersURL =  ADACTION_API_BASE_URL . '?' . http_build_query( $params );
        curl_setopt($ch, CURLOPT_URL,$this->adActionOffersURL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $serializedResponse = json_decode($response,true);

        if(!isset($serializedResponse)) { return false; }
        $offerList = $serializedResponse['response']['data'];
        $sysOfferInstance = new Offer();
        $sysOfferSet = $sysOfferInstance->get_external_ids();

        foreach($offerList as $o)
        {
            $data = $o['Offer'];
            $userID = $this->userID;

            if($this->is_sys_offer($data['id'],$sysOfferSet)) continue; //if it is already a system offer, do not show

            $offerPayout = $data['default_payout'];

            $payoutConversion = $this->offer_payout_conversion($offerPayout,ADACTION_API_PROVIDER_ID);
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerType'] = OFFERS_APPS;

            if(!isset($offerData['offerType'])) continue;
            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip

            $offerData['offerName'] = (isset($data['name']))?$data['name']:null;

            $offerData['offerID'] = (isset($data['id']))?$data['id']:null;
            $offerData['offerDestination'] = (isset($data['preview_url']))?$data['preview_url']:null;
            //$offerURL = $this->get_adaction_url($data['id']);
            //$offerURL = 'http://tracking.adactioninteractive.com/aff_c?offer_id=' . $offerData['offerID'] .'&aff_id=1389';
            //$offerData['offerURL'] = (!empty($offerURL))?$offerURL:null;
            $offerData['offerUserCost'] = null;//(isset($data['purchase']))?$data['purchase']:null;

            $offerData['offerExpiration'] = (isset($data['expiration_date']))?strtotime($data['expiration_date']):0;

            $offerData['offerNetworkPayout'] = $offerPayout;
            $offerData['offerSrc'] = 'adaction';
            $offerData['offerNetworkID'] = ADACTION_API_PROVIDER_ID;
            $offerData['offerDescription'] = (!empty($data['description']))?$data['description']:'Download and install. Play for 30 seconds.';
            $offerSet[] = $offerData;

            $offerIDs[] = $offerData['offerID'];

        }
        $this->get_hasoffers_countries(ADACTION_API_PROVIDER_ID,$offerIDs);
        $this->get_hasoffers_icons(ADACTION_API_PROVIDER_ID,$offerIDs);

        //exit('<pre>iconz: ' . print_r($offerIcons,true). '</pre>');

        return $offerSet;
    }

    public function get_hasoffers_offers($offerTypes = OFFERS_APPS, $isRefresh = false, $returnRawData = false)
    {

        $ch = curl_init();
        $params = array(
            'Method' => 'findAll'
            ,'NetworkId' => HASOFFERS_API_NETWORK_ID
            ,'api_key' => HASOFFERS_API_KEY
        );
        $this->hasOffersURL =  HASOFFERS_API_BASE_URL . '?' . http_build_query( $params );
        curl_setopt($ch, CURLOPT_URL,$this->hasOffersURL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $serializedResponse = json_decode($response,true);

        if(!isset($serializedResponse)) { return false; }
        $offerList = $serializedResponse['response']['data'];
        $sysOfferInstance = new Offer();
        $sysOfferSet = $sysOfferInstance->get_external_ids();

        foreach($offerList as $o)
        {
            $data = $o['Offer'];
            $userID = $this->userID;

            if($this->is_sys_offer($data['id'],$sysOfferSet)) continue; //if it is already a system offer, do not show

            $offerPayout = $data['default_payout'];

            $payoutConversion = $this->offer_payout_conversion($offerPayout,HASOFFERS_API_PROVIDER_ID);
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerType'] = OFFERS_APPS;

            if(!isset($offerData['offerType'])) continue;
            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip

            $offerData['offerName'] = (isset($data['name']))?$data['name']:null;
            $offerData['offerID'] = (isset($data['id']))?$data['id']:null;
            $offerData['offerDestination'] = (isset($data['preview_url']))?$data['preview_url']:null;
            $offerData['offerUserCost'] = null;//(isset($data['purchase']))?$data['purchase']:null;
            $offerData['offerNetworkPayout'] = $offerPayout;
            $offerData['offerSrc'] = 'hasoffers';
            $offerData['offerNetworkID'] = HASOFFERS_API_PROVIDER_ID;
            $offerData['offerDescription'] = (!empty($data['description']))?$data['description']:'Download and install. Play for 30 seconds.';
            $offerData['offerExpiration'] = (isset($data['expiration_date']))?strtotime($data['expiration_date']):0;

            $offerSet[] = $offerData;

            $offerIDs[] = $offerData['offerID'];

        }
        $this->get_hasoffers_countries(HASOFFERS_API_PROVIDER_ID,$offerIDs);
        $this->get_hasoffers_icons(HASOFFERS_API_PROVIDER_ID,$offerIDs);
        return $offerSet;
   }

    public function get_ksix_offers($offerTypes = OFFERS_APPS, $isRefresh = false, $returnRawData = false)
    {
        $ch = curl_init();
        $params = array(
            'Method' => 'findAll',
            'NetworkId' =>KSIX_API_NETWORK_ID,
            'api_key' => KSIX_API_KEY,

            'filters' => array(
                'Offer.status' => array('EQUAL_TO'=>'active'),
            ),
            'groups'=>array('Affiliate.')

        );
        $this->ksixURL =  KSIX_API_BASE_URL . '?' . http_build_query( $params );


        //print('debug.php - Call' . urldecode($this->ksixURL));

        curl_setopt($ch, CURLOPT_URL,$this->ksixURL);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $serializedResponse = json_decode($response,true);
        if(!isset($serializedResponse)) { return false; }
        $offerList = $serializedResponse['response']['data'];

        //exit('<pre>' .print_r($serializedResponse,true));

        $sysOfferInstance = new Offer();
        $sysOfferSet = $sysOfferInstance->get_external_ids();

        foreach($offerList as $o)
        {
            $data = $o['Offer'];
            if($this->is_sys_offer($data['id'],$sysOfferSet)) continue; //if it is already a system offer, do not show
            $offerPayout = $data['default_payout'];
            $payoutConversion = $this->offer_payout_conversion($offerPayout,KSIX_API_PROVIDER_ID);
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerType'] = OFFERS_APPS;
            if(intval($data['is_expired'])== 1) continue;
            $offerData['offerExpiration'] = (isset($data['expiration_date']))?strtotime($data['expiration_date']):0;

            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip
            $offerData['offerName'] = (isset($data['name']))?$data['name']:null;
            if(
                strpos(strtolower($offerData['offerName']),'no incent')||
                strpos(strtolower($offerData['offerName']),'web incent') ||
                !strpos(strtolower($offerData['offerName']),'incent') ||
                (!strpos(strtolower($offerData['offerName']),'android')) && !strpos(strtolower($offerData['offerName']),'ios') &&
                (!strpos(strtolower($offerData['offerDescription']),'android')) && !strpos(strtolower($offerData['offerDescription']),'ios')
            )
                continue;
            $offerData['offerID'] = (isset($data['id']))?$data['id']:null;
            $offerData['offerDestination'] = (isset($data['preview_url']))?$data['preview_url']:null;
            $offerData['offerUserCost'] = null;//(isset($data['purchase']))?$data['purchase']:null;
            $offerData['offerNetworkPayout'] = $offerPayout;
            $offerData['offerSrc'] = 'ksix';
            $offerData['offerNetworkID'] = HASOFFERS_API_PROVIDER_ID;
            $offerData['offerDescription'] = (!empty($data['description']))?$data['description']:'Download and install. Play for 30 seconds.';
            $offerSet[] = $offerData;
            $offerIDs[] = $offerData['offerID'];

        }
        //$this->get_hasoffers_countries(KSIX_API_PROVIDER_ID,$offerIDs);
        //$this->get_hasoffers_icons(KSIX_API_PROVIDER_ID,$offerIDs);
        //$this->get_hasoffers_categories(KSIX_API_PROVIDER_ID,$offerIDs);

        return $offerSet;
    }



    public function get_adscend_offers($offerTypes = OFFERS_APPS,$returnRawData = false)
    {
        $userAgent = urlencode($_SESSION['userAgent']);
        $ch = curl_init();
        $post_data = 'pubid=' . ADSCEND_API_PUBLISHER_ID . '&key=' . ADSCEND_API_APP_KEY ;
        $post_data .= '&user_ip='.$this->userIP.'&user_subid='. $this->userID;
        $post_data .= '&mode=offers';
        $post_data .= '&incent=1&gateway_mode=0&only_instant=1&include_completed=0';
        $post_data .= '&quantity=100&min_payout=0.00&max_cost=500.00&free_offers=1&cell_offers=1&trial_offers=0';
        $post_data .= '&creative=125&category=1&useragent=' . $userAgent;//&not_assoc=0';

        /*
         * 'http://adscendmedia.com/api-get.php?
         * pubid=19715&
         * key=1380317281&
         * user_ip=192.158.1.108&
         * user_subid=12441&
         * mode=offers&
         * incent=0&
         * gateway_mode=1&
         * only_instant=1&
         * include_completed=0&
         * quantity=100&
         * min_payout=0.00&
         * max_cost=500.00&
         * free_offers=1&
         * cell_offers=1&
         * trial_offers=0&
         * creative=1&
         * category=1'
         */
        $this->adscendURL = ADSCEND_API_OFFER_URL . '?' . $post_data;
        curl_setopt($ch, CURLOPT_URL,$this->adscendURL);
        curl_setopt($ch, CURLOPT_TIMEOUT, NETWORK_TIMEOUT);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        if($response == '"No offers available."') { return false;}
        $serializedResponse = json_decode($response,true);
        if(!isset($serializedResponse) || empty($serializedResponse)) { return false; } //if the result set is empty return false

        foreach($serializedResponse as $data)
        {
            if($this->is_banned($data['name'])) continue;
            if(!isset($data['url'])) continue; //if there is no URL set for the offer, just skip adding it
            $offerData['offerURL'] = (isset($data['url']))?$data['url']:null;
            $offerData['offerType'] = OFFERS_APPS;
            if(!isset($offerData

            ['offerType'])) continue;
            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip
            $offerData['offerName'] = (isset($data['name']))?$data['name']:null;
            $offerData['offerImage'] = (isset($data['creative']))?$data['creative']:null;
            $offerData['offerID'] = (isset($data['id']))?$data['id']:null;
            $payoutConversion = $this->offer_payout_conversion($data['payout'],ADSCEND_API_PROVIDER_ID);
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerUserCost'] = null;//(isset($data['purchase']))?$data['purchase']:null;
            $offerData['offerNetworkPayout'] = (isset($data['payout']))?$data['payout']:null;
            $offerData['offerSrc'] = 'adscend';
            $offerData['offerNetworkID'] = ADSCEND_API_PROVIDER_ID;
            $offerData['offerDescription'] = (isset($data['description']))?$data['description']:null . (isset($data['conv_notes']))?' '.$data['conv_notes']:'';
            $offerSet[] = $offerData;
        }

        if ($returnRawData) { return json_decode($response,TRUE);}
        else { if(isset($offerSet)) return $offerSet; else return false;}
    }

    public function get_aarki_offers($offerTypes = OFFERS_APPS,$returnRawData = false)
    {
        switch($this->get_user_platform()) {
            case PLATFORM_ANDROID:
                $this->aarkiPlacementID = AARKI_API_PLACEMENT_ID_ANDROID;
                $deviceKey = 'device_id';
            break;
            case PLATFORM_IOS:
            case PLATFORM_IPHONE:
            case PLATFORM_IPOD:
            case PLATFORM_IPAD:
            $this->aarkiPlacementID = AARKI_API_PLACEMENT_ID_IOS;
            $deviceKey = 'advertising_id';
            break;
            default:
            $this->aarkiPlacementID = AARKI_API_PLACEMENT_ID_IOS;
            $deviceKey = 'advertising_id';
            break;
        }

        $q = array(
            'src'=>$this->aarkiPlacementID,
            $deviceKey => $this->defaultUUID,
            'user_agent'=>$_SESSION['userAgent'],//$_SERVER['HTTP_USER_AGENT'],
            'user_id'=>$this->userID,
            'country'=>(strlen($this->userLocale) > 2)?substr($this->userLocale,0,2):$this->userLocale,
            'user_ip'=> $this->userIP,
            'src'=>$this->aarkiPlacementID
        );

        $url = $this->aarkiOfferBaseURL . '?' . http_build_query($q);//src=' . $this->aarkiPlacementID;

        $ch = curl_init();
        $headers = array(
            "Accept: application/json"
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = trim(curl_exec($ch));
        curl_close($ch);

        $serializedResponse = json_decode($response,true);


        foreach($serializedResponse as $data)
        {
            if($this->is_banned($data['name'])) continue;
            if(!isset($data['url'])) continue; //if there is no URL set for the offer, just skip adding it
            if(isset($data['offer_id']) && ($data['offer_id'] == 'offfd5d27cce5848ee') || $data['offer_id'] == 'ofd3b03e097b60c9d3' || $data['offer_id'] == 'of1c3b12ee4fc7af66')  continue;
            $offerData['offerURL'] = (isset($data['url']))?$data['url']:null;
            switch($data['offer_type']){
                //*  offerType - 0=all 1=install (all) only 2=install free only 3=install paid only 4=lead-gen only 5=video only
                case 'install':$offerData['offerType'] = OFFERS_APPS; break;  //an app install offer
                case 'video': $offerData['offerType'] = OFFERS_VIDEO; break; //a video offer
            }

            if(!isset($offerData['offerType'])) continue;
            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip
            $offerData['offerName'] = (isset($data['name']))?$data['name']:null;
            $offerData['offerImage'] = (isset($data['image_url']))?$data['image_url']:null;
            $offerData['offerID'] = (isset($data['offer_id']))?$data['offer_id']:null;
            $payout = floatval($data['payout']);
            $payoutConversion = $this->offer_payout_conversion($payout,AARKI_API_PROVIDER_ID);
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerUserCost'] = (isset($data['purchase']))?$data['purchase']:null;
            $offerData['offerNetworkPayout'] = (isset($payout))?$payout:null;
            $offerData['offerSrc'] = 'aarki';
            $offerData['offerNetworkID'] = AARKI_API_PROVIDER_ID;
            $offerData['offerDescription'] = (isset($data['ad_copy']))?$data['ad_copy']:null;
            $offerSet[] = $offerData;
            unset($offerData);
        }

        if ($returnRawData) { return json_decode($response,TRUE);}
        else { if(isset($offerSet)) return $offerSet; else return false;}
    }

    public function get_fyber_offers($offerTypes = OFFERS_APPS,$returnRawData = false)
    {

        $deviceInstance = new DeviceDetectManager();
        $deviceType = ($deviceInstance->isTablet())?'tablet':'phone';
        unset($deviceInstance);

        if($this->isAndroid())
        {
            $this->fyberAppID = FYBER_API_APP_ID_ANDROID;
            $this->fyberHashKey = FYBER_API_HASH_KEY_ANDROID;
            //$userOS = PLATFORM_ANDROID;
        } else {
            //$userOS = PLATFORM_IOS;
            $this->fyberAppID = FYBER_API_APP_ID_IOS;
            $this->fyberHashKey = FYBER_API_HASH_KEY_IOS;
        }





        $q = array(

            'appid'=>$this->fyberAppID,
            'uid'=>$this->userID,
            'ip'=>$this->userIP,
            'locale'=>$this->userLocale,
            'device_id'=>$this->defaultUUID,
            'offer_types'=>'101',
            'os_version'=>$this->deviceVersion,
            'format'=>'json',
            //'pub0'=>$userOS,
            'timestamp'=>time(),
            //'mac_address'=>$this->deviceMAC,
            'md5_mac'=>md5($this->deviceMAC),
            'sha1_mac'=>sha1($this->deviceMAC),
            'device'=>$deviceType,
            //'android_id'=>$this->defaultUUID

        );
        if($this->isAndroid()) $q['android_id'] = $this->defaultUUID;
        ksort($q);

        $query = http_build_query($q);

        $url = $this->fyberBaseUrl . '?' . $query  . '&hashkey=' . sha1($query . '&' . $this->fyberHashKey);
        if($returnRawData) print 'query: ' . print_r($q,true) . '<br>hashkey: ' .$this->fyberHashKey .' <br>'. $url . '<br>';
        $ch = curl_init();

        $headers = array(
            "Accept: application/json"
        );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        $serializedResponse = json_decode($response,true);

        //print('<pre>'.print_r($serializedResponse,true). '</pre><br>' . $url);
        //return;
        foreach($serializedResponse['offers'] as $data)
        {
            if($this->is_banned($data['title'])) continue;
            if(!isset($data['link'])) continue; //if there is no URL set for the offer, just skip adding it
            $offerData['offerURL'] = (isset($data['link']))?$data['link']:null;

           /*
            switch($data['offer_type']){
                //*  offerType - 0=all 1=install (all) only 2=install free only 3=install paid only 4=lead-gen only 5=video only
                case 'install':$offerData['offerType'] = OFFERS_APPS; break;  //an app install offer
                case 'video': $offerData['offerType'] = OFFERS_VIDEO; break; //a video offer
            }

            if(!isset($offerData['offerType'])) continue;
            if($offerData['offerType'] == OFFERS_APPS && ($offerTypes == OFFERS_VIDEO)) continue; //if this offer is an app but videos were requested, skip
            if($offerData['offerType'] == OFFERS_VIDEO && ($offerTypes == OFFERS_APPS)) continue; //if this offer is a video but apps were requested, skip
           */
            $offerData['offerName'] = (isset($data['title']))?$data['title']:null;
            $offerData['offerImage'] = (isset($data['thumbnail']['hires']))?$data['thumbnail']['hires']:null;
            $offerData['offerID'] = (isset($data['offer_id']))?$data['offer_id']:null;
            $payout = number_format(floatval($data['payout']/600),2);
            $payoutConversion = $this->offer_payout_conversion($payout,FYBER_API_PROVIDER_ID);
            $offerData['offerUserPayout'] = $payoutConversion['userPayout'];
            $offerData['offerReferralPayout'] = $payoutConversion['userReferralPayout'];
            $offerData['offerUserCost'] = null;
            $offerData['offerNetworkPayout'] = (isset($payout))?$payout:null;
            $offerData['offerSrc'] = 'fyber';
            $offerData['offerNetworkID'] = FYBER_API_PROVIDER_ID;
            $offerData['offerDescription'] = (isset($data['teaser']))?$data['teaser']:null;
            $offerSet[] = $offerData;
            unset($offerData);
        }

        if ($returnRawData) { return json_decode($response,TRUE);}
        else { if(isset($offerSet)) return $offerSet; else return false;}
    }


   public function get_apprewarder_offers($offerTypes = OFFERS_APPS,$returnRawData = false)
    {
            $offerInstance = new Offer();
            $offerInstance->get_offer_list();
    }

    public function offer_payout_conversion($offerPayout,$offerNetworkID)
    {
        $allowReferralPayout = false;
        $userNetPayout = 0;
        switch($offerNetworkID)
        {
            case APPREWARDER_API_PROVIDER_ID:
                $allowReferralPayout = APPREWARDER_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;

            case FYBER_API_PROVIDER_ID:
                $offerPayoutConverted = ($offerPayout>0)?floatval($offerPayout) * floatval(PAYOUT_CONVERSION_FYBER):PAYOUT_CONVERSION_FYBER_MIN;
                $userPayout = ($offerPayoutConverted <= PAYOUT_CONVERSION_FYBER_MAX)?intval($offerPayoutConverted):PAYOUT_CONVERSION_FYBER_MAX; //if it exceeds the cap, return the cap
                $allowReferralPayout = FYBER_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;

            case AARKI_API_PROVIDER_ID:
                $offerPayoutConverted = ($offerPayout>0)?floatval($offerPayout) * floatval(PAYOUT_CONVERSION_AARKI):PAYOUT_CONVERSION_AARKI_MIN;
                $userPayout = ($offerPayoutConverted <= PAYOUT_CONVERSION_AARKI_MAX)?intval($offerPayoutConverted):PAYOUT_CONVERSION_AARKI_MAX; //if it exceeds the cap, return the cap
                $allowReferralPayout = AARKI_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;

            case ADSCEND_API_PROVIDER_ID:
                $offerPayoutConverted = ($offerPayout>0)?floatval($offerPayout) * floatval(PAYOUT_CONVERSION_ADSCEND):PAYOUT_CONVERSION_ADSCEND_MIN;
                $userPayout = ($offerPayoutConverted <= PAYOUT_CONVERSION_ADSCEND_MAX)?intval($offerPayoutConverted):PAYOUT_CONVERSION_ADSCEND_MAX; //if it exceeds the cap, return the cap
                $allowReferralPayout = ADSCEND_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;

            case ADACTION_API_PROVIDER_ID:
                $offerPayoutConverted = ($offerPayout>0)?floatval($offerPayout) * floatval(PAYOUT_CONVERSION_ADACTION):PAYOUT_CONVERSION_ADACTION_MIN;
                $userPayout = ($offerPayoutConverted <= PAYOUT_CONVERSION_ADACTION_MAX)?intval($offerPayoutConverted):PAYOUT_CONVERSION_ADACTION_MAX; //if it exceeds the cap, return the cap
                $allowReferralPayout = ADACTION_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;

            case HASOFFERS_API_PROVIDER_ID:
                $offerPayoutConverted = ($offerPayout>0)?floatval($offerPayout) * floatval(PAYOUT_CONVERSION_HASOFFERS):PAYOUT_CONVERSION_HASOFFERS_MIN;
                $userPayout = ($offerPayoutConverted <= PAYOUT_CONVERSION_HASOFFERS_MAX)?intval($offerPayoutConverted):PAYOUT_CONVERSION_HASOFFERS_MAX; //if it exceeds the cap, return the cap
                $allowReferralPayout = HASOFFERS_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;

            case KSIX_API_PROVIDER_ID:
                $offerPayoutConverted = ($offerPayout>0)?floatval($offerPayout) * floatval(PAYOUT_CONVERSION_KSIX):PAYOUT_CONVERSION_KSIX_MIN;
                $userPayout = ($offerPayoutConverted <= PAYOUT_CONVERSION_KSIX_MAX)?intval($offerPayoutConverted):PAYOUT_CONVERSION_KSIX_MAX; //if it exceeds the cap, return the cap
                $allowReferralPayout = KSIX_ALLOW_OFFER_REFERRAL_PAYOUT;
                break;
            default:$userPayout = 0;break;
        }

        if(PAYOUT_RATIO_TO_REFERRAL > 0 && PAYOUT_RATIO_TO_REFERRAL < 1 && $allowReferralPayout)
        {
            //userpayout=450
            /**
             * define('PAYOUT_RATIO_TO_REFERRAL',.25);
            define('PAYOUT_RATIO_TO_DOWNLOADER',.5);
             */
            $networkPayout = intval(floor($offerPayout * AR_EXCHANGE_RATE));
            $userNetPayout = intval(floor($networkPayout * PAYOUT_RATIO_TO_DOWNLOADER));
            $userReferralPayout = intval(floor($networkPayout * PAYOUT_RATIO_TO_REFERRAL ));

            //$userNetPayout = intval(floor($userPayout * floatval(1 - PAYOUT_REFERRAL_OFFER_RATIO)));
            //$userReferralPayout = intval(floor($userPayout * PAYOUT_REFERRAL_OFFER_RATIO));
        }

        return Array(
            'userPayout'=>(isset($userNetPayout) && $userNetPayout > 0)?$userNetPayout:$userPayout,
            'userReferralPayout'=>(isset($userReferralPayout) && $userReferralPayout > 0)?$userReferralPayout:0
        );

    }

}
?>
