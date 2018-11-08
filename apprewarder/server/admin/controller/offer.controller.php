<?php

$page_data = array(
    'page_title'=> 'Offer Manager',
    'page_name' =>  $controller_name
);

function get_full_url($original_url)
{
    $url = $original_url;

    for($i=0;$i < 5;$i++)
    {
        $urlJumps[$i] = $url;
        if(
            !isset($url) ||
            $url == $urlJumps[$i-1] ||
            strpos($url,'itunes.apple.com') ||
            strpos($url,'play.google.com')) {
            break;
        }

        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_HEADER,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION,false);
        $data = curl_exec($ch);
        $res = curl_getinfo($ch);
        curl_close($ch);
        //print '<pre>' . print_r($res,true) .'</pre>';
        $url = (isset($res['redirect_url']))?$res['redirect_url']:$res['url'];
    }
    return array('url'=>$url,'url_jumps'=>$urlJumps);
}

function http_parse_headers( $header )
{
    $retVal = array();
    $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
    foreach( $fields as $field ) {
        if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
            $match[1] = preg_replace('/(?< =^|[\x09\x20\x2D])./e', 'strtoupper("")', strtolower(trim($match[1])));
            if( isset($retVal[$match[1]]) ) {
                $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
            } else {
                $retVal[$match[1]] = trim($match[2]);
            }
        }
    }
    return $retVal;
}

function get_itunes_data($url)
{
    //https://itunes.apple.com/us/app/hotels.com-hotel-booking-last/id284971959?mt=8

    $start = strpos($url,'/id') + 3;
    $end = strpos($url,'?');
    $id = substr($url,$start,$end-$start);


    $json = json_decode(file_get_contents('http://itunes.apple.com/lookup?id=' . $id));
    $icon = $json->results[0]->artworkUrl60;//$json['results'][0]['artworkUrl60'];
    $description = $json->results[0]->description;
    $title = $json->results[0]->trackName;
    return json_encode(array('offerIcon'=>$icon,'offerDescription'=>$description,'offerName'=>$title));
}

function get_googleplay_data($url)
{
    //https://play.google.com/store/apps/details?id=com.muzicall.bugmelater
    $doc = phpQuery::newDocument(file_get_contents($url));
    $icon = $doc['img.cover-image']->attr('src');
    $description = $doc['div.id-app-orig-desc']->text();
    $title = $doc['div.document-title div']->text();
    return json_encode(array('offerIcon'=>$icon,'offerDescription'=>$description,'offerName'=>$title));
}


/*
 *  GRAB NEW OFFERS FROM OUR NETWORK PARTNERS
 */

function fetchNetworkOffers($isRefresh = false) {
    $offerInstance = new OfferManager;

    if($isRefresh || empty($_SESSION['manualOffersData']) || $offerInstance->manual_offers_did_expire())
    {
        $offerInstance->purge_manual_offers_cache();
        $_SESSION['manualOffersData'] = '';
        $_SESSION['manualOffersTime'] = '';
        $_SESSION['manualOffersTime'] = time();

        $hasOffers = $offerInstance->get_hasoffers_offers(OFFERS_APPS);
        $ksixOffers = $offerInstance->get_ksix_offers(OFFERS_APPS);
        $adActionOffers = $offerInstance->get_adaction_offers(OFFERS_APPS);
        $result = array_merge((array)$hasOffers,(array)$ksixOffers,(array)$adActionOffers);
        $_SESSION['manualOffersData'] = $result;
        return $result;
    } else
    {
        unset($offerInstance);
        $result = $_SESSION['manualOffersData'];
        return $result;
    }
}

/*
 *  GRAB OFFERS WE HAVE ADDED LOCALLY AND LIKELY HAVE LIVE
 */

function fetchLocalOffers() {
    $sysOfferInstance = new Offer();
    $result = $sysOfferInstance->get_offer_list(true);
    return $result;
}


switch($controller_function)
{
    case 'pause':
        $offerID = $controller_id;
        $sysOfferInstance = new Offer();
        $result = $sysOfferInstance->disable_offer($offerID);
        if(empty($result)) {
            $modalHeader = 'Error saving offer';
            $modalMessage = 'There was an error pausing offer #' . $offerID .'. Please contact the admin.';
            $modalType = 3;
        } else {
            $modalHeader = 'Success';
            $modalMessage = 'Offer ID ' . $offerID . ' has successfully been paused!';
            $modalType = 2;
        }

        exit(json_encode(array(
            'title'=>$modalHeader,
            'message'=>$modalMessage,
            'type' => $modalType,
        )));

        break;
    case 'start':
        $offerID = $controller_id;
        $sysOfferInstance = new Offer();
        $result = $sysOfferInstance->enable_offer($offerID);
        if(empty($result)) {
            $modalHeader = 'Error saving offer';
            $modalMessage = 'There was an error starting offer #' . $offerID .'. Please contact the admin.';
            $modalType = 3;
        } else {
            $modalHeader = 'Success';
            $modalMessage = 'Offer ID ' . $offerID . ' has successfully been enabled!';
            $modalType = 2;
        }

        exit(json_encode(array(
            'title'=>$modalHeader,
            'message'=>$modalMessage,
            'type' => $modalType,
        )));

        break;
    case 'delete':
        $offerID = $controller_id;
        $sysOfferInstance = new Offer();
        $result = $sysOfferInstance->delete_offer($offerID);
        if(empty($result)) {
            $modalHeader = 'Error saving offer';
            $modalMessage = 'There was an error deleting offer #' . $offerID .'. Please contact the admin.';
            $modalType = 3;
        } else {
            $modalHeader = 'Success';
            $modalMessage = 'Offer ID ' . $offerID . ' has successfully been removed!';
            $modalType = 2;
        }

        exit(json_encode(array(
            'title'=>$modalHeader,
            'message'=>$modalMessage,
            'type' => $modalType,
        )));
        break;


    case 'appstoredata':
        if(!isset($_POST['url'])) exit;
        $url = (!strpos($_POST['url'],'itunes.apple.com') && !strpos($_POST['url'],'play.google.com'))?get_full_url($_POST['url'])['url']:$_POST['url'];
        if(strpos($url,'itunes.apple.com')) exit(get_itunes_data($url));
        if(strpos($url,'play.google.com')) exit(get_googleplay_data($url));
        exit(json_encode(array('offerDescription'=>'Not a valid iTunes or Google Play link','offerName'=>'Invalid','offerIcon'=>'')));
    break;

    case 'list':
            if($controller_id == 'r') {
                $result = fetchNetworkOffers(true);
            } else $result = fetchNetworkOffers();
        $smarty->assign('result',$result);
        $smarty->assign('icons',$_SESSION['manualOffersThumbnails']);
        $smarty->assign('countries',$_SESSION['manualOffersCountries']);
        $smarty->display(VIEW_PATH . $controller_name . '.offerlist' . VIEW_EXT);
        break;

    case 'update':

        $sysOfferInstance = new Offer();


        $offerUserPayout = $_POST['offerUserPayout'];
        $offerReferralPayout = $_POST['offerReferralPayout'];
        $offerDescription = $_POST['offerDescription'];
        $offerIcon = $_POST['offerIcon'];
        $offerID = $_POST['offerID'];
        $offerName = $_POST['offerName'];
        $offerCountries = isset($_POST['offerCountries'])?$_POST['offerCountries']:'INT';
        $offerNetworkPayout = $_POST['offerNetworkPayout'];
        $offerPlatform = $_POST['offerPlatform'];
        $offerType = $_POST['offerType'];

        if(!isset($offerUserPayout)
            ||!isset($offerReferralPayout)
            ||!isset($offerDescription)
            ||!isset($offerIcon)
            ||!isset($offerID)
            ||!isset($offerName)
            ||!isset($offerCountries)
            ||!isset($offerNetworkPayout)
            ||!isset($offerPlatform)
            ||!isset($offerType)
        ){
            $modalHeader = 'Error saving offer';
            $modalMessage = 'Offer ID ' . $offerID . ' - "' . $offerName . '" could not be saved. Please make sure you have filled out all the fields.';
            $modalType = 3;
        } else {

            if($sysOfferInstance->offer_exists($offerID))
            {
                $result = $sysOfferInstance->update_offer(array(
                    'offer_id'=>$offerID,
                    'offer_type'=>$offerType,
                    'offer_user_payout'=>intval($offerUserPayout),
                    'offer_referral_payout'=>intval($offerReferralPayout),
                    'offer_network_payout'=> number_format(floatval($offerNetworkPayout),2),
                    'offer_image_url'=>$offerIcon,
                    'offer_name'=>htmlspecialchars($offerName),
                    'offer_description'=>htmlspecialchars($offerDescription),
                    'offer_country'=>$offerCountries,
                    'offer_platform'=>$offerPlatform,
                ));

                if(empty($result)) {
                    $modalHeader = 'Error saving offer';
                    $modalMessage = 'There was an error updating offer #' . $offerID . ' in the database. Please contact the admin.';
                    $modalType = 3;
                } else {
                    $modalHeader = 'Success';
                    $modalMessage = 'Offer ID ' . $offerID . ' - "' . $offerName . '" has successfully been saved!';
                    $modalType = 2;
                }
            }  else {
                $modalHeader = 'Error saving offer';
                $modalMessage = 'Offer #' . $offerID . ' - "' . $offerName. '" could not be updated because it does not exist in the database. Please contact the admin.';
                $modalType = 3;
            }


        }

        exit(json_encode(array(
            'title'=>$modalHeader,
            'message'=>$modalMessage,
            'type' => $modalType,
        )));



        break;

    case 'add':
        $sysOfferInstance = new Offer();

        $offerInstance = new OfferManager();

        $offerUserPayout = $_POST['offerUserPayout'];
        $offerReferralPayout = $_POST['offerReferralPayout'];
        $offerNetwork = $_POST['offerNetworkSource'];
        $offerDescription = $_POST['offerDescription'];
        $offerIcon = $_POST['offerIcon'];
        $offerID = $_POST['offerID'];
        $offerName = $_POST['offerName'];
        $offerCountries = isset($_POST['offerCountries'])?$_POST['offerCountries']:'INT';
        $offerNetworkPayout = $_POST['offerNetworkPayout'];
        $offerDestination = $_POST['offerDestination'];
        $offerPlatform = $_POST['offerPlatform'];
        $offerType = $_POST['offerType'];


        $offerSourceID = 0;
        switch($offerNetwork)
        {
            case 'adaction': $offerSourceID = ADACTION_API_PROVIDER_ID; $offerClickUrl =$offerInstance->get_hasoffers_url(ADACTION_API_PROVIDER_ID,$offerID); break;
            case 'hasoffers': $offerSourceID = HASOFFERS_API_PROVIDER_ID; $offerClickUrl = $offerInstance->get_hasoffers_url(HASOFFERS_API_PROVIDER_ID,$offerID);  break;
            case 'ksix': $offerSourceID = KSIX_API_PROVIDER_ID; $offerClickUrl = $offerInstance->get_hasoffers_url(KSIX_API_PROVIDER_ID,$offerID);  break;
        }


        if($offerInstance->manual_offers_did_expire()){
            $modalHeader = 'Error saving offer';
            $modalMessage = 'It looks like the offers pulled from the network have expired. Please hit the "Refresh Offers" button in the offers menu.';
            $modalType = 3;
        } else
        if(!isset($offerUserPayout)
            ||!isset($offerReferralPayout)
            ||!isset($offerNetwork)
            ||!isset($offerDescription)
            ||!isset($offerIcon)
            ||!isset($offerID)
            ||!isset($offerName)
            ||!isset($offerCountries)
            ||!isset($offerNetworkPayout)
            ||!isset($offerDestination)
            ||!isset($offerPlatform)
            ||!isset($offerType)
            ||!isset($offerClickUrl)
        ){
            $modalHeader = 'Error saving offer';
            $modalMessage = 'Offer ID ' . $offerID . ' - "' . $offerName . '" could not be saved. Please make sure you have filled out all the fields.';
            $modalType = 3;
        } else {

            if(!$sysOfferInstance->offer_external_id_exists($offerID))
            {
                $result = $sysOfferInstance->add_offer(array(
                    'offer_type'=>$offerType,
                    'offer_external_id'=>$offerID,
                    'offer_external_cost'=>0,
                    'offer_source_id'=>$offerSourceID,
                    'offer_user_payout'=>intval($offerUserPayout),
                    'offer_referral_payout'=>intval($offerReferralPayout),
                    'offer_network_payout'=> number_format(floatval($offerNetworkPayout),2),
                    'offer_image_url'=>$offerIcon,
                    'offer_filter'=>'',
                    'offer_name'=>htmlspecialchars($offerName),
                    'offer_description'=>htmlspecialchars($offerDescription),
                    'offer_country'=>$offerCountries,
                    'offer_platform'=>$offerPlatform,
                    'offer_click_url'=>$offerClickUrl,
                    'offer_destination'=>$offerDestination
                ));

                if(empty($result)) {
                    $modalHeader = 'Error saving offer';
                    $modalMessage = 'There was an error inserting offer #' . $offerID . 'from ' . $offerNetwork. ' into the database. Please contact the admin.';
                    $modalType = 3;
                } else {
                    $modalHeader = 'Success';
                    $modalMessage = 'Offer ID ' . $offerID . ' - "' . $offerName . '" has successfully been saved!';
                    $modalType = 2;
                }
            }  else {
                $modalHeader = 'Error saving offer';
                $modalMessage = 'Offer #' . $offerID . ' from ' . $offerNetwork. ' may have already been inserted into the database already. Please try remove the offer first.';
                $modalType = 3;
            }


        }

        exit(json_encode(array(
            'title'=>$modalHeader,
            'message'=>$modalMessage,
            'type' => $modalType,
        )));
        break;

    default:
        $smarty->assign('date_origin',AR_START_DATE);
        $smarty->assign('page_data',$page_data);

        $result = fetchLocalOffers();
        $smarty->assign('result',$result);
        $smarty->display(HEADER_VIEW);
        $smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
        $smarty->display(FOOTER_VIEW);
        break;
}




//exit('<pre>Raw: ' . print_r($result,true) . PHP_EOL . ' Session:' . print_r($_SESSION,true));



?>