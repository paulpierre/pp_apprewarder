<?php

$page_data = array(
    'page_title'        =>  'Import Ranking Data',
    'page_name'         =>  $controller_name
);


switch($controller_function)
{
    case 'upload':
        $target_path =  $RESOURCE_PATH['UPLOAD_PATH'] . basename($_FILES['uploadedfile']['name']);
        $target_file =  $_FILES['uploadedfile']['tmp_name'];

        if(!move_uploaded_file($target_file, $target_path)) {

            //print '<span style="color:red;">There was an error uploading the file, please try again!</span>';
            $page_data['error_message'] = 'There was an error uploading your file ' . basename($_FILES['uploadedfile']['name']) . 'Please try again';
            break;
        }

        $f = file_get_contents($target_path);
        $page_data['csv_data'] = $f;
        //$rows = explode("\r",$f);

        /*
        print '<table>';
        foreach($rows as $r)
        {
            $row = explode(",",$r);

            print_r($row);
        }*/

    break;


    default:


    break;
}

$smarty->assign('page_data',$page_data);
$smarty->display($RESOURCE_PATH['VIEW_PATH'] . 'header' . $APP_FILE_EXTENSION['VIEW_EXT']);
$smarty->display($RESOURCE_PATH['VIEW_PATH'] . $controller_name .$APP_FILE_EXTENSION['VIEW_EXT']);
$smarty->display($RESOURCE_PATH['VIEW_PATH'] . 'footer' . $APP_FILE_EXTENSION['VIEW_EXT']);


