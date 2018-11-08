<?php
$APP_NAME = constant('APP_NAME');
$USER_CURRENCY = (!isset($_SESSION['userCredits']))?0:$_SESSION['userCredits'];
$APP_BASE_URL = constant('API_HOST');
$IMG_RES = constant('IMG_RES');
$CSS_RES = constant('CSS_RES');
$JS_RES = constant('JS_RES');
define('offerDebug',false);
$userDidUnlock = $userInstance->didUnlockAllApps;

//if the cache expires or is triggered by the client with /offer/a/r
if($controllerID == 'r') {$offerInstance->purge_offers(); unset($_SESSION['offerNetwork']); }
switch(strtolower($controllerFunction))
{
    case 'av':  get_offers(OFFERS_ALL);                     break; //apps and videos (ALL)
    case 'v':   get_offers(OFFERS_VIDEO);                   break; //video only
    case 'a':   get_offers(OFFERS_APPS);                    break; //apps only
    case 'staff': get_promo_offers();                       break;
    case 'pending': get_pending_offers();                   break;
    case 'get': download_offer($controllerID);              break;
    default:die();break;
}


function download_offer($offerIndex)
{
    global $offerInstance;
    $offerModelInstance = new Offer();
    global $userInstance;
    global $offerNetworkNames;

    if(!$userInstance->user_is_logged_in()){
        print 'Dude, you\'re not even logged in bro! Please log out in the menu on the upper left and try again jabroney.';
        die();
    }

    if(!is_numeric($offerIndex)) {
        print 'It looks like there was an error requesting that particular download. Refresh by clicking the AppRewarder logo and try again.';
        die();
    }

    if(!isset($_SESSION['currentOfferTypes']) || $offerInstance->cache_did_expire($_SESSION['currentOfferTypes']))
    {
        print 'It looks like your session has expired. Please hit the AppRewarder logo above to refresh your offers.';
        die();
    }
    //lets retrieve the offer from the user's cache
    //print 'requested ID:' . $offerIndex;
    //print '<pre>' . print_r($_SESSION['offerData'][$_SESSION['currentOfferTypes']][$offerIndex],true) . '</pre>';

    $offer = $_SESSION['offerData'][$_SESSION['currentOfferTypes']][$offerIndex];
    $offerID = $offer['offerID'];
    $offerName = $offer['offerName'];



    $result = $userInstance->user_update_offer(
        Array(
            'offer_id'=>$offer['offerID'],
            'offer_payout'=>$offer['offerUserPayout'],
            'user_id'=>$_SESSION['userID'],
            'offer_referral_payout'=>$offer['offerReferralPayout'],
            'offer_cost'=>$offer['offerUserCost'],
            'offer_network_id'=>$offer['offerNetworkID'],
            'offer_network'=>$offer['offerSrc'],
            'offer_network_payout'=>$offer['offerNetworkPayout'],
            'offer_name'=>$offer['offerName'],
            'offer_image_url'=>$offer['offerImage'],
            'offer_url'=>$offer['offerURL'],
            'user_ip'=>((MODE=='prod')?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR'])
        )
    );

    if(is_bool($result) && $result == true)
    {
        print 'Your first attempt to download <b>' . $offer['offerName'] . '</b> for <span class="inline-coin coin-balance-icon"></span><b>' . $offer['offerUserPayout'] .'</b> is looking great. Use the app for 30 seconds, and <b>REMEMBER:</b> You will not receive coins for apps that have been downloaded before on this device.';
        die();
    } elseif(is_numeric($result))
    {
        print 'It looks like this is attempt #' . $result . '. Apps you download should appear in pending in the Offers page. <b>REMEMBER:</b> you will not receive coins for apps that have been downloaded before on this device. Please email us with any issues at: ' . SUPPORT_EMAIL;
        die();
    } else {
        print 'It seems like you ran into a pretty serious error. Actually, lets face it, it\'s pretty serious. Please email us at: ' . SUPPORT_EMAIL . ' with the details.';
        die();
    }
    die();
}


function get_pending_offers()
{
    global $userInstance;
    if(!isset($_SESSION['userID'])) return false;
    $offersData = $userInstance->get_pending_offers($_SESSION['userID']);
    if(!empty($offersData)) UtilityManager::aasort($offersData,'offer_tmodified',SORT_DESC);
    include(VIEW_PATH . 'pendingOffersView.php');
    die();

}

function get_promo_offers()
{
    global $userInstance;
    global $controllerFunction;
    include(VIEW_PATH . 'promoView.php');
    die();
}

function get_offers($offerType)
{

    global $userInstance;
    //global $offerInstance;
    $offerInstance = new OfferManager;
    if($offerInstance->cache_did_expire($offerType))
    {
        $offerInstance->purge_offers();
        $userInstance->user_refresh_data();
    }

    global $offerNetworkRank;
    global $offerCurrentPage;
    global $controllerID;


    $offerCurrentPage = (isset($controllerID) && is_numeric($controllerID))?intval($controllerID):1; //  domain/offer/1 where 1 is the page being requested, by default it will load page 1
    $_SESSION['offerCurrentPage'] = $offerCurrentPage;
    $offerNetworkCount = count($offerNetworkRank);

    if(!isset($_SESSION['offerData'])) $_SESSION['offerData'] = Array();
    if(!isset($_SESSION['offerNetwork'])) {
        $_SESSION['offerNetwork'] = Array();
        foreach($offerNetworkRank as $offerNetwork) //initialize each index we listed in the config file
        {
            $_SESSION['offerNetwork'][$offerNetwork] = '';
            $_SESSION['offerNetwork'][$offerNetwork]['offerSize'] = '';
        }
        $offerNetworkCurrentIndex = 0;
        $_SESSION['offerNetwork']['currentIndex'] = 0;
    } else {
        $offerNetworkCurrentIndex = (isset($_SESSION['offerNetwork']['currentIndex']) && is_numeric($_SESSION['offerNetwork']['currentIndex']))?intval($_SESSION['offerNetwork']['currentIndex']):0;

    }

    $offerCurrentSize = count($_SESSION['offerData'][$offerType]); //see how many we have in the cache

    $offerRequestSize = $offerCurrentPage * OFFER_PAGE_MAX;
    if(offerDebug)
    {
    print 'OFFER_PAGE_MAX:' . OFFER_PAGE_MAX . '<br>';
    print 'offerCurrentPage:' . $offerCurrentPage. ' <br>';
    print 'offerNetworkCount: ' . $offerNetworkCount .' <br>';
    print 'offerNetworkCurrentIndex: ' . $offerNetworkCurrentIndex . ' <br>';
    print 'offerRequestSize: ' . $offerRequestSize . ' <br>';
    print 'offerCurrentSize: ' . $offerCurrentSize . ' <br>';
    print 'requested offerType:' . $offerType . '<br>';

    print 'network size: <pre>' . print_r($_SESSION['offerNetwork'],true) . '</pre>';
    }

    //CALL WITHIN ARRAY BOUNDS
    if($offerRequestSize <= $offerCurrentSize )
    {
        if(offerDebug) print 'Within bounds.<br>';

        //the page being requested is not out of scope, so lets return what we have in cache

        //if were starting at page 1, then the starting index is 0 for the array
        //if its larger than 1, then the starting point would be the very end of the last page
        //so figure out the previous page (currentpage-1) and then go the the page max then add 1!
        $offerIndexStart = ($offerCurrentPage == 1)?0:(intval($offerCurrentPage-1) * OFFER_PAGE_MAX);
        $offerIndexFinish = ($offerRequestSize - 1); //since its 0 indexed, decrease by 1 and additional 1 since the page isn't zero index as well
        show_offers($_SESSION['offerData'][$offerType],$offerIndexStart,$offerIndexFinish);
        exit();

    }

    //CALL OUTSIDE ARRAY BOUNDS

    //so we're out of bounds, regardless we need to call the next network. keep iterating until
    //the size is sufficient

    if(offerDebug) print 'Outside bounds.<br>';



    //INCREASE OFFER LIST WITH ADDITIONAL NETWORK CALLS
    //keep calling networks until either we have enough offers to meet the request or we simply run out of networks to grab offers from
    //print 'i: ' . $i . ' offerNetworkCurrentIndex: ' . $offerNetworkCurrentIndex . ' offerCurrentSize: ' . $offerCurrentSize . ' offerRequestSize: ' . $offerRequestSize . ' offerNetworkCount: ' . $offerNetworkCount.  ' <br>';


    for($i=$offerNetworkCurrentIndex; ($offerCurrentSize < $offerRequestSize && $i !== $offerNetworkCount ); ++$i)

    {
        if(offerDebug)  print 'i: ' . $i . ' offerNetworkCurrentIndex: ' . $offerNetworkCurrentIndex . ' offerCurrentSize: ' . $offerCurrentSize . ' offerRequestSize: ' . $offerRequestSize . ' offerNetworkCount: ' . $offerNetworkCount.  ' <br><br>';

        if(offerDebug) print 'Calling network: #' . $offerNetworkRank[$i] . ' with index: ' . $i . '<br><br>';
        switch($offerNetworkRank[$i])
        {
            case APPREWARDER_API_PROVIDER_ID:
                $offerData = $offerInstance->get_local_offers($offerType);          //lets request offers from the given network
                if(offerDebug) print '<br><b>LOCAL OFFERS:</b>'.count($offerData).' <br><br>';
                break;

            case ADSCEND_API_PROVIDER_ID:
                $offerData = $offerInstance->get_adscend_offers($offerType);
                if(offerDebug) print '<b>ADSCEND OFFERS:</b>'.count($offerData).' <br><br>';
                break;

            case AARKI_API_PROVIDER_ID:
                $offerData = $offerInstance->get_aarki_offers($offerType);
                if(offerDebug) print '<b>AARKI OFFERS:</b>'.count($offerData).' <br><br>';
                break;

            case FYBER_API_PROVIDER_ID:
                $offerData = $offerInstance->get_fyber_offers($offerType);
                if(offerDebug) print '<b>FYBER OFFERS:</b>'.count($offerData).' <br><br>';
                break;
        }

        //print '<pre style="color:#000;">'.print_r($offerData, true) . '</pre><br><br>';
        if(!$offerData) {
            $offerNetworkSize = 0;
        } else {
            $offerNetworkSize = count($offerData);                              //lets also grab the size of this particular networks offers
            if(offerDebug) print 'Offer count:'. $offerNetworkSize.'<br>';

            $newArray = array_merge((array)$_SESSION['offerData'][$offerType],(array) $offerData);                  //now lets add to our offers
            UtilityManager::aasort($newArray,'offerUserPayout'); //sort offers by highest payout desc
            $_SESSION['offerData'][$offerType] = $newArray;

            $_SESSION['offerNetwork'][$offerNetworkRank[$i]]['offerSize'] = $offerNetworkSize;     //and lets store this

        }
        $offerCurrentSize = count($_SESSION['offerData'][$offerType]);      //lets update how large array is so we can let the for loop know



    }

    if(offerDebug) print 'total iterations made: ' . $i . ' offers new size: ' . $offerCurrentSize .'<br>';

    $offerNetworkCurrentIndex = $i; //lets update the current ad network index we left off on
    $_SESSION['offerNetwork']['currentIndex'] = $offerNetworkCurrentIndex;


    //two states are possible, either we met the required size to reach the requested page, or not. lets see.
    if($offerRequestSize <= $offerCurrentSize)
    {
        if(offerDebug) print '<h1>offerRequestSize <= offerCurrentSize TRUE!</h1><br><br>';
        $offerIndexStart = ((intval($offerCurrentPage)-1) * OFFER_PAGE_MAX);
        $offerIndexFinish = $offerRequestSize - 1;
        show_offers($_SESSION['offerData'][$offerType],$offerIndexStart,$offerIndexFinish);
        exit();
    }

    //so a page is being requested that is out of bounds, but there is the possibility that
    //additional offers may be available, just not at the size of OFFER_PAGE_MAX, lets see
    $offerDeltaSize = ($offerCurrentSize - (($offerCurrentPage - 1) * OFFER_PAGE_MAX));
    if($offerDeltaSize > 0) //lets provide these offers to them
    {
        if(offerDebug) print '<h1>offerDeltaSize > 0 TRUE!</h1><br><br>';
        $offerIndexStart = $offerCurrentSize - $offerDeltaSize ;//$offerRequestSize - OFFER_PAGE_MAX;
        $offerIndexFinish = $offerCurrentSize - 1;
        show_offers($_SESSION['offerData'][$offerType],$offerIndexStart,$offerIndexFinish);
        exit();
    }

    //ERROR
    // and if for some reason we arrive here, it just means they request a page out of bounds

    if(offerDebug) print 'sorry, perhaps you requested a page out of bounds?';

    //print '<script>$(".btn-load-more").hide();</script>';
    exit();

}


function show_offers($offerArray,$offerIndexStart,$offerIndexFinish)
{

        if(offerDebug) {
            print 'REQUESTED RANGE:' . $offerIndexStart . ' - '.$offerIndexFinish.' <br>';

            print '<h1>RANGE DATA:</h1><pre>';
            for($i = $offerIndexStart;$i <= $offerIndexFinish; $i++)
            {
                print '<b>index: ' .$i.'</b><br>' . print_r($offerArray[$i],true) . '<br>';
            }
            print '</pre><br><br>';

        }
        $offerData = Array(
            'offerArray'=>$offerArray,
            'offerIndexStart'=>$offerIndexStart,
            'offerIndexFinish'=>$offerIndexFinish
        );
        if(!offerDebug)include(VIEW_PATH . 'offerView.php');

        if(offerDebug) print '<h1>SESSION</h1><br><pre>' . print_r($_SESSION,true);
}

exit();





?>