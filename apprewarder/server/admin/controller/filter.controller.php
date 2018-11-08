<?php

//define('');

$filterConst = array(
    'OFFER_FILTER_TARGET_TITLE'=>OFFER_FILTER_TARGET_TITLE,
    'OFFER_FILTER_TARGET_DESCRIPTION'=>OFFER_FILTER_TARGET_DESCRIPTION,
    'OFFER_FILTER_ACTION_HIDE'=>OFFER_FILTER_ACTION_HIDE,
    'OFFER_FILTER_CONDITION_NONE'=>OFFER_FILTER_CONDITION_NONE,
    'OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT'=>OFFER_FILTER_CONDITION_IS_HIGHEST_PAYOUT,
    'OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT'=>OFFER_FILTER_CONDITION_IS_LOWEST_PAYOUT,
    'OFFER_FILTER_CONDITION_HAS_ICON'=>OFFER_FILTER_CONDITION_HAS_ICON,
    'OFFER_FILTER_CONDITION_HAS_NO_ICON'=>OFFER_FILTER_CONDITION_HAS_NO_ICON
);

$filterAdNetworks = array(
    'KSIX_API_PROVIDER_ID'=>KSIX_API_PROVIDER_ID,
    'HASOFFERS_API_PROVIDER_ID'=>HASOFFERS_API_PROVIDER_ID,
    'AARKI_API_PROVIDER_ID'=>AARKI_API_PROVIDER_ID,
    'ADSCEND_API_PROVIDER_ID'=>ADSCEND_API_PROVIDER_ID,
    'ADACTION_API_PROVIDER_ID'=>ADACTION_API_PROVIDER_ID
);



switch($controller_function)
{
    case 'add':
        $sysOfferInstance = new Offer();

        $offerFilterAction = $_POST['offerFilterAction'];
        $offerFilterCondition = $_POST['offerFilterCondition'];
        $offerFilterNetwork = $_POST['offerFilterNetwork'];
        $offerFilterPlatform = $_POST['offerFilterPlatform'];
        $offerFilterTarget = $_POST['offerFilterTarget'];
        $offerFilterText = strtolower($_POST['offerFilterText']);
        $offerFilterName = $_POST['offerFilterName'];
        $offerFilterCountries = !empty($_POST['offerFilterCountries'])?$_POST['offerFilterCountries']:'INT';

        $o = array(
            'offerFilterAction'=>$offerFilterAction,
            'offerFilterCondition'=>$offerFilterCondition,
            'offerFilterNetwork'=>$offerFilterNetwork,
            'offerFilterPlatform'=>$offerFilterPlatform,
            'offerFilterTarget'=>$offerFilterTarget,
            'offerFilterText'=>$offerFilterText,
            'offerFilterName'=>$offerFilterName,
            'offerFilterCountries'=>$offerFilterCountries
        );

        if( isset($offerFilterAction)
            && is_numeric($offerFilterAction)
            && isset($offerFilterCondition)
            && is_numeric($offerFilterCondition)
            && isset($offerFilterNetwork)
            && is_numeric($offerFilterNetwork)
            && isset($offerFilterPlatform)
            && is_numeric($offerFilterPlatform)
            && isset($offerFilterTarget)
            && isset($offerFilterCountries)
            && is_numeric($offerFilterTarget)
            && isset($offerFilterText)
            && strlen($offerFilterText) > 0
            && isset($offerFilterName)
            && strlen($offerFilterName) > 0
        ){
            $result = $sysOfferInstance->add_offer_filter($offerFilterName,$o);
            if(empty($result))
            {
                $modalHeader = 'Error!';
                $modalMessage = 'There was an error inserting filter "' . $offerFilterName .'" into the database. Please contact the admin.';
                $modalType = 3;
            } else {
                $modalHeader = 'Success!';
                $modalMessage = 'Added "' . $offerFilterName .'" to the filters.';
                $modalType = 2;
            }
        } else {
            $modalHeader = 'Error!';
            $modalMessage = 'A filter name and keyword text must be set. Please make sure you have filled out all the necessary fields before saving!';
            $modalType = 3;
        }
        break;

    case 'update':
        $sysOfferInstance = new Offer();

        $offerFilterID = ((isset($_POST['offerFilterID'])))?intval($_POST['offerFilterID']):null;
        $offerFilterStatus =  ((isset($_POST['offerFilterStatus'])))?intval($_POST['offerFilterStatus']):null;
/*
                exit(json_encode(array(
                    'title'=>'twerk',
                    'message'=>print_r($_POST,true),
                    'type' => 3,
                )));
*/
        //this means user is either attempting to delete or disable the offer
        if($offerFilterID !== null && $offerFilterStatus !==null)
        {
            $result = $sysOfferInstance->update_offer_filter($offerFilterID,array('filter_status'=>$offerFilterStatus));
            switch($offerFilterStatus)
            {
                case 0: $action = 'disabling';break;
                case 1: $action = 'enabling'; break;
                case 5: $action = 'deleting'; break;
            }
            if(empty($result))
            {
                $modalHeader = 'Error!';
                $modalMessage = 'There was an error ' .$result . $action . ' filter ID #' . $offerFilterID .' in the database. Please contact the admin.';
                $modalType = 3;
            } else {
                $modalHeader = 'Success!';
                $modalMessage = 'Success in ' . $action . ' filter ID #' . $offerFilterID .'.';
                $modalType = 2;
            }
            //otherwise they are updating their record
        } elseif($sysOfferInstance->filter_exists($offerFilterID)) {
            $offerFilterAction = $_POST['offerFilterAction'];
            $offerFilterCondition = $_POST['offerFilterCondition'];
            $offerFilterNetwork = $_POST['offerFilterNetwork'];
            $offerFilterPlatform = $_POST['offerFilterPlatform'];
            $offerFilterTarget = $_POST['offerFilterTarget'];
            $offerFilterText = strtolower($_POST['offerFilterText']);
            $offerFilterName = $_POST['offerFilterName'];
            $offerFilterCountries = (isset($_POST['offerFilterCountries']))?$_POST['offerFilterCountries']:'INT';

            $offerData = json_encode(array(
                'offerFilterAction'=>$offerFilterAction,
                'offerFilterCondition'=>$offerFilterCondition,
                'offerFilterNetwork'=>$offerFilterNetwork,
                'offerFilterPlatform'=>$offerFilterPlatform,
                'offerFilterTarget'=>$offerFilterTarget,
                'offerFilterText'=>$offerFilterText,
                'offerFilterCountries'=>$offerFilterCountries
            ));
            $o = array(
                'filter_name'=>$offerFilterName,
                'filter_tmodified'=>time(),
                'filter_data'=>$offerData
            );

            if( isset($offerFilterAction)
                && is_numeric($offerFilterAction)
                && isset($offerFilterCondition)
                && is_numeric($offerFilterCondition)
                && isset($offerFilterNetwork)
                && is_numeric($offerFilterNetwork)
                && isset($offerFilterPlatform)
                && is_numeric($offerFilterPlatform)
                && isset($offerFilterTarget)
                && is_numeric($offerFilterTarget)
                && isset($offerFilterText)
                && isset($offerFilterName)
                && strlen($offerFilterName) > 0
            ){
                $result = $sysOfferInstance->update_offer_filter($offerFilterID,$o);
                if(empty($result))
                {
                    $modalHeader = 'Error!';
                    $modalMessage = 'There was an error updating filter "' . $offerFilterName .'" in the database. Please contact the admin.';
                    $modalType = 3;
                } else {
                    $modalHeader = 'Success!';
                    $modalMessage = 'Successfully updated filter "' . $offerFilterName .'"';
                    $modalType = 2;
                }
            } else {
                $modalHeader = 'Error!';
                $modalMessage = 'A filter name must be set. Please make sure you have filled out all the necessary fields before saving!';
                $modalType = 3;
            }
        }


        break;
    case 'list':
        $sysOfferInstance = new Offer();
        $result = $sysOfferInstance->get_offer_filters(true);
        //exit('<pre>' .print_r($result,true) . '</pre>');
        $smarty->assign('filterAdNetworks',$filterAdNetworks);
        $smarty->assign('filterConst',$filterConst);
        $smarty->assign('result',$result);
        $smarty->display(VIEW_PATH . $controller_name . '.filterlist' . VIEW_EXT);
        unset($sysOfferInstance);
        exit();
        break;

    default:
        break;
}

exit(json_encode(array(
    'title'=>$modalHeader,
    'message'=>$modalMessage,
    'type' => $modalType,
)));

?>