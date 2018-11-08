<?php

$page_data = array(
    'page_title'=> 'Fraud Manager',
    'page_name' =>  $controller_name
);



switch($controller_function)
{

    case 'isp':

        $userInstance = new User();
        switch($controller_id)
        {
            case 'accepted':
                $result = $userInstance->get_sys_isp_accepted_list();
                break;
            case 'denied':
                $result = $userInstance->get_sys_isp_denied_list();
                break;
            case 'all':
                $result = $userInstance->get_sys_isp_all_list();
                break;

            default:
            case 'unresolved':
                $result = $userInstance->get_sys_isp_unresolved_list();
                break;
        }

        //unset($userInstance);
        $smarty->assign('result',$result);
        $smarty->display(VIEW_PATH . $controller_name . '.ispList' . VIEW_EXT);
        exit();
    break;


    case 'update':

        $successCount = 0;
        $errorCount = 0;
        $ispList = $_POST['ispList'];
        if(isset($ispList))
        {
            foreach($ispList as $isp)
            {

                $ispID = $isp['ispID'];
                $ispName = $isp['ispName'];
                $ispCountry = (isset($isp['ispCountry']))?$isp['ispCountry']:'';
                $ispStatus = intval($isp['ispStatus']);

                $userInstance = new User();

                if(isset($ispID) && isset($ispName) && isset($ispStatus) && is_numeric($ispStatus)){
                    $result = $userInstance->update_sys_ip(array(
                        'ispName'=>$ispName,
                        'ispID'=>$ispID,
                        'ispCountry'=>$ispCountry,
                        'ispStatus'=>$ispStatus
                    ));

                    if($result)
                    {
                        $successCount++;
                    } else {
                        $errorCount++;
                        continue;
                    }
                } else {
                    $errorCount++;
                    continue;
                }

            }

            $modalHeader = (($successCount > 0)?'Success':'Error updating ISP\'s');
            $modalMessage = $a . ' ' .(($successCount >0)?('Updated a total of ' . $successCount . ' records.'. (($errorCount>0)?('Error updating ' . $errorCount . ' records'):'')):'No updated made, error updating ' . $errorCount . ' records! Please contact admin.');
            $modalType = ($successCount > 0)?2:3;

        } else {
            $ispID = $_POST['ispID'];
            $ispName = $_POST['ispName'];
            $ispCountry = (isset($_POST['ispCountry']))?$_POST['ispCountry']:'';
            $ispStatus = intval($_POST['ispStatus']);

            if(!isset($ispID) || !isset($ispName) || !isset($ispStatus) || !is_numeric($ispStatus))
            {
                $modalHeader = 'Error saving ISP';
                $modalMessage = 'No ISP ID or name was specified, please try again or please contact the admin.';
                $modalType = 3;
            } else {

                $userInstance = new User();
                $result = $userInstance->update_sys_ip(array(
                    'ispName'=>$ispName,
                    'ispID'=>$ispID,
                    'ispCountry'=>$ispCountry,
                    'ispStatus'=>$ispStatus
                ));
                if(!$result)
                {
                    $modalHeader = 'Error saving ISP';
                    $modalMessage = 'There was an error updating ISP #' . $ispID . ' "' .$ispName .'" into the database. Please contact the admin.';
                    $modalType = 3;
                } else {
                    $modalHeader = 'Success';
                    $modalMessage = 'ISP #'. $ispID . ' "' .$ispName . '" has successfully been updated.';
                    $modalType = 2;
                }
            }
        }

        exit(json_encode(array(
            'title'=>$modalHeader,
            'message'=>$modalMessage,
            'type' => $modalType,
        )));
    break;



    default:
        $smarty->assign('page_data',$page_data);
        $smarty->display(HEADER_VIEW);
        $smarty->display(VIEW_PATH . $controller_name . VIEW_EXT);
        $smarty->display(FOOTER_VIEW);
        break;
}




//exit('<pre>Raw: ' . print_r($result,true) . PHP_EOL . ' Session:' . print_r($_SESSION,true));



?>