<?php

//http://apprewarder.com/cb/$network_name/
//call back for 3rd party hosts to verify installs
//$cbUserID = $controllerID;

$offerInstance = new OfferManager();
//$utilityInstance = new UtilityManager();
//$network_name = $controllerFunction;

//$referral_user_id = (isset($_GET['userReferralUserID']) && $_GET['userReferralUserID'] !== '')?$_GET['userReferralUserID']:0;


switch(strtolower($network_name))
{
case 'fyber':
    $user_id = $_GET['uid'];
    //$user_os = intval($_GET['pub0']);
    //if($user_os == 0) exit();
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($_GET['payout_net']),FYBER_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $transaction_id = $_GET['_trans_id_'];
    $offer_id = $_GET['lpid'];
    //$amount = $_GET['amount'];
    //$cb_hash = $_GET['sid'];
    //$cb_token = ($user_os == PLATFORM_ANDROID)?FYBER_API_CB_TOKEN_ANDROID:FYBER_API_CB_TOKEN_IOS;
    //if($cb_hash !== sha1($cb_token . $user_id . $amount . $transaction_id)) exit();
    $utilityInstance->log('[callback.php] Fyber callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'everbadge':
    $user_id = $_GET['userID'];
    $offer_id = $_GET['offerID'];
    $offer_payout = $_GET['userPayout'];
    $referral_payout = $_GET['userReferralPayout'];
    $transaction_id = $_GET['transactionID'];
    $utilityInstance->log('[callback.php] Everbadge callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;


case 'adscend':
    //http://m.stage.apprewarder.com/cb/adscend/?offerID=[OID]&networkPayout=[PAY]&transactionID=[TID]&userID=[SID]&status=[STS]
    $user_id = $_GET['userID'];
    $network_payout = $_GET['networkPayout'];
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($network_payout),ADSCEND_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $transaction_id = $_GET['transactionID'];
    $offer_id  = $_GET['offerID'];
    $offer_status = $_GET['status'];
    if ($offer_status == '2') exit(); // a STS of 2 means its revoked
    $utilityInstance->log('[callback.php] Adscend callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'flurry':
    //http://apprewarder.com/cb/flurry/?udid=%{udid}&appPrice=%{appPrice}
    $user_id = $_GET['uid'];
    $offer_id = $_GET['oid'];
    $transaction_id = $offer_id;//$_GET['fguid'];
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($_GET['payout']),FLURRY_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $utilityInstance->log('[callback.php] Flurry callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'w3i':
    //http://apprewarder.com/offer/cb/aarki/?dev={device_id}&units={reward}&id={transaction_id}
    $user_id = $_GET['userid'];
    $transaction_id = md5($_GET['offerid']);
    $offer_id = md5($_GET['offerid']);
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($_GET['payoutamount']),W3I_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    //unfortunately below is a hack because the callbacks are pretty limited in what they're willing to return.
    //$offer_payout = intval(floatval($_GET['payoutamount']) * floatval(100*0.20));
    $utilityInstance->log('[callback.php] w3i callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'aarki':
    //http://mstage.apprewarder.com/offer/cb/aarki/?dev={device_id}&units={reward}&id={transaction_id}
    $user_id = $_GET['userID'];
    $transaction_id = $_GET['id'];
    $offer_id = $_GET['offerID'];
    $network_payout = $_GET['payout'];
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($network_payout),AARKI_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $utilityInstance->log('[callback.php] Aarki callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'adaction':
    $user_id = $_GET['userID'];
    $offer_id = $_GET['offerID'];
    $network_payout = $_GET['networkPayout'];
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($network_payout),ADACTION_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $transaction_id = $_GET['transactionID'];
    //aff_sub={aff_sub}
    // http://mstage.apprewarder.com/cb/adaction/?offerID={offer_id}&networkPayout={payout}&deviceID={device_id}&transactionID={transaction_id}&offerName={offer_name}&userID={aff_sub}&userPayout={aff_sub2}
    //LATEST CALLBACK URL:  http://m.stage.apprewarder.com/cb/adaction/?offerID={offer_id}&networkPayout={payout}&deviceID={device_id}&transactionID={transaction_id}&offerName={offerName}&userID={aff_sub}&userPayout={aff_sub2}&userReferralPayout={aff_sub3}&userReferralUserID={aff_sub4}

    //http://m.stage.apprewarder.com/cb/hasoffers/?offerID={offer_id}&networkPayout={payout}&deviceID={device_id}&transActionID={transaction_id}&offerName={offerName}&offerRef={offer_ref}&goalID={goal_id}&affID={affiliate_id}&affName={affiliate_name}&affRef={affiliate_ref}&sourceVal={source}&userID={aff_sub}&userPayout={aff_sub2}&userReferralPayout={aff_sub3}
    ///cb/hasoffers/offerID=68&networkPayout=1.80&deviceID=&transActionID=1022170165ac9c543d4e510b10a239&offerName={offerName}&offerRef=&goalID=0&affID=6&affName=Mintstack&affRef=&sourceVal=pixel_test&userID=10000&userPayout=500
    $utilityInstance->log('[callback.php] Hasoffers callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
    break;


case 'hasoffers':
    $user_id = $_GET['userID'];
    $offer_id = $_GET['offerID'];
    $network_payout = $_GET['networkPayout'];
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($network_payout),HASOFFERS_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $transaction_id = $_GET['transactionID'];
    //aff_sub={aff_sub}
    //LATEST CALLBACK URL:  http://m.stage.apprewarder.com/cb/hasoffers/?offerID={offer_id}&networkPayout={payout}&deviceID={device_id}&transactionID={transaction_id}&offerName={offerName}&userID={aff_sub}&userPayout={aff_sub2}&userReferralPayout={aff_sub3}&userReferralUserID={aff_sub4}

    //http://m.stage.apprewarder.com/cb/hasoffers/?offerID={offer_id}&networkPayout={payout}&deviceID={device_id}&transActionID={transaction_id}&offerName={offerName}&offerRef={offer_ref}&goalID={goal_id}&affID={affiliate_id}&affName={affiliate_name}&affRef={affiliate_ref}&sourceVal={source}&userID={aff_sub}&userPayout={aff_sub2}&userReferralPayout={aff_sub3}
    ///cb/hasoffers/offerID=68&networkPayout=1.80&deviceID=&transActionID=1022170165ac9c543d4e510b10a239&offerName={offerName}&offerRef=&goalID=0&affID=6&affName=Mintstack&affRef=&sourceVal=pixel_test&userID=10000&userPayout=500
    $utilityInstance->log('[callback.php] Hasoffers callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'ksix':
    $user_id = $_GET['userID'];
    $offer_id = $_GET['offerID'];
    $network_payout = $_GET['networkPayout'];
    $payout_conversion = $offerInstance->offer_payout_conversion(floatval($network_payout),KSIX_API_PROVIDER_ID);
    $offer_payout = $payout_conversion['userPayout'];
    $referral_payout = $payout_conversion['userReferralPayout'];
    $transaction_id = $_GET['offerRef'];
    //$referral_payout = $_GET['userReferralPayout']; //TODO: please verify we can do this with KSIX
    //http://m.stage.apprewarder.com/cb/ksix/offerID={offer_id}&offerName={offerName}&offerRef={offer_ref}&goalID={goal_id}&affID={affiliate_id}&affName={affiliate_name}&affRef={affiliate_ref}&sourceVal={source}

    //http://m.stage.apprewarder.com/cb/ksix/?offerID={offer_id}&networkPayout={payout}&deviceID={device_id}&transActionID={transaction_id}&offerName={offerName}&userID={aff_sub}&userPayout={aff_sub2}&userReferralPayout={aff_sub3}&userReferralUserID={aff_sub4}
    //$utilityInstance->log('[offer.php] Hasoffers callback: uid:' . $user_id . ' payout:' . $offer_payout . ' transaction_id:' . $transaction_id . ' offer_id:' . $offer_id);
break;

case 'supersonicads':

break;

default:
    die();
break;
}

//print 'call back requested from adnetwork:'. $controllerID . ' userid:' . $user_id . ' for amount:' . $offer_payout;

$utilityInstance->log('call back requested from adnetwork:'. $controllerID . ' userid:' . $user_id . ' for amount:' . $offer_payout);

$offerData = Array(
    //$user_id,$offer_payout,$network_name,$transaction_id,$offer_id
    'user_id'=>$user_id,
    'offer_payout'=>$offer_payout,
    'network_name'=>$network_name,
    'transaction_id'=>$transaction_id,
    'offer_id'=>$offer_id,
    'referral_payout'=>$referral_payout,
    'network_payout'=>$network_payout,
    'network_cb_id'=> $offer_id
);

if(!$userInstance->user_credit_offer($offerData)) {
    echo 'You\'ve been a naughty boy, and now you must repent.';
    exit();
}
header('HTTP/1.1 200 OK');
die();


?>