<?php

$page_data = array(
    'page_title'=> 'Dashboard',
    'page_name' =>  $controller_name
);

/*
 *  GET DEVICE STATS
 */
$devices = $userInstance->db_query($SQL_DATA['USER_DEVICE_COUNT']);
$iosCount = $androidCount = 0;
foreach($devices as $device)
{
    switch($device['user_platform']) {
        case PLATFORM_IOS:case PLATFORM_IPAD:case PLATFORM_IPHONE:case PLATFORM_IPOD: $iosCount += intval($device['device_count']); break;
        case PLATFORM_ANDROID: $androidCount += intval($device['device_count']); break;
    }
}
//print '<pre>' .print_r($devices,true).'</pre>';
$devices = array('ios_count'=>$iosCount,'android_count'=>$androidCount);


/*
 *  GET NETWORK ACTUAL REVENUE STATS
 */
$analyticsInstance->startDate = AR_START_DATE;
$analyticsInstance->endDate = date('Y-m-d',time());
$aarkiData = $analyticsInstance->aarkiStats();
$adscendData = $analyticsInstance->adscendStats();
$networkData = array(
    'network'=>array(
        'hasoffers'=>$analyticsInstance->hasOffersStats(HASOFFERS_API_NETWORK_ID,HASOFFERS_API_KEY),
        'adaction'=>$analyticsInstance->hasOffersStats(ADACTION_API_NETWORK_ID,ADACTION_API_KEY),
        'ksix'=>$analyticsInstance->hasOffersStats(KSIX_API_NETWORK_ID,KSIX_API_KEY),
        'adscend'=>array(
            'conversions'=>((isset($adscendData['conversions']))?$adscendData['conversions']:'---'),
            'payout'=>((isset($adscendData['revenue']))?$adscendData['revenue']:'---')
        ),
        'aarki'=> array(
            'payout'=>$aarkiData[0]['revenue'] +$aarkiData[1]['revenue'],
            'conversions'=>$aarkiData[0]['transactions'] +$aarkiData[1]['transactions']
        )
    )
);


$result = array_merge(
    (array)$userInstance->db_query($SQL_DATA['GLOBAL_USER'])[0],
    (array)$userInstance->db_query($SQL_DATA['GLOBAL_CALLBACK'])[0],
    (array)$userInstance->db_query($SQL_DATA['USER_EMAIL_COUNT'])[0],
    (array)$userInstance->db_query($SQL_DATA['USER_FB_COUNT'])[0],
    (array)$devices,
    (array)$networkData
);





//print '<pre>' .print_r($result['transactions'],true).'</pre>';
//number_format

//print $SQL_DATA['GLOBAL_USER'];

$smarty->assign('date_origin',AR_START_DATE);
$smarty->assign('page_data',$page_data);
$smarty->assign('result',$result);
$smarty->display(HEADER_VIEW);
$smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
$smarty->display(FOOTER_VIEW);